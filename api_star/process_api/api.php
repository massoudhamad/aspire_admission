<?php

/**
 * // namespace APIProcess;
 */
class Api extends Rest
{

	public function __construct()
	{
		parent::__construct();
	}




	public function validateRoute()
	{

		$contentTypeArray = array('application/json', 'application/json; charset=utf-8', 'application/json; charset=UTF-8', 'application/x-www-form-urlencoded');
		$contentType = $_SERVER['CONTENT_TYPE'];
		$contentInArray = in_array($contentType, $contentTypeArray);

		if (!$contentInArray) {
			$this->throwError(REQUEST_CONTENTTYPE_NOT_VALID, REQUEST_CONTENTTYPE_NOT_VALID_MESSAGE);
		}


		$request_uri = explode('/', trim($_SERVER['REQUEST_URI'], '/'));

		//array_slice(array, start, length, preserve);
		$request_uri = array_slice($request_uri, -3, 3);

		$this->$serviceName = $request_uri[2];


		switch ($this->$serviceName) {

			case 'auth':
				require './v1/authentication.php';
				break;

			case 'get_progs_n_intakes':
				require './v1/get_list_programs_and_admission_intakes_endpoint.php';
				break;

			case 'get_single_registered':
				require './v1/get_single_registered_student_endpoint.php';
				break;
			case 'get_registered':
				require './v1/get_list_registered_students_endpoint.php';
				break;

			case 'get_progs':
				require './v1/get_list_programs_endpoint.php';
				break;

			case 'get_prog_major':
				require './v1/get_list_program_major_endpoint.php';
				break;

			case 'get_intakes':
				require './v1/get_list_admission_intakes_endpoint.php';
				break;

			default:
				$this->throwError(REQUEST_URL_NOT_VALID, REQUEST_URL_NOT_VALID_MESSAGE);
				break;
		}
	}
}