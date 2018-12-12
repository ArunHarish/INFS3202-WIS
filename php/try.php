<?php
    require_once 'admin.php';
    $email = get_email();
    $hash = get_hash($email);
    //echo $email;
    
    header("Location: https://infs3202-studenttm-arunharish.c9users.io/INFS3202/php/verify.php?email=$email&hash=$hash");
?>