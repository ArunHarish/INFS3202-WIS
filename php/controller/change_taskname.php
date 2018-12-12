<?php
    require_once "../admin.php";
    
    header('Content-Type: text/json');
    
    $task = new task;
    $coursecode = $_POST['coursecode'];
    $taskname = $_POST['taskname'];
    $newtaskname = $_POST['newtaskname'];
    if (!$task->check_task($newtaskname,$coursecode)){
        die('Duplicate taskname');
    }
    else{
        if(!$task->change_taskname($coursecode,$taskname,$newtaskname)){
            die('Taskname not changed');
        }
        else{
            die('Taskname changed');
        }
    }
?>