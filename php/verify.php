<?php
    require_once 'config.php';
    
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
    
    function set_active($email,$hash){
        $link = connect_database();
        $query =  'UPDATE user SET active = 1 WHERE email = ? AND hash = ?';
        if ($stmt = mysqli_prepare($link,$query)){
            mysqli_stmt_bind_param($stmt, 'ss', $email, $hash);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
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
    
    $email = $_GET['email'];
    $hash = $_GET['hash'];
    
    if (isset($_GET['email'])){
        if (isset($_GET['hash'])){
            if(check_email_hash($email,$hash)){
                if (set_active($email,$hash)){
                    header("Location: /INFS3202/login.php?");
                }
                else{
                    header("Location: /INFS3202/login.php?");
                }
            }
        }
    }
    else{
        echo "<script>alert('Link not valid'); window.location.href='register.php';</script>";
    }
?>