<?php
error_reporting(E_ALL);
set_time_limit(0);
require_once "database.php";
$count = 0;
$dataset = $db->from('dataset')
  ->limit(0,300000)->all();


foreach($dataset as $data){
	if(count($db->from('incidents')
  ->where('idincidentnum',$data["incident_number"])->where('reportkey',$data["report_key"])->all()) < 1){

	$data = array_map('trim',$data);
	$data = array_map(function($value) { return str_replace("'", "\'", $value); }, $data);
	$set = array();
	$set['idincidentnum'] = $data["incident_number"];
	$timestap = strtotime($data["date"]);
	$set['date'] = date('Y-m-d',$timestap);
	if(strlen($data["time"]) > 3){
	$set['time'] = $data["time"];
	}
	$railroad_code = $data["﻿railroad_code"];
	$railroad_name = $data['railroad_name'];

	
	$railroads = $db->from('railroad')->where('name',$railroad_name)->all();
	#If railroad_code exists in railroad table, just assing. Otherway insert code and name to railroad table.
	if(count($railroads) > 0){
		$set['railroad_code'] = $data["﻿railroad_code"];
	}
	else{
		
	   $insert = $db->query("INSERT INTO railroad (code,name) VALUES ('$railroad_code','$railroad_name')");
	   $set['railroad_code'] = $railroad_code;
	}
	##
	$set['nearest_station'] = $data["nearest_station"];
	$set['division'] = $data["division"];
	$set['subdivision'] = $data["subdivision"];
	$set['highway_name'] = $data["highway_name"];
	$set['is_public'] = $data["public_private_code"];
	#If highway_user_code exists in highway_users table, just assing. Otherway insert code and name to highway_users table.
	if(strlen($data["highway_user_code"]) > 0){
	$highway_users = $db->from('highway_users')->where('code',$data["highway_user_code"])->all();
	if(count($highway_users) > 0){
		$set['highway_user_code'] = $data["highway_user_code"];
	}
	else{
		$uc = $data["highway_user_code"];
		$us = $data["highway_user"];
		$insert = $db->query("INSERT INTO highway_users (code,name) VALUES ('$uc','$us')");
	
	   $set['highway_user_code'] = $data["highway_user_code"];
	}
	}
	
	
	
	##
	#If highway_user_position_code exists in highway_positions table, just assing. Otherway insert code and name to highway_positions table.
	if($data["highway_user_position_code"]!=="" && strlen(@$data["highway_user_position_code"]) > 0 && isset($data["highway_user_position_code"])){
	$highway_positions = $db->from('highway_positions')->where('code',$data["highway_user_position_code"])->all();

	if(count($highway_positions) > 0){
		
		$set['highway_positions_code'] = $data["highway_user_position_code"];
	}
	else{
		$uc = $data["highway_user_position_code"];
		$us = $data["highway_user_position"];
		$insert = $db->query("INSERT INTO highway_positions (code,name) VALUES ('$uc','$us')");
	
	   $set['highway_positions_code'] = $data["highway_user_position_code"];
	}
	}

	##
	if(strlen($data["state_code"]) > 0){
	#State.
	$state = $db->from('state')->where('code',$data["state_code"])->all();

	if(count($state) > 0){
		
		$state_code = $data["state_code"];
	}
	else{
		$uc = $data["state_code"];
		$us = $data["state_name"];
		$insert = $db->query("INSERT INTO state (code,name) VALUES ('$uc','$us')");
		$state_code = $data["state_code"];
	}
	
	#County.
	if($data["county_name"]!=="" && strlen(@$data["county_name"]) > 5 && isset($data["county_name"])){
		
		$data["county_name"] = $data["county_name"];
		
	}
	else{
		$data["county_name"] = $data["state_name"];
	}
		
	$county = $db->from('county')->where('name',$data["county_name"])->where('state_code',$state_code)->all();
	
	if(count($county) > 0){
		
		$set['county_name'] = $data["county_name"];
	}
	else{
		$uc = $data["county_code"];
		$us = $data["county_name"];
		if(strlen($uc) < 1){
			$insert = $db->query("INSERT INTO county (name,state_code) VALUES ('$us','$state_code')");
		}
		else{
		$insert = $db->query("INSERT INTO county (code,name,state_code) VALUES ('$uc','$us','$state_code')");
		}
		$set['county_name'] = $data["county_name"];
	}
		$set['state_code'] = $state_code;
	
	
	}
	#City
	
	
	
	

	##
	
	$set['user_gender'] = $data["user_gender"];
	print_r($data);

	#Driver Condition.
	if($data["driver_condition_code"]!=="" && strlen(@$data["driver_condition_code"]) > 0 && isset($data["driver_condition_code"])){
	$driver_condition = $db->from('driverCondition')->where('id',$data["driver_condition_code"])->all();
	if(count($driver_condition) > 0){
		
		$set['driverCondition_id'] = $data["driver_condition_code"];
	}
	else{
		$uc = $data["driver_condition_code"];
		$us = $data["driver_condition"];
		$insert = $db->query("INSERT INTO driverCondition (id,name) VALUES ('$uc','$us')");
	
	   $set['driverCondition_id'] = $data["driver_condition_code"];
	}
	}
	##
	
	$set['narrative'] = $data['narrative'];
	$set['reportkey'] = $data["report_key"];
	#Visibility.
	if($data["visibility"]!=="" && strlen(@$data["visibility"]) > 0 && isset($data["visibility"])){
	$visibility = $db->from('visibility')->where('name',$data["visibility"])->all();
	if(count($visibility) > 0){
		
		$set['visibility_id'] = $data["visibility_code"];
	}
	else{
		$uc = $data["visibility_code"];
		$us = $data["visibility"];
		$insert = $db->query("INSERT INTO visibility (id,name) VALUES ('$uc','$us')");
	
	   $set['visibility_id'] = $data["visibility_code"];
	}
	}
	##
	
	#Weather Condition.
	if($data["weather_condition_code"]!=="" && strlen(@$data["weather_condition_code"]) > 0 && isset($data["weather_condition_code"])){
	$weather = $db->from('weather_condition')->where('id',$data["weather_condition_code"])->all();
	if(count($weather) > 0){
		
		$set['weather_condition_id'] = $data["weather_condition_code"];
	}
	else{
		$uc = $data["weather_condition_code"];
		$us = $data["weather_condition"];
		$insert = $db->query("INSERT INTO weather_condition (id,name) VALUES ('$uc','$us')");
	
	    $set['weather_condition_id'] = $data["weather_condition_code"];
	}
	}
	##
	$key = array();
	$value = array();
	$set = $set;
	foreach($set as $k => $v ){
		$key[] = $k;
		$value[] = $v;
	}
	$keys = implode(',',$key);
	$values = "'".implode("','", $value)."'" ;
	
	
	
	$insert = $db->query("INSERT INTO incidents ($keys) VALUES ($values)");
	
	
	## Temperature
	if(strlen($data["temperature"]) > 1){
	$temperature = $db->from('incidentTypeName')->where('name',"temperature")->all();
	if(count($temperature)  > 0){
		$type_id = $temperature[0]["id"];
	}
	else{
		$type_id = $db->query("INSERT INTO incidentTypeName (name) VALUES ('temperature')");
		
	}
	$temperature = $db->from('incidentTypeName')->where('name',"temperature")->first();
	$type_id = $temperature["id"];
	$id = $data["incident_number"];
	$in = $data["report_key"];
	$tmp = intval($data["temperature"]);
	$insert = $db->query("INSERT INTO incident2value (idincidentnum,reportkey,typeid,value) VALUES ('$id','$in','$type_id','$tmp')");
	}
	##Number Loco
	if(strlen($data["number_of_locomotive_units"]) > 1){
	$number_loco = $db->from('incidentTypeName')->where('name',"number_loco")->all();
	if(count($number_loco)  > 0){
		$type_id = $number_loco[0]["id"];
	}
	else{
		$type_id = $db->query("INSERT INTO incidentTypeName (name) VALUES ('number_loco')");
		
	}
	$number_loco = $db->from('incidentTypeName')->where('name',"number_loco")->first();
	$type_id = $number_loco["id"];
	$id = $data["incident_number"];
	$in = $data["report_key"];
	$tmp = intval($data["number_of_locomotive_units"]);
	$insert = $db->query("INSERT INTO incident2value (idincidentnum,reportkey,typeid,value) VALUES ('$id','$in','$type_id','$tmp')");
	}
	#Number Car
	if(strlen($data["number_of_cars"]) > 1){
	$number_car = $db->from('incidentTypeName')->where('name',"number_car")->all();
	if(count($number_car)  > 0){
		$type_id = $number_car[0]["id"];
	}
	else{
		$type_id = $db->query("INSERT INTO incidentTypeName (name) VALUES ('number_car')");
		
	}
	$number_car = $db->from('incidentTypeName')->where('name',"number_car")->first();
	$type_id = $number_car["id"];
	$id = $data["incident_number"];
	$in = $data["report_key"];
	$tmp = intval($data["number_of_cars"]);
	$insert = $db->query("INSERT INTO incident2value (idincidentnum,reportkey,typeid,value) VALUES ('$id','$in','$type_id','$tmp')");
}
	
	##Train Speed
	if(strlen($data["train_speed"]) > 1){
	$train_speed = $db->from('incidentTypeName')->where('name',"train_speed")->all();
	if(count($train_speed)  > 0){
		$type_id = $train_speed[0]["id"];
	}
	else{
		$type_id = $db->query("INSERT INTO incidentTypeName (name) VALUES ('train_speed')");
		
	}
	$train_speed = $db->from('incidentTypeName')->where('name',"train_speed")->first();
	$type_id = $train_speed["id"];
	$id = $data["incident_number"];
	$in = $data["report_key"];
	$tmp = intval($data["train_speed"]);
	$insert = $db->query("INSERT INTO incident2value (idincidentnum,reportkey,typeid,value) VALUES ('$id','$in','$type_id','$tmp')");
	}
	##User Age
	if(strlen($data["user_age"]) > 1){
	$user_age = $db->from('incidentTypeName')->where('name',"user_age")->all();
	if(count($user_age)  > 0){
		$type_id = $user_age[0]["id"];
	}
	else{
		$type_id = $db->query("INSERT INTO incidentTypeName (name) VALUES ('user_age')");
		
	}
	$user_age = $db->from('incidentTypeName')->where('name',"user_age")->first();
	$type_id = $user_age["id"];
	$id = $data["incident_number"];
	$in = $data["report_key"];
	$tmp = intval($data["user_age"]);
	$insert = $db->query("INSERT INTO incident2value (idincidentnum,reportkey,typeid,value) VALUES ('$id','$in','$type_id','$tmp')");
	}
	
	##Killed
	if(strlen($data["total_killed_form_57"]) > 1){
	$killed = $db->from('incidentTypeName')->where('name',"killed")->all();
	if(count($killed)  > 0){
		$type_id = $killed[0]["id"];
	}
	else{
		$type_id = $db->query("INSERT INTO incidentTypeName (name) VALUES ('killed')");
		
	}
	$killed = $db->from('incidentTypeName')->where('name',"killed")->first();
	$type_id = $killed["id"];
	$id = $data["incident_number"];
	$in = $data["report_key"];
	$tmp = intval($data["total_killed_form_57"]);
	$insert = $db->query("INSERT INTO incident2value (idincidentnum,reportkey,typeid,value) VALUES ('$id','$in','$type_id','$tmp')");
	}
	
	##Damage Cost
	if(strlen($data["vehicle_damage_cost"]) > 0){
	$damage = $db->from('incidentTypeName')->where('name',"damage_cost")->all();
	if(count($damage)  > 0){
		$type_id = $damage[0]["id"];
	}
	else{
		$type_id = $db->query("INSERT INTO incidentTypeName (name) VALUES ('damage_cost')");
		
	}
	$damage = $db->from('incidentTypeName')->where('name',"damage_cost")->first();
	$type_id = $damage["id"];
	$id = $data["incident_number"];
	$in = $data["report_key"];
	$tmp = intval($data["vehicle_damage_cost"]);
	$insert = $db->query("INSERT INTO incident2value (idincidentnum,reportkey,typeid,value) VALUES ('$id','$in','$type_id','$tmp')");
	}
	
	##Injured
	if(strlen($data["total_injured_form_57"]) > 1){
	$injured = $db->from('incidentTypeName')->where('name',"injured")->all();
	if(count($injured)  > 0){
		$type_id = $injured[0]["id"];
	}
	else{
		$type_id = $db->query("INSERT INTO incidentTypeName (name) VALUES ('injured')");
		
	}
	$injured = $db->from('incidentTypeName')->where('name',"injured")->first();
	$type_id = $injured["id"];
	$id = $data["incident_number"];
	$in = $data["report_key"];
	$tmp = intval($data["total_injured_form_57"]);
	$insert = $db->query("INSERT INTO incident2value (idincidentnum,reportkey,typeid,value) VALUES ('$id','$in','$type_id','$tmp')");
	}
	
	
	
	$count++;
  }
}

echo $count;


?>