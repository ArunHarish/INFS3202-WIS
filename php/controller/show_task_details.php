<?php
    require_once "../admin.php";
    
    header('Content-Type: text/json');
    
    $task = new task;
    $coursecode = $_POST['coursecode'];//'INS3202';
    $multiple = $_POST['multiple'];
    
    $result = array(); 
    $content = json_decode(
        strip_tags(
            stripslashes($coursecode)
        )
    );

    foreach($content as $key => $value) {
        //array
        $taskname = $task->show_task($value);
        $jsonObject = new stdClass;
        if($taskname != false) {
            
            $jsonObject->coursecode=$value;
            $jsonObject->taskname=$taskname;
            array_push($result, $jsonObject);
            
        }
    }
    
    printf(
        json_encode($result)
    );

    
    
?>