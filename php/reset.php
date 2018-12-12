<?php
    require_once "config.php";

    function check_email_hash($email,$hash){
        $link = connect_database();
        $query =  'SELECT email, hash FROM user WHERE email = ? AND hash = ?';
        if ($stmt = mysqli_prepare($link,$query)){
            mysqli_stmt_bind_param($stmt, 'ss', $email, $hash);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            if (mysqli_stmt_num_rows($stmt) == 1){
                mysqli_stmt_close($stmt);
                return true;
            }
        }
        return false;
    }
    
    $email = $_GET['email'];
    $hash = $_GET['hash'];
        
    if (!check_email_hash($email,$hash)){
        echo "<script>alert('Link is not valid'); window.location.href='register.php';</script>";
    }
    else{
        // bring the user to change password html?
        die('Change user password page');
    }
?>