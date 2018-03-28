<?php
/**
 * User: luca.bottoni
 * Date: 28/03/2018
 * Time: 15:38
 * To change this template use File | Settings | File Templates.
 */

namespace WPSOCIALJWT\Classes;


class Response extends \WP_REST_Response
	{
	private $message;
	private $success;
	/**
	 * @var bool indica se message success o errore devono ritornare un json e non una array
	 */
	public $toJson=false;
	//	public function __get($property)
	//		{
	//			if (property_exists($this, $property))
	//				{
	//				return $this->$property;
	//				}
	//		}
	//
	//	public function __set($property, $value)
	//		{
	//			if (property_exists($this, $property))
	//				{
	//				$this->$property = $value;
	//				}
	//
	//			return $this;
	//		}

	private function _message($type, $message = null)
		{
			$resp=new \stdClass();
			$resp->success=($type==="success");
			$resp->messagge=(empty($message))?"":$message;
			$data= ($this->toJson)?json_encode($resp):$resp;
			return $data;
		}

	public static function success($message = null)
		{
			return Response::_message("success", $message);
		}
	public static function error($message = null)
		{
			return Response::_message("error", $message);
		}
	}