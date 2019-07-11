<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8"/>
	<title>News</title>
    <link rel="stylesheet" href="news.css">
</head>
<body>
    <h3> Welcome to WashU Times!</h3>
    <form action='<?php echo htmlentities($_SERVER['PHP_SELF']); ?>' method='POST'>
        <label>Sort by:</label>
        <select name='sort_by'>
            <option value="news.id">News ID</option>
            <option value="news.date">Date</option>
            
        </select>
        <input type='submit' value='Sort' name='submit_sort'>
    </form>
 

<?php
    session_start();
    $user_id=$_SESSION['user_id'];
    $username=$_SESSION['username'];

    //verify token, which i havent figure out how it works
    /*$token=$_SESSION['token'];
    unset($_SESSION['token']);
    */

    echo"<p> You are now logged in as $username. </p>";

    // Creative portion: Search news article
    echo"
        <form action='search.php' method='POST'>
            <label>Search here:</label>
            <input type='text' name='key_word'>
            <input type='submit' name='submit'>
        </form>
    ";

    

    require "database.php";
    // Use a prepared statement
    $stmt = $mysqli->prepare("
                            SELECT news.id, news.title, news.date, news.author_id, users.username
                            FROM news 
                            left join users on (news.author_id=users.id)
                            order by ?
                            ");
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }   
    
                
     // Bind the results
     if(isset($_POST['submit_sort'])){
         $stmt->bind_param('s', $_POST['sort_by']);
         echo("Now sorted by ".$_POST['sort_by']);
         $sort_by=$_POST['sort_by'];
     }
     else{
        $sort_by="news.title";
        $stmt->bind_param('s', $sort_by);
     }
    
    $stmt->execute();
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
        //echo($sort_by);
        //$_SESSION['news_id']=$id;
        // no need to pass news_id to $id
        if($news_author_id==$user_id){
            echo"<tr>
                    <td>$news_id</td>
                    <td>$news_date</td>
                    <td>$news_title</td>
                    <td>$author_name</td>
                    <td>
                        <form action=$link method='POST'>
                            <input type='hidden' value=$news_id name='news_id'
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
        <form action='logout.php' method='POST'>
            <input type='submit' value='Log out'>
        </form>";
?>
</body>
</html>