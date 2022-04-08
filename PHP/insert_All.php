<?php
set_time_limit(0);
require_once "database.php";
$file = fopen("data.csv","r");
$count = 0;

$getDataFirst = fgetcsv($file, 10000, ";");

while (($getData = fgetcsv($file, 10000, ";")) !== FALSE)
           {
	$array = array();
	$values = array();
	$i = 0;
	foreach($getDataFirst as $data){
	$data = str_replace(" ","_",$data);
	$data = str_replace("/","_",$data);
	$data = strtolower($data);
	$array[] =  $data;
	$values[] = str_replace("'","\'",str_replace('\\','',$getData[$i]));
	$i++;
	
	}
	$columns = implode(',',$array);
	$Valuess = "'".implode("','", $values)."'" ;
	$sql = "INSERT INTO dataset ($columns) VALUES ($Valuess)";
	$add = $db->query($sql);
	if($add){ 
	 $count++;
	}
           }
   echo $count . " adet veri eklendi.";
?>