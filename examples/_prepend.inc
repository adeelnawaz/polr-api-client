<?php

/*
 * Pass your Polr API URL, key, and quota as environment variables to the script
 */

$apiUrl = getenv('POLR_API_URL') ?: 'http://polr-api.test';
$apiKey = getenv('POLR_API_KEY') ?: 'polr-api-key-test';
$apiQuota = getenv('POLR_API_QUOTA') ?: 60;

use Adeelnawaz\PolrApiClient\Deserializer\PolrNormalizer;
use Adeelnawaz\PolrApiClient\PolrApi;

error_reporting(E_ALL);

// Register composer spl autoload
$autoloader = require __DIR__ . '/../vendor/autoload.php';

// Instantiate PolrApi object
$api = new PolrApi(
    $apiUrl,
    $apiKey,
    $apiQuota
);