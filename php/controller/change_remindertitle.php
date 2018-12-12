<?php
    require_once "../admin.php";
    
    header('Content-Type: text/json');
    
    $reminder = new reminder;
    $coursecode = $_POST['coursecode'];
    $title = $_POST['title'];
    $newtitle = $_POST['newtitle'];

    if (!($reminder->check_reminder($coursecode, $newtitle))){//coursecode duplicate 
        die('Duplicate reminder');
    }
    else{
        if (!$reminder->change_remindertitle($coursecode,$title,$newtitle)){
            die ('reminder title not changed');   
        }
        else{
            die ('Reminder title changed');
        }
    }
?>