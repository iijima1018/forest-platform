<?php
session_start();

require("connect_db.php");

$user_id = $_SESSION['USERID'];      //ユーザID
$map_id = $_SESSION['SHEETID'];    //シートID
$st_time = $_POST["time"]; 

$result = $mysqli->query("SELECT edge_start, edge_end FROM network_edges_activity
          WHERE user_id = '$user_id' AND map_id = '$map_id' AND time > '$st_time'
          ORDER BY time DESC ");

$data = array();
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}
if (empty($data)) {
    echo json_encode(["error" => "not"]);
} else {
    echo json_encode($data);
}

?>
