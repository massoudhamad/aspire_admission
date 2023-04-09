<?php

/**
 * // namespace APIProcess;
 */
//require '../vendor/autoload.php';

// WIthout Composer Autolaod
require_once '../jwt_firebase/php-jwt/src/JWT.php';
require_once '../jwt_firebase/php-jwt/src/SignatureInvalidException.php';
require_once '../jwt_firebase/php-jwt/src/BeforeValidException.php';
require_once '../jwt_firebase/php-jwt/src/ExpiredException.php';
require_once '../jwt_firebase/php-jwt/src/JWK.php';


use \Firebase\JWT\JWT;

class Rest
{

	protected $request;
	protected $serviceName;
	protected $vendorName;



	function __construct()
	{

		$request_uri = explode('/', trim($_SERVER['REQUEST_URI'], '/'));

		if (!is_array($request_uri) || empty($request_uri)) {

			$this->throwError(REQUEST_URL_NOT_VALID, REQUEST_URL_NOT_VALID_MESSAGE);
		}

		//array_slice(array, start, length, preserve);
		$request_uri = array_slice($request_uri, -3, 3);

		//if (count($request_uri) <= 1 && count($request_uri) >= 0 || $request_uri[0] != 'api' || $request_uri[1] != 'v1' ) {
		if (count($request_uri) <= 1 && count($request_uri) >= 0 || $request_uri[0] != 'api_star' || $request_uri[1] != 'v1') {
			$this->throwError(REQUEST_URL_NOT_VALID, REQUEST_URL_NOT_VALID_MESSAGE);
		}

		$this->validateRoute();
	}



	//GENERATE  ACCESS TOKEN
	public function generateToken($userId)
	{

		try {
			$paylod = [
				'name' => 'Sumait University',
				'iss' => 'StAR_MOBILE_API',
				'user_id' => $userId,
				'iat' => time(),
				//'exp' => time() + (15*60),//15min expire 150*60
				'exp' => time() + (150 * 60)
			];

			//encode($payload, $key, $alg = 'HS256', $keyId = null, $head = null)//
			$token = JWT::encode($paylod, SECRETE_KEY, 'HS384', KEY_ID);

			//$data = ['token' => $token];
			$this->returnToken($token);
		} catch (Exception $e) {
			$this->throwError(JWT_PROCESSING_ERROR, $e->getMessage());
		}
	}


	//VALIDATE PROVIDED TOKEN
	public function validateToken()
	{

		$userID = $userPayload['user_id'];
		$userName = $userPayload['name'];

		try {

			$token = $this->getBearerToken();

			//decode($jwt, $key, array $allowed_algs = array());
			$payload = JWT::decode($token, SECRETE_KEY, ['HS384']);

			//Object Element
			$name = $payload->name;
			$iss = $payload->iss;
			$user_id = $payload->user_id;
			$iat = $payload->iat;
			$exp = $payload->exp;

			$userPayload = array('user_id' => $user_id, 'name' => $name);

			// $this->throwError(ACCESS_TOKEN_ERRORS, $userPayload);
			// exit();
			return $userPayload;
		} catch (Exception $e) {
			$this->throwError(ACCESS_TOKEN_ERRORS, $e->getMessage());
		}
	}





	/**
	 * get access token from header
	 * */
	public function getBearerToken()
	{
		$headers = $this->getAuthorizationHeader();

		// HEADER: Get the access token from the header
		if (!empty($headers)) {
			if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
				return $matches[1];
			}
		}
		$this->throwError(ATHORIZATION_HEADER_NOT_FOUND, ATHORIZATION_HEADER_NOT_FOUND_MESSAGE);
	}




	/**
	 * Get hearder Authorization
	 * */
	public function getAuthorizationHeader()
	{
		$headers = null;
		if (isset($_SERVER['Authorization'])) {
			$headers = trim($_SERVER["Authorization"]);
		} else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
			$headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
		} elseif (function_exists('apache_request_headers')) {
			$requestHeaders = apache_request_headers();
			// Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
			$requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
			if (isset($requestHeaders['Authorization'])) {
				$headers = trim($requestHeaders['Authorization']);
			}
		}

		return $headers;
	}




	//Throw  Error Message
	public function throwError($code, $message)
	{

		//$this->write_log($_SERVER['CONTENT_TYPE'], $message);

		//include headers
		header("Access-Control-Allow-Origin: *");
		//http_response_code(404);
		//header("content-type: application/json");
		header("Content-type: application/json; charset=utf-8");
		$errorMsg = json_encode([$code => $message]);
		echo $errorMsg;
		exit;
	}



	public function returnResponse($code, $data)
	{

		//$this->write_log($code,$data);

		//include headers
		header("Access-Control-Allow-Origin: *");
		//header("content-type: application/json");
		header("Content-type: application/json; charset=utf-8");
		$response = json_encode([$code => $data]);
		echo $response;
		exit;
	}


	public function returnResponseData($data)
	{

		//$this->write_log($code,$data);

		//include headers
		header("Access-Control-Allow-Origin: *");
		header("Content-type: application/json");
		//header("Content-type: application/json; charset=utf-8");
		$response = json_encode($data);
		echo $response;
		exit;
	}



	public function returnToken($tokenData)
	{
		// Write to default log 
		//$this->write_log(200,"Token Created Successfully");

		//include headers
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Methods: POST");
		header("Content-type: application/json; charset=utf-8");

		$response = json_encode([
			'access_token' => $tokenData, 'token_type' => 'Bearer', 'expires_in' => 3600,
			'scope' => 'OMRS'
		]);
		echo $response;
		exit;
	}


	//Write Log
	public function write_log($code, $message)
	{

		$time = date("d-m-Y H:i:s", time());

		// Get IP address
		if (($remote_addr = $_SERVER['REMOTE_ADDR']) == '') {
			$remote_addr = "UNKNOWN_REMOTE_ADDR";
		}

		if (($service_name = $this->serviceName) == '') {
			$service_name = "UNKNOWN_SERVICE";
		}

		if (($vendor_name = $this->vendorName) == '') {
			$vendor_name = "UNKNOWN_VENDOR";
		}

		//LOG_NAME ,   REQUESTED_DATE ,    REMOTE_ADDR ,   SERVICE_NAME  ,  VENDOR_NAME  , CODE    ,  MESSAGE

		$myfile = fopen("logs/default.log", "a");
		$content = "API LOG: " . $time . " , " . $remote_addr . " , " . $service_name . " , " . $vendor_name . " , " . $code . "  , " . $message . "\n";

		fwrite($myfile, $content);
		fclose($myfile);
		// echo $content; exit;


	}
}