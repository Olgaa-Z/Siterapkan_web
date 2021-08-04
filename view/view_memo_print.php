<?php
ini_set('date.timezone', 'Asia/Jakarta');
require_once "view/indo_tgl.php";
require_once "htmlpurifier/library/HTMLPurifier.auto.php";
$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);
$params = array(':id_sm' => trim($_GET['memoid']));
$memo = $this->model->selectprepare("arsip_sm a JOIN user b on a.id_user=b.id_user", $field = null, $params, "a.id_sm=:id_sm", $order = null);
if ($memo->rowCount() >= 1) {
	$data_memo = $memo->fetch(PDO::FETCH_OBJ);

	$params = array(':id' => 1);
	$pengaturan = $this->model->selectprepare("pengaturan", $field = null, $params, "id=:id", $other = null);
	if ($pengaturan->rowCount() >= 1) {
		$data_pengaturan = $pengaturan->fetch(PDO::FETCH_OBJ);
		$kop = $data_pengaturan->logo;
		$title = $data_pengaturan->title;
		$deskripsi = $data_pengaturan->deskripsi;
	} else {
		$kop = "default.jpg";
		$title = "E-Office - Sistem Informasi Arsip Surat";
		$deskripsi = "E-Office merupakan aplikasi pengelolaan arsip surat";
	}

	$ListUser = $this->model->selectprepare("user a join user_jabatan b on a.jabatan=b.id_jab", $field = null, $params = null, $where = null, "ORDER BY a.nama ASC");
	$TujuanSurat = "";
	$TargetDisposisi = "";
	$DataTembusanVer = "";
	$DataTembusanHor = "";
	while ($dataListUser = $ListUser->fetch(PDO::FETCH_OBJ)) {
		/* if(false !== array_search($dataListUser->id_user, json_decode($data_memo->disposisi, true))){
			$TargetDisposisi .= '- '.$dataListUser->nama .'<br/>';
		} */
		if (false !== array_search($dataListUser->id_user, json_decode($data_memo->tujuan_surat, true))) {
			$TujuanSurat .= '- ' . $dataListUser->nama . ' (' . $dataListUser->nama_jabatan . ')<br/>';
		}
		/* if(false !== array_search($dataListUser->id_user, json_decode($data_memo->tembusan, true))){
			$DataTembusanVer .= '- '.$dataListUser->nama .'<br/>';
			$DataTembusanHor .='- '.$dataListUser->nama .', ';
		} */
	}

	$kopSet = $this->model->selectprepare("kop_setting", $field = null, $params = null, $where = null, "WHERE idkop='2'");
	$dataKopSet = $kopSet->fetch(PDO::FETCH_OBJ);
	$layout = $dataKopSet->layout;
	$Rlayout = $layout;

	$arr = array("=NoAgenda=" => $data_memo->custom_noagenda, "=NoSurat=" => $data_memo->no_sm, "=Perihal=" => $data_memo->perihal, "=TujuanSurat=" => $TujuanSurat, "=TglSurat=" => tgl_indo($data_memo->tgl_surat), "=TglTerima=" => tgl_indo($data_memo->tgl_terima), "=AsalSurat=" => $data_memo->pengirim, "=Keterangan=" => $data_memo->ket, "=Penerima=" => $data_memo->nama);
	foreach ($arr as $nama => $value) {
		if (strpos($layout, $nama) !== false) {
			$Rlayout = str_replace($nama, $value, $layout);
			$layout = $Rlayout;
		}
	} ?>
	<html>

	<head>
		<meta http-equiv="Content-Language" content="en-us">
		<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="GENERATOR" content="Microsoft FrontPage 4.0">
		<meta name="ProgId" content="FrontPage.Editor.Document">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
	</head>

	<body>
		<div class="container">
			<?php
			if ($dataKopSet->status == "Y") {
				if ($dataKopSet->kopdefault == "Y") { ?>
					<img src="./<?php echo "foto/$kop"; ?>" class="img-fluid w-100">
				<?php
				}
				echo $Rlayout;
			} else {
				if ($dataKopSet->kopdefault == "Y") { ?>
					<img src="./<?php echo "foto/$kop"; ?>" class="img-fluid w-100">
				<?php
				} ?>
				<div class="text-right">
					<button class="btn btn-sm btn-outline-primary my-2 d-md-none d-block" onclick="window.print()">
						<i class="fas fa-print    "></i>
						Cetak
					</button>
				</div>
				<h3 style="text-align:center;">SURAT MASUK</h3>

				<!-- <table border="0" cellspacing="0" cellpadding="0" style='border-collapse:collapse;' align="center">
					<tr>
						<td width="100"> -->
				<div class="table-responsive">
					<table class="table table-bordered">
						<tr align=left>
							<th nowrap style="padding: 5px; vertical-align: top;">Surat Dari</th>
							<td nowrap style="padding: 5px; vertical-align: top; width:250"><?php echo $data_memo->pengirim; ?></td>
							<th nowrap style="padding: 5px; vertical-align: top;">Diterima Tanggal </th>
							<td nowrap style="padding: 5px; vertical-align: top; width:225"><?php echo tgl_indo($data_memo->tgl_terima); ?></td>
						</tr>
						<tr align=left>

							<th nowrap style="padding: 5px; vertical-align: top;">Tanggal Surat</th>
							<td style="padding: 5px; vertical-align: top;"><?php echo tgl_indo($data_memo->tgl_surat); ?></td>
							<th nowrap style="padding: 5px; vertical-align: top;">Nomor Agenda</th>
							<td nowrap style="padding: 5px; vertical-align: top;"><?php echo $data_memo->custom_noagenda; ?></td>
						</tr>
						<tr align=left>
							<th nowrap style="padding: 5px; vertical-align: top;">Nomor Surat </th>
							<td style="padding: 5px;"><?php echo $data_memo->no_sm; ?></td>
							<th nowrap style="padding: 5px; vertical-align: top;">Tujuan Surat</th>
							<td nowrap style="padding: 5px; vertical-align: top;"><?php echo $TujuanSurat; ?></td>
						</tr>
						<tr align=left height="100">
							<th nowrap style="padding: 5px; vertical-align: top;">Perihal </th>
							<td style="padding: 5px; vertical-align: top;"><?php echo $data_memo->perihal; ?></td>
							<th nowrap style="padding: 5px; vertical-align: top;">Ket </th>
							<td style="padding: 5px; vertical-align: top;"><?php echo $data_memo->ket; ?></td>
						</tr>
					</table>
				</div>
				<!-- </td>
					</tr>
				</table> -->
		</div><?php
			} ?>
	</body>

	</html><?php
		} else {
			echo "Belum ada data";
		}
		/*Cetak Direct PDF*/
		if (isset($_GET['act']) and $_GET['act'] == "pdf") {
			$filename = $data_memo->no_sm . ".pdf";
			$content = ob_get_clean();
			$content = '<page style="font-family: Verdana,Arial,Helvetica,sans-serif"">' . nl2br($content) . '</page>';
			require_once 'html2pdf/html2pdf.class.php';
			try {
				$html2pdf = new HTML2PDF('P', 'A4', 'en', false, 'ISO-8859-15', array(0, 5, 0, 0));
				$html2pdf->setDefaultFont('Arial');
				$html2pdf->writeHTML($content, isset($_GET['vuehtml']));
				$html2pdf->Output($filename);
			} catch (HTML2PDF_exception $e) {
				echo "Terjadi Error kerena : " . $e;
			}
		}
