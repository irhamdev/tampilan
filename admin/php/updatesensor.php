<?php
	include('db_connect.php');
	$id_sensor = $_POST ['id_sensor'];
    $kode = $_POST ['kode'];
    $nama = $_POST ['nama'];
    $keterangan = $_POST ['keterangan'];
    
    // echo $id_bindo;
    // echo $bindo;

	$query = "UPDATE `data_sensor` SET `kode` = '$kode',`nama` = '$nama',`keterangan` = '$keterangan' WHERE `data_sensor` = '$id_sensor'";
	// echo $query;
	mysqli_query($link,$query);
	// $query;
	header('Location: ../datasensor.php');
?>