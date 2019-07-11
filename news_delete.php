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
                                    "delete from news where id=?"
                                );
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        // Bind parameters
        $stmt->bind_param('i', $news_id);      
        $stmt->execute();
        echo("Your story has been deleted successfully.");
        $stmt->close();

?>
        <form action='main.php' method='POST'>
            <input type='submit' value='Return to main page'>
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
