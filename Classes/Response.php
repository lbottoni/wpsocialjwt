<?php
/**
 * User: luca.bottoni
 * Date: 28/03/2018
 * Time: 15:38
 * To change this template use File | Settings | File Templates.
 */

namespace WPSOCIALJWT\Classes;


class Response
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
			$resp=array();
			$resp["success"]=($type==="success");
			$resp["message"]=(empty($message))?"":$message;
			//$data= (self::$toJson)?json_encode($resp):$resp;
			return $resp;
		}
	public static function success($message = null)
		{
			$data=Response::_message("success", $message);
			return  new \WP_REST_Response($data,200);
		}
	public static function error($message = null,$code=400)
		{
			$data=Response::_message("error", $message);
			return new \WP_REST_Response($data,$code);
		}
	}