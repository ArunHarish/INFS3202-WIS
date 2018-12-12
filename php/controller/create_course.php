<?php
    require_once "../admin.php";
    
    header('Content-Type: text/json');
    
    $course = new course;
    
    $status = new stdClass;
    
    $coursecode = $_POST['coursecode'];
    $coursename = $_POST['coursename'];
    
    if (strlen($coursename) > 15){
        $status->status= -2;
    }
    if (!($course->check_coursecode($coursecode))){//coursecode duplicate 
        $status->status= -1;
    }
    else{
        if (!$course->create_course($coursecode,$coursename)){
            //course cannot be created due to error
            $status->status = 0;
        }
        else{
            //course created
            $status -> cid = get_cid($coursecode);
            $status->status = 1;
            $status->name = $coursename;
            $status->coursecode = $coursecode;
        }
    }
    
    die(json_encode($status));
  
?>