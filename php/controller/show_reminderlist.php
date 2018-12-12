<?php
    require_once "../admin.php";
    
    header('Content-Type: text/json');
    
    $reminderlist = new reminderlist;
    $rid = $_POST['rid'];
    $multiple = $_POST['multiple'];
    
    if(isset($rid)) {
        //On multiple course reminder requests especially at the beginning.
        if(!isset($multiple) || !$multiple)  {
            $rid = "[\"$rid\"]";
        }
        
        $result = array();
        $content = json_decode(
            strip_tags(
                stripslashes($rid)
            )
        );
        
        foreach($content as $value) {
            $list = $reminderlist->show_reminderlist($value);
            $jsonObject=  new stdClass;
            
            if($list != false) {
                $jsonObject->rid = $value;
                $jsonObject->reminderlist = $list;
                array_push($result, $jsonObject);
            }
            
        }
        
        printf(
            json_encode($result)
        );
    }

    
    
?>