<?php

//指定した日時だけ取得・マッチングするバージョン
session_start();
require("connect_db.php");

// s$user_id = $_SESSION["USERID"];//"26943"; //
$map_id = $_SESSION["MAPID"];//"102774749"; //

//タイムゾーンの設定
date_default_timezone_set('Asia/Tokyo');
$today_date = date("Y-m-d");


$sql = "SELECT scenario_title FROM map_mode_link WHERE map_id='$map_id' AND mode_id = 1";

if($result = $mysqli->query($sql)){

  //$reflections
  while($row = mysqli_fetch_assoc($result)){
    $reflections[] = array('scenario_title'=> $row["scenario_title"]);
  }
}

echo json_encode($reflections);

?>
