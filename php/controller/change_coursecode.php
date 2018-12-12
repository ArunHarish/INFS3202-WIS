<?php
    require_once "../admin.php";
    
    
    header('Content-Type: text/json');
    
    $course = new course;
    
    $coursecode = $_POST['coursecode'];
    $newcoursecode = $_POST['newcoursecode'];
	
    if (!$course->check_coursecode($newcoursecode)){//check if the new course course is duplicate
        die('Duplicate coursecode');
    }
    else{
        if (!$course->change_coursecode($coursecode,$newcoursecode)){
            die ('Coursecode not changed');
        }
        else{
            die ('Coursecode changed.');
        }
    }
?>