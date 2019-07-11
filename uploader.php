<?php
session_start();

//Increase file size limit from https://www.sitepoint.com/upload-large-files-in-php/
ini_set('upload_max_filesize', '10M');
ini_set('post_max_size', '10M');
ini_set('max_input_time', 300);
ini_set('max_execution_time', 300);

// Get the filename and make sure it is valid
$filename = basename($_FILES['uploadedfile']['name']);
if( !preg_match('/^[\w_\.\-]+$/', $filename) ){
    header("Location: main.php?feedback=invalidf");
}

// Get the username and make sure it is valid
$username = $_SESSION['usrnm'];
if( !preg_match('/^[\w_\-]+$/', $username) ){
    header("Location: main.php?feedback=invalidu");
}

$filesize = $_FILES['uploadedfile']['size'];
if($filesize > 10*10^9){
	header("Location: main.php?feedback=toobig");
}

$dir = $_SESSION['dir'];
$full_path = sprintf("$dir/%s", $filename);

if( move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $full_path) ){
	header("Location: main.php?feedback=uploadsuccess");
	exit;
}else{
	header("Location: main.php?feedback=uploadfail");
	exit;
}

?>