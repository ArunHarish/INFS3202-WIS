<?php
    require_once '../admin.php';
    
    header('Content-Type: text/json');
    
    $user = new user;
    $newpassword= $_POST['newpassword'];
    $oldpassword = $_POST['oldpassword'];    

    if (!check_password($oldpassword,get_password())){
        
    }else{
        if (!$user->change_password($newpassword)){// indicate password sucessfully changed
        die ('Fail');
    }
    else{
        header("Location:../INFS3202/");
    }
    
?>