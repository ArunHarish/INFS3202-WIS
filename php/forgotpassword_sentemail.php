<?php
    require_once 'config.php';
    $email = $_POST['email'];
    $username = $_POST['username'];
    $hash = get_hash($email);
    
    function check_username_email($email,$username){
        $link = connect_database();
        $query = 'Select email from user WHERE email = ? AND username = ?';
        if ($stmt = mysqli_prepare($link, $query)){
            mysqli_stmt_bind_param($stmt, 'ss', $email, $username);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            if (mysqli_stmt_num_rows($stmt) < 1){
                mysqli_stmt_close($stmt);
                return true;
            }
        }
        return false;
    }
    
    /* Sent recover password email */
    function recovery_password_email($email,$hash){
        $subject = "Reset password email";
        $to= $email; // Send email to our user
        $message = "
        Your password reset link send to your e-mail address: 
        
        https://http://13.54.15.7/INFS3202/php/reset.php?email=$email&hash=$hash'
         
        "; // Our message above including the link
                             
        $headers = 'From:noreplystudenttm@gmail.com' . "\r\n"; // Set from headers
        if (mail($to, $subject, $message, $headers)){
            return true;
        }
        return false;
    }
    
    function get_hash($email){
        $link = connect_database();
        $query =  'SELECT hash FROM user WHERE email = ?';
        if ($stmt = mysqli_prepare($link,$query)){
            mysqli_stmt_bind_param($stmt, 's', $email);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt,$hash);
            while (mysqli_stmt_fetch($stmt)){
                mysqli_stmt_close($stmt);
                return $hash;
            }
        }
        return false;
    }
    
    if (check_username_email($email,$username)){
        die('User havent create account');
    }
    else{
        if(!recovery_password_email($email,$hash)){
            die('Email not sent');
        }
        else{
            die('Recovery password email sent');
        }    
    }
?>