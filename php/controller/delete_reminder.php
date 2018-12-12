<?php
    require_once "../admin.php";
    
    
    header('Content-Type: text/json');
    
    $reminder = new reminder;
    $rid = $_GET['rid'];
    $result = new stdClass;
    
    if (!($reminder->delete_reminder($rid))){
        $result->status = 0;
    }
    else{
        $result->status = 1;
    }
    
    print(json_encode($result));
    
?>