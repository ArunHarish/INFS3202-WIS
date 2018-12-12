<?php
    require_once "admin.php";
    
    header('Content-Type: text/json');
    
    $reminderlist = new reminderlist;
    $coursecode = 'INFS3202';//$_POST['coursecode'];
    $title = array("Final Report", "Exam");
    
    $result = array();
    foreach($title as $content){
        $list = $reminderlist->show_reminderlist($coursecode,$content);
        array_push($result,$list);
    }
    
    printf(json_encode($result));
    
?>