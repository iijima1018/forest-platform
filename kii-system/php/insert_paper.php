<?php

	session_start();

	/*ノード情報をDBに格納する際に使用*/
	require("connect_db.php");
	date_default_timezone_set('Asia/Tokyo');
	$timestamp = date("Y-m-d H:i:s") . "." . substr(explode(".", (microtime(true) . ""))[1], 0, 3);
	$paper_id = rand(); //nishida
	

	// $paper_content = $_POST["paper_content"]; //nishida
	$_SESSION["paper_content"] = $_POST["paper_content"]; //nishida

	  
// 	$sql = "INSERT INTO papers (id,paper_content,created_at)


?>

