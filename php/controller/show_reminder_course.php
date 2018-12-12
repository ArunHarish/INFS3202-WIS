<?php

    require_once "../admin.php";
    
    header('Content-Type: text/json');
    
    $reminder = new reminder;
    $cid = $_POST['CID'];
    $multiple = $_POST['multiple'];
    
    
    if(isset($cid)) {
        //On multiple course reminder requests especially at the beginning.
        if(!isset($multiple) || !$multiple)  {
            $cid = "[\"$cid\"]";
        }
        
        $result = array();
        $content = json_decode(
            strip_tags(
                stripslashes($cid)
            )
        );
        foreach($content as $key => $value) {
            //array
            
            /*
                Application requires : 
                $jsonObject->CID=CID;
                $jsonObject->reminders=[RID, ReminderTitle];
            */
            $Object = array();
            $courseReminder = $reminder -> show_course_reminder($value);
            $jsonObject = new stdClass;
            if($courseReminder != false) {
                
                foreach($courseReminder as $a){
                    //$location = array();
                    //array_push($location,array($a[2],$a[3]));
                    array_push($Object,
                        array($a[0], $a[1],array($a[2], $a[3]))
                    );
                    
                }
                $jsonObject->CID=$value;
                $jsonObject->reminders=$Object;
                array_push($result, $jsonObject);
            }
        }
        
        printf(
            json_encode($result)
        );
    }

    
    
?>