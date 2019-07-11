<?php
    if(isset($_POST['btn'])&&!empty($_POST['usrnm'])&&!empty($_POST['password'])){ //check if all fields filled
        if($_POST['password']!=$_POST['confirm']){
            echo('<p class="error"> Passwords do not match. Please try again.</p>' ); //check if two password inputs match
        }
        else{
            session_start(); //start a session for the user

            require 'database.php'; //establish a database connection to execute sql commands
            $password=password_hash($_POST['password'], PASSWORD_DEFAULT);//hash the password input
            $username=$_POST['usrnm'];
            echo($password);
            //filter out invalid string in user input
            //$password=mysql_real_escape_string($password);
            //$username= mysql_real_escape_string($username);
  
            // Use a prepared statement
            $stmt = $mysqli->prepare("insert into users (username, hashed_password) values (?, ?)");

            
            if(!$stmt){
                printf("<p class='error'>Query Prep Failed: %s\n </p>", $mysqli->error);
                exit;
            }

            // Bind the parameter
            $stmt->bind_param('ss', $username, $password);
            $stmt->execute();
            $stmt->close();
            //echo($password);
            //redirect to login page after registration
            echo"<p class='error'> Account created. Please log in. </p>"; 
            header('LOCATION:login.php?feedback="accCreated"');
            
        }


        
        
        
    } 
?>