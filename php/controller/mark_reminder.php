<?php
  require_once "../admin.php";
    
    header('Content-Type: text/json');
    
    $reminder = new reminder;
    $reminderlist = new reminderlist;

    $rid = $_POST['rid'];
    $boolean = !(get_reminder_status($rid));

    if (!$reminder->mark_reminder($rid,$boolean)){
        die ("Reminder not marked");
    }
    else{
        if (!$reminderlist->mark_all_reminderlist_complete($rid,$boolean)){
            die("Reminderlists not marked");
        }
    }
?>