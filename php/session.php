<?php
    session_start();
    
    class sessionStatus {
        
        function getStatus() {
            session_start();
            $usersession = @$_SESSION["auth"];
            $username = @$_SESSION["username"];
            if(isset($usersession) && isset($username)) {
               // echo "1";
                return true;
            }
            //echo "0";
            return false;
        }
        function printStatus() {
            $printstatus = sessionStatus::getStatus();
            $stringstatus = $printstatus ? "true" : "false";
            
            printf("{
                \"sessionActive\" : $stringstatus
            }");
        }
    }
?>