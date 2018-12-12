<?php
    require_once "admin.php";
    
	header('Content-Type: text/json');
	
    $username = @$_POST['uname'];
    $email = @$_POST['email'];
    $password  = @$_POST['pwd'];
    $hash = encrypt_password(rand(0,1000));
    if (isset($username) && isset($password) && isset($email)){
        if (!(check_user($username))) { // duplicate username
            die('Username has been used');
        }
        else{
            if (!(check_email($email))){ // duplicate email
                //Death
                die('duplicate email found');
            }
            else{        
                $success = create_user($username, $password, $email, $hash);
            }
        }
    }
    if ($success == true){
        /*if (!activate_email($email,$username,$password,$hash)){
            die('Fail to sent activation email.');
        }
        die('please activate email');*/
        header("Location: /INFS3202/login.php?");
    }
?>

