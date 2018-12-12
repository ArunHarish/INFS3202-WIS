<?php
    require_once "../admin.php";
    
    header('Content-Type: text/json');
    
    $task = new task;
    $coursecode = $_POST['coursecode'];
    $taskname = $_POST['taskname'];
    $type = $_POST['tasktype'];
    if(!$task->change_tasktype($coursecode,$taskname,$type)){
        die ('Task type not changed');
    }
    else{
        die('task type changed');
    }

?>