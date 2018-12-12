<?php

    require_once 'admin.php';
    if (logout_user()){
        header("Location: /INFS3202/login.php?");
    }
    
?>