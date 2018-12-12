<?php
    require_once "../admin.php";
    
    header('Content-Type: text/json');
    
    $task = new task;
    $coursecode = $_POST['coursecode'];
    $taskname = $_POST['taskname'];
    $done = !$task->get_boolean($coursecode,$taskname);
    if(!$task->task_done($coursecode,$taskname,$done)){
        die ('Task not done');
    }
    else{
        die ('Task done');
    }
?>