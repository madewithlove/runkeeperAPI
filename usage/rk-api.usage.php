<?php
error_reporting(E_ERROR);

// Include dependencies with composer
require_once('../vendor/autoload.php');


/* API initialization */
$rkAPI = new \opus\runkeeper\ApiHandler(
	__DIR__ . '/../config/rk-api.sample.yml'	/* api_conf_file */
);

$token = ''; // get user token using oauth


/* Your code to retrieve and store $rkAPI->access_token (client-side, server-side or session-side) */
/* Note: $rkAPI->access_token will have to be set et valid for following operations */

$rkAPI->setRunkeeperToken($token);


/* Do a "Read" request on "Profile" interface => return all fields available for this Interface */
$rkProfile = $rkAPI->doRunkeeperRequest('Profile','Read');
print_r($rkProfile);

/* Do a "Read" request on "Settings" interface => return all fields available for this Interface */
$rkSettings = $rkAPI->doRunkeeperRequest('Settings','Read');
print_r($rkSettings);

/* Do a "Read" request on "FitnessActivities" interface => return all fields available for this Interface or false if request fails */
$rkActivities = $rkAPI->doRunkeeperRequest('FitnessActivities','Read');
print_r($rkActivities);


/* Do a "Read" request on "FitnessActivityFeed" interface => return all fields available for this Interface or false if request fails */
$rkActivities = $rkAPI->doRunkeeperRequest('FitnessActivityFeed','Read');
print_r($rkActivities);


/* Do a "Create" request on "FitnessActivity" interface with fields => return created FitnessActivity content if request success, false if not */
$fields = json_decode('{"type": "Running", "equipment": "None", "start_time": "Sat, 1 Jan 2011 00:00:00", "notes": "My first late-night run", "path": [{"timestamp":0, "altitude":0, "longitude":-70.95182336425782, "latitude":42.312620297384676, "type":"start"}, {"timestamp":8, "altitude":0, "longitude":-70.95255292510987, "latitude":42.31230294498018, "type":"end"}], "post_to_facebook": true, "post_to_twitter": true}');
$rkCreateActivity = $rkAPI->doRunkeeperRequest('NewFitnessActivity','Create',$fields);
print_r($rkCreateActivity);
