<?php
    require_once "../admin.php";
    
    header('Content-Type: text/json');
    
    $reminder = new reminder;
    $coursecode = $_POST['coursecode']
    $title = $_POST['remindertitle'];
    $content = $_POST['content'];
    $newcontent = $_POST['newcontent'];
    if (!($reminderlist->check_reminderlist($coursecode,$title,$content))){
        die('Duplicate list');
    }
    else{
        if (!$reminder->change_reminderlist($coursecode, $title, $content, $newcontent)){
            die ('Reminderlist not changed.');
        }
        else{
            die ('Reminderlist changed');
        }
    }
?>