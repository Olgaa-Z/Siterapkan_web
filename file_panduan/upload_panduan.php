<?php
include "koneksi.php";

//pengecekan tipe harus pdf
$tipe_file = $_FILES['nama_file']['type']; //mendapatkan mime type
if ($tipe_file == "application/pdf") //mengecek apakah file tersebu pdf atau bukan
{
	$judul     = trim($_POST['judul']);
	$nama_file = trim($_FILES['nama_file']['name']);

	$sql = "INSERT INTO tb_panduan (judul) VALUES ('$judul')";
 mysqli_query($koneksi,$sql); //simpan data judul dahulu untuk mendapatkan id

 //dapatkan id terkahir
 $query = mysqli_query($koneksi,"SELECT id FROM tb_panduan ORDER BY id DESC LIMIT 1");
 $data  = mysqli_fetch_array($query);

 //mengganti nama pdf
 $nama_baru = "file_panduan_".$data['id'].".pdf"; //hasil contoh: file_1.pdf
 $file_temp = $_FILES['nama_file']['tmp_name']; //data temp yang di upload
 $folder    = "../file_panduan"; //folder tujuan

 move_uploaded_file($file_temp, "$folder/$nama_baru"); //fungsi upload
 //update nama file di database
 mysqli_query($koneksi,"UPDATE tb_panduan SET nama_file='$nama_baru' WHERE id='$data[id]' ");

 header('location:input_panduan.php?pesan=upload-berhasil');

} else
{
	echo "Gagal Upload File Bukan PDF! <a href='input_panduan.php'> Kembali </a>";
}

?>