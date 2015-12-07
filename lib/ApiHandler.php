<?php

namespace opus\runkeeper;

use GuzzleHttp\Client;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

/**
 * RunKeeper main class
 *
 * @author Original Author Pierre RASO - eX Nihili pierre@exnihili.com
 * @see Forked from https://github.com/madewithlove/runkeeper
 */
class ApiHandler
{
    public $api_conf;
    public $api_last_error = null;
    public $access_token = null;
    public $token_type = 'Bearer';
    public $api_request_log = null;
    private $api_base_url;
    private $api_conf_file;
    private $http_client;

    /**
     * Build a new instance of RunKeeper
     *
     * @param string $api_conf_file Path to the configuration file
     * @throws \Exception
     */
    public function __construct($api_conf_file)
    {
        $this->api_conf_file = $api_conf_file;
        $this->http_client = $this->createHttpClient();

        try {
            if (!file_exists($api_conf_file) || !is_file($api_conf_file) || !is_readable($api_conf_file)) {
                throw new \Exception("Unable to find/read the YAML api_conf_file : $api_conf_file");
            } else {
                $values = Yaml::parse($api_conf_file);
                $this->api_conf = json_decode(json_encode($values));
                $this->api_base_url = $this->api_conf->App->api_base_url;
            }
        } catch (ParseException $e) {
            throw new \Exception("Unable to parse the YAML string: " . $e->getMessage());
        }
    }

    /**
     * Set the token to use
     *
     * @param string $access_token
     */
    public function setRunkeeperToken($access_token)
    {
        $this->access_token = $access_token;
    }

    /**
     * Do a request on the API
     *
     * @param string $name
     * @param string $type
     * @param array $fields
     * @param string $url
     * @param array $optParams
     *
     * @throws \Exception
     * @return array
     */
    public function doRunkeeperRequest($name, $type, $fields = null, $url = null, $optParams = null)
    {
        if (empty($name) || !isset($this->api_conf->Interfaces->$name)) {
            throw new \Exception("doRunkeeperRequest: wrong or missing Interface name : " . $name);
        } elseif (!$type || !isset($this->api_conf->Interfaces->$name->$type)) {
            throw new \Exception("doRunkeeperRequest: not supported or missing type (Read, Update, Create or Delete)");
        } else {
            $params = ($optParams == null ? '' : '?' . http_build_query($optParams));

            $requestHeaders = [
                'Authorization' => $this->token_type . ' ' . $this->access_token,
            ];
            $curlOptions = [
                CURLOPT_SSL_VERIFYPEER => false,
            ];

            if ($url == null) {
                $requestUrl = $this->api_base_url . $this->api_conf->Interfaces->$name->$type->Url . $params;
            } else {
                if (!preg_match('/^https?:\/\//', $url)) {
                    $url = $this->api_base_url . $url;
                }
                $requestUrl = $url . $params;
            }

            $requestMethod = $this->api_conf->Interfaces->$name->$type->Method;

            switch ($requestMethod) {
                case 'GET':
                    // nothing
                    break;
                case 'POST':
                    $jsonFields = json_encode($fields);
                    $requestHeaders += [
                        'Content-Type' => $this->api_conf->Interfaces->$name->Media_Type,
                        'Content-Length' => strlen($jsonFields),
                    ];
                    $curlOptions[CURLOPT_FOLLOWLOCATION] = false;
                    $curlOptions[CURLOPT_POSTFIELDS] = $jsonFields;
                    break;
                case 'PUT':
                    $jsonFields = json_encode($fields);
                    $requestHeaders += [
                        'Content-Type' => $this->api_conf->Interfaces->$name->Media_Type,
                        'Content-Length' => strlen($jsonFields),
                    ];
                    $curlOptions[CURLOPT_FOLLOWLOCATION] = false;
                    $curlOptions[CURLOPT_POSTFIELDS] = $jsonFields;
                    break;
                case 'DELETE':
                    $requestHeaders += [
                        'Content-Type' => $this->api_conf->Interfaces->$name->Media_Type,
                        'Content-Length' => 0,
                    ];
                    $curlOptions[CURLOPT_FOLLOWLOCATION] = false;
                    break;
            }

            $client = $this->createHttpClient();
            $request = $client->createRequest(
                $requestMethod,
                $requestUrl,
                [
                    'config' => [
                        'curl' => $curlOptions
                    ]
                ]
            );
            $request->addHeaders($requestHeaders);
            $response = $client->send($request);
            $responseCode = $response->getStatusCode();

            if ($responseCode === "200") {
                $decodeResponse = json_decode($response->getBody());
                return ($decodeResponse);
            } elseif (in_array($responseCode, ['201', '204', '301', '304'])) {
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * @return Client
     */
    protected function createHttpClient()
    {
        $client = new Client();
        return $client;
    }
}
