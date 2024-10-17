<?php

//指定した日時だけ取得・マッチングするバージョン
session_start();
require("connect_db.php");

$class_name = $_POST["class"];
$type_name = $_POST["type"];

if($class_name == ""){
    $sql = "SELECT type_id FROM types WHERE type='$type_name' ";

    $result = $mysqli->query($sql);
	if(!$n_result){
		echo "error";
	}

}else{
    $sql = "SELECT type_id FROM types WHERE class = '".$class_name."' AND type = '".$type_name."' ";

    $result = $mysqli->query($sql);
	if(!$n_result){
		echo "error";
	}

}

?>