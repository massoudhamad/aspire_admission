<?php

$skey = "QN5LFplE9QGx1ZENpSO1QyOqA_ZOZ4A_M3J0eXAiOiJhdCtqd3QifQyJuYmYiOjE2MDgyODA0MDgsImV4TYwODI4NDAwOCwiaXNzIjoiaHR0cDovLzEwLjEwLjEwMC42MDo1MDAwIiwiYXVkIjoiT01SUyIsImNsaWVudF9p1GSeIOcKvnzq9LXD17BYwdUvDrWj0GlSKDujp4UVkWE3175uHgzwW9ipMdqzP2m6_";

$keyId = "P2fYboJIwOlial0J7LDy6r8z9soQMiEZp1BSlj7lial0J7LDy6r8z9soQMiEZp1BSlj7FV9TNzmAVKgtdKBY_8y9OqoddJWVWIt6K7H2k2aUR6ZMBMc7CPdbBcPQP2fYboJIwOlial0J7LDy6r8z9soQMiEZp1BSlj7FV9TNzmAVKgtdKBY_8y9OqoddJWVWIt6K7H2k2aUR6ZMBMc7CPdbBcPQNzmAVKgtdKBY_";

/*Security*/
define('SECRETE_KEY', $skey);
define('KEY_ID', $keyId);

/*Error Codes and Message*/
define('REQUEST_METHOD_NOT_VALID', 'Error');
define('REQUEST_METHOD_NOT_VALID_MESSAGE', "Request Method is not valid.");

define('REQUEST_URL_NOT_VALID', 'Error');
define('REQUEST_URL_NOT_VALID_MESSAGE', "Request URL is not valid.");

define('REQUEST_CONTENTTYPE_NOT_VALID',    'Error');
define('REQUEST_CONTENTTYPE_NOT_VALID_MESSAGE',    "Request content type is not application/json");

define('AUTH_KEY_NOT_VALID', 'Error');
define('AUTH_KEY_NOT_VALID_MESSAGE', "Authentification key is not valid");

define('INVALID_PARAMETERS_REQUEST', 'Error');
define('INVALID_PARAMETERS_REQUEST_MESSAGE', "Invalid parameters request");

define('INVALID_PARAMETERS_TYPE', 'Error');
define('INVALID_PARAMETERS_TYPE_MESSAGE', "Invalid parameters data type");

define('MISSING_PARAMETERS_REQUEST', 'Error');
define('MISSING_PARAMETERS_REQUEST_MESSAGE', "Missing parameters request");

define('REQUIRED_USER_PASS', 'Error');
define('REQUIRED_USER_PASS_MESSAGE', "Missing one or more parameters values");

define('INVALID_USER_PASS', 'Error');
define('INVALID_USER_PASS_MESSAGE', "Invalid credentials");



define('DASHBOARD_NO_DATA', 106);
define('DASHBOARD_NO_DATA_MESSAGE', "There is no data for Dashboard");

define('NO_DATA_FOUND', 107);
define('NO_DATA_FOUND_MESSAGE', "No data found");

define('DATA_SUCCESS', 'Success');
define('DATA_SUCCESS_MESSAGE', "Data received successfully");


/*Server Errors*/
define('JWT_PROCESSING_ERROR', 'Error');

define('ATHORIZATION_HEADER_NOT_FOUND',    301);
define('ATHORIZATION_HEADER_NOT_FOUND_MESSAGE',    'Access Token Not found');

define('ACCESS_TOKEN_ERRORS', 'Error');


 //*Server Fetch Response*/
 // define('NO_DATA_FOUND',	222);
// define('DATA_FOUND',	 444);	


// Filename of log to use when none is given to write_log
//define("DEFAULT_LOG","default.log");