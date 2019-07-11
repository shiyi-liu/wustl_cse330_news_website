<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8"/>
	<title>Edit Comment</title>
    <link rel="stylesheet" href="news.css">
</head>
 
<body>        
<h3> Edit Comment</h3>

<?php
    session_start();
    $username=$_SESSION['username'];
    $user_id=$_SESSION['user_id'];
    $comment_updated=$_POST['comment_updated'];
    $comment_id=$_POST['comment_id'];
    $author_id=$_POST['author_id'];
    $news_id=$_SESSION['news_id'];

    require 'database.php';

    if ($user_id==$author_id){
        // Use a prepared statement
        $stmt = $mysqli->prepare("
                                    update comments
                                    set comment=? 
                                    where id=?
                                ");
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
        }
        // Bind parameters
        $stmt->bind_param('si', $comment_updated, $comment_id);      
        $stmt->execute();
        echo("Your comment has been updated successfully.");
        $stmt->close();
    }
    else{
        echo("Oops, it seems you are not the author of this comment.");
    }
?>
        <form action='content.php' method='POST'>
            <input type='submit' value='Return to News Content page'>
            <input type='hidden' name='news_id' value=<?php $news_id ?>>
        </form>
</body>
</html>