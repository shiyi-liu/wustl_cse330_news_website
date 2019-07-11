<?php
    session_start();
    // assign session values for guests
    $_SESSION['user_id']=0;
    $_SESSION['username']="Suspicious Guest"; 
    $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32)); // generate a 32-byte random string

    // redirect guest to main page
    header('LOCATION: main.php');
?>    