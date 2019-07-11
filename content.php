<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8"/>
	<title>News Content</title>
    <link rel="stylesheet" href="news.css">
</head>

<body>


<?php
    session_start();
    if(isset($_SESSION['news_id'])){
        unset($_SESSION['news_id']);
    }
    $news_id=$_POST['news_id'];
    $_SESSION['news_id']=$news_id;
    $user_id=$_SESSION['user_id'];
    $username=$_SESSION['username'];
    echo("<p> You are now logged in as $user_id $username </p>");

    require "database.php";

    // Select news
    // -Use a prepared statement
    $stmt = $mysqli->prepare("SELECT title, news FROM news where id=?");
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }   
    $stmt->bind_param('s', $news_id);
    $stmt->execute();
                
    // -Bind the results
    $stmt->bind_result($title, $news); 
    while($stmt->fetch()){
        echo"<table>
                <tr>
                    <th> $title </th>
                </tr>
                <tr> 
                    <td> $news </td>
                </tr>
            </table>";
    }

    $stmt->close(); 

    // Select comments
    // -Use a prepared statement
    $stmt = $mysqli->prepare("
                            SELECT 
                                comments.user_id,
                                comments.comment,
                                comments.id,
                                users.username
                            FROM comments 
                                join users on (comments.user_id=users.id)
                            where comments.news_id=$news_id
                            ");
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }   
    $stmt->execute();
                
    // -Bind the results
    $stmt->bind_result($author_id, $comment, $comment_id, $author_name);
    echo"<table>
            <tr>
                <th>Author</th>
                <th>Comment</th>
                <th></th>
            </tr>";

    // -Table of comments 
    while($stmt->fetch()){
        if ($user_id==$author_id){
            // provide edit and delete buttons for registered users
            echo"
                <tr>
                    <td> $author_name </td>
                    <td> $comment </td>
                    <td> 
                        <form action='comment_edit.php' method='POST'>
                            <input type='hidden' name='author_id' value=$author_id>
                            <input type='hidden' name='comment_id' value=$comment_id>
                            <input type='hidden' name='comment_text' value=$comment>
                            <input type='submit' name='submit' value='Edit'>
                        </form>
                        <br />
                        <form action='comment_delete.php' method='POST'>
                            <input type='hidden' name='author_id' value=$author_id>
                            <input type='hidden' name='comment_id' value=$comment_id>
                            <input type='hidden' name='comment_text' value=$comment>
                            <input type='submit' name='submit' value='Delete'>
                        </form>
                    </td>
                </tr>";
        }
        else{
            // display comments for guest users
            echo"
                <tr>
                    <td> $author_name </td>
                    <td> $comment </td>
                    <td> </td>
                </tr>";
        }
    }
 
    // provide add comment function for registered users
    if ($user_id!=0){
        echo"
            <form action='comment_add.php' method='POST'>
                <input type='submit' value='Add comment'>
            </form>
        ";
    }
    $stmt->close();
    
    echo"
    <form action='main.php' method='POST'>
        <input type='submit' value='Return to main page'>
        <input type='hidden' name='news_id' value=$news_id>
    </form>
";

    
?>
</body>
</html>