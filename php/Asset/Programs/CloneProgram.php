<?php
/*
   CloneProgram.php

   Marketo REST API Sample Code
   Copyright (C) 2016 Marketo, Inc.

   This software may be modified and distributed under the terms
   of the MIT license.  See the LICENSE file for details.
*/
$program = new CloneProgram();
$program->id = 1142;
$program->folder = new stdClass();
$program->folder->id = 5562;
$program->folder->type = "Folder";
$program->name = "Clone Program PHP";
$program->description = "Cloned with PHP";

print_r($program->postData());

class CloneProgram{
	private $host = "CHANGE ME";
	private $clientId = "CHANGE ME";
	private $clientSecret = "CHANGE ME";

	public $folder;//parent folder, folders object with id and type, type must be folder, target folder must be in same workspace as program being cloned
	public $name;//name of resulting program
	public $description;//description of resulting program
	
	public function postData(){
		$url = $this->host . "/rest/asset/v1/program/" . $this->id . "/clone.json";
		$ch = curl_init($url);
		$requestBody = $this->bodyBuilder();
		curl_setopt($ch,  CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('accept: application/json', "Authorization: Bearer " . $this->getToken(), "Content-Type: application/json"));
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $requestBody);
		curl_getinfo($ch);
		$response = curl_exec($ch);
		return $response;
	}

	private function getToken(){
		$ch = curl_init($this->host . "/identity/oauth/token?grant_type=client_credentials&client_id=" . $this->clientId . "&client_secret=" . $this->clientSecret);
		curl_setopt($ch,  CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('accept: application/json',));
		$response = json_decode(curl_exec($ch));
		curl_close($ch);
		$token = $response->access_token;
		return $token;
	}
    
	private function bodyBuilder(){
		$jsonFolder = json_encode($this->folder);
		$requestBody = "name=$this->name&folder=$jsonFolder&description=$this->description";
		return $requestBody;
	}
}