<?php
include "koneksi.php";
?>
<!DOCTYPE html>
<html>
<head>
	<title>Aplikasi Untuk Mengupload File PDF Dengan PHP | belajarwebpedia.com</title>
	<style type="text/css">
		body {
			font-family: verdana;
			font-size: 12px;
		}
		a {
			text-decoration: none;
			color: #3050F3;
		}
		a:hover {
			color: #000F5E;
		} 
	</style>
</head>
<body>

	<h1>Aplikasi Untuk Mengupload File PDF Dengan PHP</h1>
	<hr>
	<form action="upload_panduan.php" method="POST" enctype="multipart/form-data">
		<table width="600" border="0">
			<tr>
				<td width="100">Judul File</td>
				<td><input type="text" name="judul" placeholder="Judul" required></td>
			</tr>
			<tr>
				<td width="100">File PDF</td>
				<td><input type="file" name="nama_file" required></td>
			</tr>
			<tr>
				<td width="100"></td>
				<td><input type="submit" value="Upload File"></td>
			</tr>
		</table>
	</form>

	<hr>
	<b>List File PDF</b>
	<table width="800" border='0' cellpadding="2" cellspacing="1" bgcolor="#000000">
	<tr>
	 <th bgcolor="#ffffff">Judul</th>
	 <th bgcolor="#ffffff" width="100">View</th>
	 <th bgcolor="#ffffff" width="100">Hapus</th>
	 <th bgcolor="#ffffff" width="100">Edit</th>
	</tr>
	<?php
	$query = mysqli_query($koneksi,"SELECT * FROM tb_panduan ORDER BY id DESC");
	while($data=mysqli_fetch_array($query))
	{
	?>
	<tr>
	 <td bgcolor="#ffffff"><?php echo $data['judul'];?></td>
	 <th bgcolor="#ffffff"><a href="view.php?id=<?php echo $data['id'];?>">Lihat File</a></th>
	 <th bgcolor="#ffffff"><a href="hapus.php?id=<?php echo $data['id'];?>">Hapus File</a></th>
	 <th bgcolor="#ffffff"><a href="edit.php?id=<?php echo $data['id'];?>">Edit File</a></th>
	</tr>
	<?php
	}
	?> 
</table>

</body>
</html>