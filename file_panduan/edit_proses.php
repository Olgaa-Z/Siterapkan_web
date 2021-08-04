<?php
include "koneksi.php";

//pengecekan tipe harus pdf

$judul= trim($_POST['judul_edit']);
$var_id =trim($_POST['id']);
$nama_file = $_FILES['nama_file']['name'];

$nama_baru = "file_panduan_".$data['id'].".pdf";
$file_temp = $_FILES['nama_file']['tmp_name'];
$folder    = "../file_panduan"; //folder tujuan



if(!empty($file_temp)){
	move_uploaded_file($file_temp, "$folder/$nama_baru");
	$query="UPDATE tb_panduan SET judul='$judul', nama_file='$nama_baru' WHERE id=$var_id";
}else{
	$query="UPDATE tb_panduan SET judul='$judul' WHERE id=$var_id";
}

// $query="UPDATE tb_panduan SET judul='$judul' WHERE id=$var_id";

mysqli_query($koneksi, $query);
header("location: ../index.php?op=panduan");



?>