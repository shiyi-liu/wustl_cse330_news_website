<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8"/>
	<title>Login</title>
    <link rel="stylesheet" href="news.css">

</head>
  
<body>
	<!--Login Page HTML Part-->
    <h1>News Site</h1>
    <form name="login" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="POST">
        Username: <input type="text" name="usrnm"><br/>
        Password: <input type="password" name="password"><br/>
        <input type="submit" name='btn' class="btn" value="Log in">
    </form>
    <p>===Don't have an account?===</p>
    <form name="register" action="register.php" method="POST">
        Username: <input type="text" name="usrnm"><br/>
        Password: <input type="password" name="password"><br/>
        Confirm Password: <input type="password" name="confirm"> <br/>
        <input type="submit" name='btn' class="btn" value="Register">
    </form>
    <p>===Don't want to sign up?===</p>
    <form name='guest' action="guest.php" method="POST">
        <input type="submit" value="Browse as a guest">
    </form>
</body>

    <?php
        if(isset($_POST['btn'])&&!empty($_POST['usrnm'])&&!empty($_POST['password'])){ //check if all fields filled
            
                
                session_start(); //start a session for this user
                $user=$_POST['usrnm'];
                $pwd_guess = $_POST['password'];

                if( !preg_match('/^[\w_\.\-]+$/', $user) ){
                    echo "Invalid username";
                    exit;
                }
                if( !preg_match('/^[\w_\.\-]+$/', $pwd_guess) ){
                    echo "Invalid password";
                    exit;
                }

                require 'database.php'; //establish a database connection to execute sql commands
                
                // Use a prepared statement
                $stmt = $mysqli->prepare("SELECT COUNT(*), id, hashed_password FROM users WHERE username=?");

                // Bind the parameter
                $stmt->bind_param('s', $user);
                //$user = $_POST['usrnm'];        
                $stmt->execute();
                
                // Bind the results
                $stmt->bind_result($cnt, $user_id, $pwd_hash);
                $stmt->fetch();
                //echo($cnt."\n".$user_id."\n".$pwd_hash."\n"); 
                //$pwd_guess = $_POST['password'];
                // Compare the submitted password to the actual password hash
               
                if($cnt == 1 && password_verify($pwd_guess, $pwd_hash)){
                    // Login succeeded!
                    $_SESSION['user_id'] = $user_id;
                    $_SESSION['username']=$user; 
                    $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32)); // generate a 32-byte random string
                    //$_SESSION['token']=rand(0,99); //pass a token to the main page for security reason, which i havent figured out how it works
                    // Redirect to target page
                    header('LOCATION:main.php');
                } else{
                    // Login failed
                    echo('<p class="error"> Password incorrect. Please try again. </p>');
                }
                $stmt->close(); // Do we need this command here?
        }
    ?>
    <?php   
        /* the following is supposed to give feedback on successfully creating new accounts, but Im receiving errors saying "$feedback=$_GET['feedback'];" not defined*/
        /*
      
        $feedback=$_GET['feedback'];
        switch($feedback){
            case"accCreated":
                echo"<p class='error'> Account created. Please log in. </p>";
                break;
            case"compare":
                echo"verified";
            default:
                break;
        }*/
        
            
                
    ?>
</html> 