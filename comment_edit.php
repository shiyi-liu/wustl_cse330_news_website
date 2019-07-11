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
    $user_id=$_SESSION['user_id'];
    $news_id=$_SESSION['news_id'];
    $username=$_SESSION['username'];
    $comment_text=$_POST['comment_text'];
    $comment_id=$_POST['comment_id'];
    $author_id=$_POST['author_id'];
?>
        <form action='comment_edit_backend.php' method='POST' id='comment_edit>
            <lable form='comment_edit'>Please edit your comment below: </lable>
            <br/>
            <input type='text' name='comment_updated' value=$comment_text >
            <input type='submit' name='comment_edit_save' value='Save'>
            <input type='hidden' name='comment_id' value=$comment_id>
            <input type='hidden' name='author_id' value=$author_id>
            <input type='hidden' name='token' value='<?php echo $_SESSION['token'];?>' />
        </form>

<?php  
    if(!hash_equals($_SESSION['token'], $_POST['token'])){
        die("Request forgery detected");
    }
?>
</body>
</html>