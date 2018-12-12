<?php
    require_once "../admin.php";
    
    header('Content-Type: text/json');
    
    $course = new course;
    $cid = $_POST['cid'];
    $status = new stdClass;
    
    if (!$course->delete_course($cid)){
        $status->code = 0 ;
    }
    else{
        
        $status->code = 1;       
    }

    die(
        json_encode($status)  
    );

?>