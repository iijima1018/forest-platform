<?php

session_start();

/*ノード情報をDBに格納する際に使用*/
require("connect_db.php");

$user_id = $_SESSION["USERID"];//
$sheet_id = $_SESSION["SHEETID"];//
$timestamp = date("Y-m-d H:i:s") . "." . substr(explode(".", (microtime(true) . ""))[1], 0, 3);


$sql = "SELECT scenario_title FROM sheets WHERE id='$sheet_id'";

if($result = $mysqli->query($sql)) {
  while($row = mysqli_fetch_assoc($result)){
    $pre_title = $row['scenario_title'];
  }
}

$title = $_POST["title"]; //論文タイトル

if($title != $pre_title){	//変更があれば更新

  $sql = "UPDATE sheets SET updated_at='$timestamp', scenario_title='$title' WHERE id='$sheet_id'";
    
  $result = $mysqli->query($sql);

  //php($sql)のエラー処理
  if($sql == TRUE){
    
    error_log('$sql:scenario_title_update成功', 0);
  }else if($sql == FALSE){
    error_log($sql.'$sql:scenario_title_update失敗', 0);
  }else{
    error_log('$sql:scenariotitle_update不明なエラー', 0);
  }

  //php($result)のエラー処理
  if($result == TRUE){
    
    error_log('$result:scenario_title_update成功', 0);
  }else if($result == FALSE){
    error_log($result.'$result:scenario_title_update失敗'.$mysqli->error, 0);
  }else{
    error_log('$result:scenario_title_update不明なエラー', 0);
  }
}

?>