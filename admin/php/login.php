<?php
	session_start(); 
	include 'db_connect.php'; 
	$username=$_POST['username']; 	
	$password=$_POST['password']; 

	$query=mysqli_query($link,"SELECT * FROM admin WHERE username='$username' and password='$password'");	
	$admin=mysqli_num_rows($query);
	if($admin==TRUE){
		$_SESSION['username']=$username;
		$_SESSION['status'] ="login";
		header("location:../dashboard.php");     
	}else{
		header("location:../index.php?pesan=gagal");
	}
?>