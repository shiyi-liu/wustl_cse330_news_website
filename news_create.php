<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8"/>
	<title>New News</title>
    <link rel="stylesheet" href="news.css">
</head>

<body>
    <!-- https://www.tutorialspoint.com/How-to-Create-a-Multi-line-Text-Input-Text-Area-In-HTML -->
    <form name="login" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="POST" id='comment_submit'>
        <textarea name="title" form="comment_submit" rows = "1" cols = "60">Enter title here...</textarea> <br>
        <textarea name="news" form="comment_submit" rows = "5" cols = "60">Enter news here...</textarea>
        <input type='hidden' name='token' value='<?php session_start(); echo $_SESSION['token'];?>' />
        <input type='submit' value='Submit' name='submit'>
    </form>  
</body>

<?php
    if(isset($_POST['submit'])){ //check if all fields filled
        if(!hash_equals($_SESSION['token'], $_POST['token'])){
            die("Request forgery detected");
        }
        $user_id=$_SESSION['user_id'];
        $username=$_SESSION['username'];
        $news=$_POST['news'];
        $title=$_POST['title'];

        require 'database.php';

       
        
        // Use a prepared statement
        $stmt = $mysqli->prepare("
                                    insert into news
                                    (news, title, author_id)
                                    values (?,?,?)
                                ");
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        // Bind parameters
        $stmt->bind_param('sss', $news, $title, $user_id);      
        $stmt->execute();

        echo('Your news has been submitted.');
        echo("
            <form action='content.php' method='POST' >
                <input type='submit' value='Return to news content'>
            </form>
            
        ");
        $stmt->close();


        //gives unique link for each news article based on id
        $stmt = $mysqli->prepare("
                                    update news set link = concat(link, id)
                                ");
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        // Bind parameters   
        $stmt->execute();
        $stmt->close();
    }

?>  