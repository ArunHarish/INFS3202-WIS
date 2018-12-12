<?php
    require_once "../admin.php";
    
    
    header('Content-Type: text/json');
    
    $reminder = new reminder;
    $coursecode = $_POST['coursecode'];
    $title = $_POST['title'];
    $location = $_POST['location'];

    if (!$reminder->change_location($coursecode,$title, $location)){
        die ('location not changed');   
    }
    else{
        if (!$reminder->add_coordinate($coursecode,$title,$location,false)){
            die('Location deleted but coordinate not deleted.');
        }
        else{
            die ('Both location and coordinate deleted.');
        }
    }    
?>