<?php
    require_once "../admin.php";
    
    header('Content-Type: text/json');
    
    $reminderlist = new reminderlist;
    $coursecode = $_GET['coursecode'];
    $title = $_GET['remindertitle'];
    $contentA = $_GET['contentA']; // LID A ?
    $contentB = $_GET['contentB']; // LID B ?
    
    if (!$reminderlist->shift_roworder($coursecode,$title,$contentA,$contentB)){
            die('Fail shift');
    }
    else{
        die('Sucess shift');
    }
    
?>