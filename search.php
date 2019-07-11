<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8"/>
	<title>News</title>
    <link rel="stylesheet" href="news.css">
</head>
<body>
    <h3> Welcome to WashU Times!</h3>
    <?php
        session_start();
  
        //verify token
        /*if(!hash_equals($_SESSION['token'], $_POST['token'])){
            die("Request forgery detected");
        }*/

        $user_id=$_SESSION['user_id'];
        $username=$_SESSION['username'];
        $key_word=$_POST['key_word'];

        require "database.php";
        // Use a prepared statement
        $stmt = $mysqli->prepare("
                                SELECT news.id, news.title, news.date, news.author_id, users.username
                                FROM news 
                                left join users on (news.author_id=users.id)
                                where news.title like ?
                                order by news.id
                                ");
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }  
        
        $key_string=sprintf("%%%s%%", $key_word);
        $stmt->bind_param('s', $key_string);
        $stmt->execute();
                    
        // Bind the results
        $stmt->bind_result($news_id, $news_title, $news_date, $news_author_id, $author_name);
        $link="content.php";

        // Create the table of titles
        
        echo"<table>";
        echo"<tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th></th>
                </tr>";
        while($stmt->fetch()){
            //$_SESSION['news_id']=$id;
            if($news_author_id==$user_id){
                echo"<tr>
                        <td>$news_id</td>
                        <td>$news_date</td>
                        <td>$news_title</td>
                        <td>$author_name</td>
                        <td>
                            <form action=$link method='POST'>
                                <input type='hidden' value=$news_id name='news_id'>
                                <input type='submit' value='View'>
                            </form>
                            <br />
                            <form action='news_edit.php' method='POST'>
                                <input type='hidden' value=$news_id name='news_id'>
                                <input type='hidden' value=$news_author_id' name='news_author_id'>
                                <input type='submit' value='Edit'>
                            </form>
                            <br />
                            <form action='news_delete.php' method='POST'>
                                <input type='hidden' value=$news_id name='news_id'>
                                <input type='hidden' value=$news_author_id' name='news_author_id'>
                                <input type='submit' value='Delete'>
                            </form>
                        </td>
                    </tr>";
            }
            else{
                echo"
                <tr>
                    <td>$news_id</td>
                    <td>$news_date</td>
                    <td>$news_title</td>
                    <td>$author_name</td> 
                    <td>
                        <form action=$link method='POST'>
                            <input type='hidden' value=$news_id name='news_id'>
                            <input type='submit' value='View'>
                        </form>
                    </td>
                </tr>";
            }
        
            
        }
        echo "</table>";
        
        // Add new news
        if ($user_id != 0){
            echo("
                <br />
                <form action='news_create.php' method='POST'>
                    <input type='submit' value='Submit new article'>
                </form>
            ");
        }
        $stmt->close();

        echo"<br/>
            <form action='main.php' method='POST'>
                <input type='submit' value='Return to full news list'>
            </form>";
            



    ?>
</body>
</html>
