<?php
    require_once "../admin.php";
    
    header('Content-Type: text/json');
    
    $task = new task;
    $coursecode = $_POST['coursecode'];
    $taskname = $_POST['taskname'];
    $description = $_POST['description'];
    if (!$task->change_taskdescription($coursecode,$taskname,$description)){
        die('description not changed');
    }
    else{
        die ('description changed');
    }
?>