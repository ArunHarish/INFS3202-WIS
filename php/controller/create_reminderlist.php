<?php
    require_once "../admin.php";
    
    header('Content-Type: text/json');
    
    $reminderlist = new reminderlist;
    $coursecode = $_GET['coursecode'];
    $title = $_GET['remindertitle'];
    $content = $_GET['content'];

    if (!($reminderlist->check_reminderlist($coursecode,$title,$content))){
        die('Duplicate list');
    }
    else{
        $roworder = $reminderlist->count_reminderlist_order($coursecode,$title);
        if (!$reminderlist->create_reminderlist($coursecode,$title,$content,$roworder)){
            die ('reminderlist not created');   
        }
        else{
            die ('reminderlist created');
        }
    }
?>