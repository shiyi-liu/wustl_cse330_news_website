<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8"/>
	<title>Edit Comment</title>
    <link rel="stylesheet" href="news.css">
</head>

<body>        
    <h3> Delete Comment</h3>
<?php
    session_start();
    $user_id=$_SESSION['user_id'];
    $news_id=$_SESSION['news_id'];
    $username=$_SESSION['username'];
    $comment_text=$_POST['comment_text'];
    $comment_id=$_POST['comment_id'];
    $author_id=$_POST['author_id'];

    require 'database.php';

    if ($user_id==$author_id){
        // Use a prepared statement
        $stmt = $mysqli->prepare("
                                    delete from comments 
                                    where id=?
                                ");
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
        }
        // Bind parameters
        $stmt->bind_param('i', $comment_id);      
        $stmt->execute();
        echo("Your comment has been deleted successfully.");
        $stmt->close();
    }
    else{
        echo("Oops, it seems you are not the author of this comment.");
    }
?>
        <form action='content.php' method='POST'>
            <input type='submit' value='Return to News Content page'>
            <input type='hidden' name='token' value='<?php echo $_SESSION['token'];?>' />
            <input type='hidden' name='news_id' value=$news_id>
        </form>

<?php
    if(!hash_equals($_SESSION['token'], $_POST['token'])){
        die("Request forgery detected");
    }
?>        

</body>
</html>
