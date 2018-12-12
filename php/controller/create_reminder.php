<?php
    require_once "../admin.php";
    
    header('Content-Type: text/json');
    
    $reminder = new reminder;
    $cid = $_POST['cid'];
    $title = $_POST['title'];
    $location = $_POST['location'];
    $result = new stdClass;
    
    //Duplication
    if (!($reminder->check_reminder($cid, $title))
        || !$reminder->create_reminder($cid,$title, $location)
    ){
        $result->errorCode = 0;
    }
    else if(!isset($cid) || !isset($title) || strlen(trim($title)) == 0 ||
        strlen(trim($cid)) == 0
    ) {
        $result->errorCode = -1;
    }
    else{
        //gives the rid
        $result->errorCode = 1;
        $result->rid = get_rid($cid, $title);
        $result->reminderName = $title;
        $result->location = array(null, null);
        $result->lid = array();
        $result->cid = cid;
        
    }
    
    die(
        json_encode($result)  
    );
?>