<?php

session_start();
require("connect_db.php");


$version_id = $_POST['version_id'];	//バージョンID


if (!empty($_POST['content'])) {

        $content = $_POST['content'];

        $sql = "UPDATE versions SET paper_content = '$content' WHERE version_id = '$version_id'";

        echo $sql;

        $result = $mysqli->query($sql);
    
} else{
        $user_id = $_SESSION['USERID'];      	//ユーザID
        $user_name = $_SESSION['USERNAME'];	//ユーザ名
        $sheet_id = $_SESSION['SHEETID'];    	//シートID
        $paper_title = $_POST['paper_title'];   //論文タイトル
        $timestamp = date("Y-m-d H:i:s") . "." . substr(explode(".", (microtime(true) . ""))[1], 0, 3);

        $sql = "INSERT INTO versions (version_id, user_id, user_name, sheet_id, paper_title, created_at) VALUES ('$version_id', '$user_id', '$user_name', '$sheet_id', '$paper_title', '$timestamp')";

        $result = $mysqli->query($sql);

        $sql = "INSERT INTO version_nodes (version_id, node_id, user_id, created_at, type, concept_id, content, x, y, sheet_id, parent_id) 
                SELECT '$version_id', id, '$user_id', created_at, type, concept_id, content, x, y, '$sheet_id', parent_id
                FROM nodes WHERE sheet_id='$sheet_id' AND deleted = 0";


        $result = $mysqli->query($sql);

        $sql = "INSERT INTO version_chapter (version_id, chapter_id, user_id, sheet_id, title, rank) 
                SELECT '$version_id', chapter_id, '$user_id', '$sheet_id', title, rank
                FROM chapter WHERE sheet_id='$sheet_id' AND deleted = 0";
        

        $result = $mysqli->query($sql);


        $sql = "INSERT INTO version_section (version_id, section_id, user_id, sheet_id, chapter_id, title, rank) 
                SELECT '$version_id', section_id, '$user_id', '$sheet_id', chapter_id, title, rank
                FROM section WHERE sheet_id='$sheet_id' AND deleted = 0";
        $result = $mysqli->query($sql);


        $sql = "INSERT INTO version_paragraph (version_id, paragraph_id, user_id, sheet_id, section_id, title, rank, content) 
                SELECT '$version_id', paragraph_id, '$user_id', '$sheet_id', section_id, title, rank, content
                FROM paragraph WHERE sheet_id='$sheet_id' AND deleted = 0";

        $result = $mysqli->query($sql);


        $sql = "INSERT INTO version_content_rank (version_id, content_id, node_id, concept_id, rank, content, paragraph_id, type, indent, user_id, sheet_id) 
                SELECT '$version_id', content_id, node_id, concept_id, rank, content, slide_id, type, indent, '$user_id', '$sheet_id'
                FROM slide_content_rank WHERE sheet_id='$sheet_id' AND deleted = 0";

        $result = $mysqli->query($sql);


        $sql = "INSERT INTO version_feedback (version_id, feedback_id, user_id, sheet_id, node_id, node_concept, content, feedback_status) 
                SELECT '$version_id', feedback_id, '$user_id', '$sheet_id', node_id, node_concept, content, feedback_status
                FROM feedback WHERE sheet_id='$sheet_id'";

        $result = $mysqli->query($sql);
}



?>