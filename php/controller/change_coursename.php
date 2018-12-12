<?php
    require_once "../admin.php";

    header('Content-Type: text/json');
    
    $course = new course;
    
    $coursecode = $_POST['coursename'];
    $newcoursename = $_POST['newcoursename'];
    
    if ($course->change_coursename($coursecode,$newcoursename)){
        die ('Course name changed');
    }
    else{
        die('Course name not changed');
    }
?>