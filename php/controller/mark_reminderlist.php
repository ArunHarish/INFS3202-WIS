<?php
    require_once "../admin.php";
    
    header('Content-Type: text/json');
    
    $reminderlist = new reminderlist;

    $coursecode = $_POST['coursecode'];
    $title = $_POST['remindertitle'];
    $content = $_POST['content'];
    $boolean = $_POST['mark'];
    if (!$reminderlist->completed_reminderlist($coursecode, $remindertitlte, $content, $boolean)){
        die('Cant mark the reminderlist');
    }else{
        die('Reminderlist marked');
    }
    
?>