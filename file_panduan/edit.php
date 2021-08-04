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
	<?php
	$id = $_GET['id'];
	$query = mysqli_query($koneksi,"SELECT * FROM tb_panduan WHERE id=$id");
	while($data=mysqli_fetch_array($query))
	{
		?>
		<form action="edit_proses.php" method="POST" enctype="multipart/form-data">
			<table width="600" border="0">
				<input type="hidden" name="id" placeholder="ID" value="<?php echo $data['id'];?>"></td>
				<tr>
					<td width="100">Judul File</td>
					<td><input type="text" name="judul_edit" placeholder="Judul" value="<?php echo $data['judul'];?>" required></td>
				</tr>
				<tr>
					<td width="100">File PDF</td>
					<td><input type="file" name="nama_file"></td>
				</tr>
				<tr>
					<td width="100"></td>
					<td><input type="submit" value="Upload File"></td>
				</tr>
			</table>
		</form>

		<?php
	}
	?> 

</body>
</html>