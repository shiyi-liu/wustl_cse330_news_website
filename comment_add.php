<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8"/>
	<title>New Comment</title>
    <link rel="stylesheet" href="news.css">
</head>
 
<body>
    <h3> Post comment</h3>
    <form name="login" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="POST" id='comment_submit'>
        <input type='text' name="comment_text" value='Please enter here...'>
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
        $news_id=$_SESSION['news_id'];
        $username=$_SESSION['username'];
        $comment_text=$_POST['comment_text'];

        require 'database.php';

       
        
        // Use a prepared statement
        $stmt = $mysqli->prepare("
                                    insert into comments
                                    (news_id, comment, user_id)
                                    values (?,?,?)
                                ");
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        // Bind parameters
        $stmt->bind_param('isi', $news_id, $comment_text, $user_id);      
        $stmt->execute();

        echo('Your comment has been submitted.');
        echo("
            <form action='content.php' method='POST' >
                <input type='submit' value='Return to news content'>
                <input type='hidden' value=$news_id name='news_id'>
            </form>
            
        ");
        $stmt->close();
    }

   

?>