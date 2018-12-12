<?php
    require_once 'config.php';
    
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

    
    function activate_email($email,$username,$hash){
        $subject = "User account activation email";
        $to= $email; // Send email to our user
        $message = "
        Thanks for signing up!
        Your account has been created, you can login with the following credentials after you have activated your account by pressing the url below.
         
        ------------------------
        Username: '.$username.'
        ------------------------
         
        Please click this link to activate your account:
         https://http://13.54.15.7/INFS3202/php//verify.php?email=$email&hash=$hash'
         
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

    $username = $_POST['username'];
    $email = $_POST['email'];

    $result = check_email($email);
    
    if($result == false){
        $hash = get_hash($email);
        if (activate_email($email,$username,$hash)){
            echo "<script>alert('Activate email has sent to your email'); window.location.href='register.php';</script>";
        }
    }
?>
