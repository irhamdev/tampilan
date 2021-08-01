<?php 
	include ('db_connect.php');
    $kode = $_POST ['kode'];
	$nama = $_POST ['nama'];
	$keterangan = $_POST ['keterangan'];
    
    $query = "INSERT INTO data_sensor ( `kode`, `nama`, `keterangan`) VALUES ( '$kode', '$nama', '$keterangan')";

	mysqli_query($link,$query);
	header('Location: ../datasensor.php');
	
 ?>