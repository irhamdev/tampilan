<?php
	include('db_connect.php');
	$id_sensor = $_GET['id_sensor'];
	$query = " DELETE FROM `data_sensor` WHERE `id_sensor`= '$id_sensor'";

	mysqli_query($link,$query);
	header('Location: ../datasensor.php');
?>