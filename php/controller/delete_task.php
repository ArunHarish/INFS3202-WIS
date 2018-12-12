<?php
    require_once "../admin.php";
    
    
    header('Content-Type: text/json');
    
    $task = new task;
     $coursecode = $_POST['coursecode'];
    $taskname = $_POST['taskname'];
    if ($task->check_task($taskname,$coursecode)){
        die('no taskname found');
    }
    else{
        if(!$task->delete_task($coursecode,$taskname)){
            die ('Task not deleted');
        }
        else{
            die('task deleted');
        }
    }
?>