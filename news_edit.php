<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8"/>
	<title>New News</title>
    <link rel="stylesheet" href="news.css">
</head>

<?php
        session_start();
        $user_id=$_SESSION['user_id'];
        $username=$_SESSION['username'];
        $news_id=$_SESSION['news_id'];
        $news="";
        $title="";
  
        echo $news_id;
        require 'database.php';
        
        // Use a prepared statement
        $stmt = $mysqli->prepare(
                                    "select news, title from news where id=?"
                                );
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        // Bind parameters
        $stmt->bind_param('i', $news_id); 
        $stmt->bind_result($news, $title);      
        $stmt->execute();
        $stmt->close();
?>



<body>
    <!-- https://www.tutorialspoint.com/How-to-Create-a-Multi-line-Text-Input-Text-Area-In-HTML -->
    <form name="login" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="POST" id='comment_submit'>
        <textarea name="title" form="comment_submit" rows = "1" cols = "60"><?php $title ?></textarea> <br>
        <textarea name="news" form="comment_submit" rows = "5" cols = "60"><?php $news ?></textarea>
        <input type='hidden' name='token' value='<?php echo $_SESSION['token'];?>' />
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
        $news_id=$_SESSION['news_id'];
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
                <input type='hidden' value=$_POST['news_id'] name='news_id'> 
            </form>
            
        ");
        $stmt->close();
    }

   

?>