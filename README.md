# Runkeeper API wrapper

PHP wrapper for Runkeeper Health Graph API

**Version:** 0.4.0-dev

**Author:** Pierre RASO - eX Nihili <pierre@exnihili.com>

Fork from [madewithlove/runkeeper](https://github.com/madewithlove/runkeeper)

## Installation via Composer

Add this to you `composer.json` file, in the require object;

    "opus-online/runkeeper": "dev-master"

After that, run `composer install` to install the RunKeeper API wrapper.

## Dependencies

* Symfony YAML (https://github.com/symfony/yaml) - Installed via Composer
* PHP cURL support (http://www.php.net/manual/en/book.curl.php)
* PHP json support (http://fr2.php.net/manual/en/book.json.php)

## Usage

See /usage/rk-api.sample.php

## ChangeLog :

### v0.4.0 (under development)

* Added namespace
* Added proper composer auto-loading
* Fixed code style, typos, potential php errors (undefined variable etc)
* Added GuzzleHttp to handle connections
* Removed authentication, this is much better done with dedicated OAuth libraries (such as yii2-authclient or Zend_Oauth)

### v0.3.6 (2014-05-02)

* Use Symfony 2's YAML component instead of Symfony 1's
* Constraint extension dependencies via Composer

### v0.3.5 (2013-05-13)

* Removed unneeded include

### v0.3.4 (2013-05-13)

* Updated composer.json file, posted to [packagist.org](https://packagist.org/)
* Added autoload statement for runkeeperAPI.class.php

### v0.3.3 (2013-05-13)

* Updated README
* Install sfYaml via Composer

### v0.3.2 (2013-03-05)

* Adds support for Records

### v0.3.1 (2012-04-13)

* fixed bug (missing "}")

### v0.3 (2012-04-02)

* fixed bug with cURL on some server which had "error :SSL certificate problem, verify that the CA cert is OK"

### v0.2 (2012-03-19)

* added support for "Delete" requests in "doRunkeeperRequest" method
* added Interfaces in API config

### v.01 (2012-03-03)

* Initial version
