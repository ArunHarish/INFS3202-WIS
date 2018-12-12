<?php
    require_once "../admin.php";
    
    
    header('Content-Type: text/json');
    
    $task = new task;
    $coursecode = $_POST['coursecode'];
    $taskname = $_POST['taskname'];
    $description = $_POST['description'];
    $type = $_POST['tasktype'];
    $duedate = $_POST['date'];
    if (!$task->check_task($taskname,$coursecode)){
        die('duplicate taskname');
    }
    else{
        if (!$task->create_task($coursecode,$taskname,$description,$type,$duedate)){
            die('Fail Create');
        }
        else{
            die ('Task Created');
        }
    }
?>