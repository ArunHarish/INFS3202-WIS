<?php
    require_once "../admin.php";
    
    header('Content-Type: text/json');
    
    $reminderlist = new reminderlist;
    $coursecode = $_POST['coursecode']
    $title = $_POST['remindertitle'];
    $content = $_POST['content'];
    
    $roworder = current_reminderlist_order($coursecode,$remindertitle,$content);
        
    if (!$reminderlist->delete_reminderlist($coursecode,$title,$content)){
        die ('reminderlist not deleted');   
    }
    else{
        if(!change_roworder_delete($coursecode,$title,$roworder)){
            die('Roworder not changed after delete reminderlist');
        }
        else{
            die ('Roworder changed and reminderlist deleted');
        }
    }
    
?>