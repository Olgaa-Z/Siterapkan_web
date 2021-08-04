<?php
if ($_SERVER["REQUEST_METHOD"] == "POST"){
	$noagenda = htmlspecialchars($purifier->purify(trim($_POST['noagenda'])), ENT_QUOTES);
	$noagenda_custom = trim($_POST['noagenda_custom']);
	$nosm = htmlspecialchars($purifier->purify(trim($_POST['nosm'])), ENT_QUOTES);
	$tglsm = htmlspecialchars($purifier->purify(trim($_POST['tglsm'])), ENT_QUOTES);
	$tglsm = explode("-",$tglsm);
	$tglsmdb = $tglsm[2]."-".$tglsm[1]."-".$tglsm[0];
	$tgl_terima = htmlspecialchars($purifier->purify(trim($_POST['tgl_terima'])), ENT_QUOTES);
	$tgl_terima = explode("-",$tgl_terima);
	$tgl_terimadb = $tgl_terima[2]."-".$tgl_terima[1]."-".$tgl_terima[0];
	$tgl_retensi = htmlspecialchars($purifier->purify(trim($_POST['tgl_retensi'])), ENT_QUOTES);
	$tgl_retensi = date_format(date_create($tgl_retensi), "Y-m-d");
	$pengirim = htmlspecialchars($purifier->purify(trim($_POST['pengirim'])), ENT_QUOTES);
	$tujuan = json_encode($_POST['tujuan']);
	$perihal = htmlspecialchars($purifier->purify(trim($_POST['perihal'])), ENT_QUOTES);
	$ket = htmlspecialchars($purifier->purify(trim($_POST['ket'])), ENT_QUOTES);
	$klasifikasi = htmlspecialchars($purifier->purify(trim($_POST['id_klasifikasi'])), ENT_QUOTES);
	
	$fileName = htmlspecialchars($_FILES['filesm']['name'], ENT_QUOTES);
	$tipefile = pathinfo($fileName,PATHINFO_EXTENSION);
	$extensionList = array("pdf","jpg","jpeg","png");
	$namaDir = 'berkas/';
	$filesm = $namaDir."SM"."_".$tgl_terima[0]."-".$tgl_terima[1]."-".$tgl_terima[2]."_". slugify($perihal)."_". date("d-m-Y_H-i-s", time()) .".".$tipefile;
	if(empty($fileName)){
		$filedb = "";
	}else{
		$filedb = "SM"."_".$tgl_terima[0]."-".$tgl_terima[1]."-".$tgl_terima[2]."_". slugify($perihal)."_". date("d-m-Y_H-i-s", time()) .".".$tipefile;
	}
	$tgl_upload = date("Y-m-d H:i:s", time());
	
	//print_r($_POST);
	if(isset($_GET['smid'])){
		$smid = htmlspecialchars($purifier->purify(trim($_GET['smid'])), ENT_QUOTES);
		$params = array(':id_sm' => $smid);
		$lihat_sm = $this->model->selectprepare("arsip_sm", $field=null, $params, "id_sm=:id_sm");
		if($lihat_sm->rowCount() >= 1){
			$data_lihat_sm = $lihat_sm->fetch(PDO::FETCH_OBJ);
			$idsm = $data_lihat_sm->id_sm;
			if(empty($fileName)){
				//echo "No Update File";
				$field = array('no_sm' => $nosm, 'tgl_terima' => $tgl_terimadb, 'tgl_surat' => $tglsmdb, 'tgl_retensi' => $tgl_retensi, 'pengirim' => $pengirim, 'klasifikasi' => $klasifikasi, 'tujuan_surat' => $tujuan, 'perihal' => $perihal, 'ket' => $ket);
			}else{
				if(in_array($tipefile, $extensionList)){
					@unlink($namaDir.$data_lihat_sm->file);
					$field = array('no_sm' => $nosm, 'tgl_terima' => $tgl_terimadb, 'tgl_surat' => $tglsmdb, 'tgl_retensi' => $tgl_retensi, 'pengirim' => $pengirim, 'klasifikasi' => $klasifikasi, 'tujuan_surat' => $tujuan, 'perihal' => $perihal, 'ket' => $ket, 'file' => $filedb);
					move_uploaded_file($_FILES['filesm']['tmp_name'], $filesm);
				}else{
					echo "<script type=\"text/javascript\">alert('File gagal di Upload, Format file tidak di dukung!!!');window.location.href=\"./index.php?op=add_sm&smid=$idsm\";</script>";
				}
			}
			$params = array(':id_sm' => $idsm);
			$update = $this->model->updateprepare("arsip_sm", $field, $params, "id_sm=:id_sm");
			if($update){
				echo "<script type=\"text/javascript\">alert('Data Berhasil diperbaharui...!!');window.location.href=\"./index.php?op=sm&smid=$idsm\";</script>";
			}
		}
	}else{
		//echo $tujuan;
		$params = array(':no_agenda' => $noagenda);
		/*$cek_nosm = $this->model->selectprepare("arsip_sm", $field=null, $params, "no_agenda=:no_agenda");
		if($cek_nosm->rowCount() <= 0){*/
			$field = array('id_user' => $_SESSION['id_user'], 'no_sm'=>$nosm, 'tgl_terima'=>$tgl_terimadb, 'no_agenda'=>$noagenda, 'custom_noagenda' => $noagenda_custom, 'tgl_surat'=>$tglsmdb, 'tgl_retensi' => $tgl_retensi, 'pengirim'=>$pengirim, 'klasifikasi' => $klasifikasi, 'perihal'=>$perihal, 'tujuan_surat'=>$tujuan, 'ket'=>$ket, 'file'=>$filedb, 'created'=>$tgl_upload);
			$params = array(':id_user' => $_SESSION['id_user'], ':no_sm'=>$nosm, ':tgl_terima'=>$tgl_terimadb, ':no_agenda'=>$noagenda, ':custom_noagenda' => $noagenda_custom, ':tgl_surat'=>$tglsmdb, ':tgl_retensi' => $tgl_retensi, ':pengirim'=>$pengirim, 'klasifikasi' => $klasifikasi, ':perihal'=>$perihal, ':tujuan_surat'=>$tujuan, ':ket'=>$ket, ':file'=>$filedb, ':created'=>$tgl_upload);
			if(empty($fileName)){
				$insert = $this->model->insertprepare("arsip_sm", $field, $params);
				if($insert->rowCount() >= 1){
					//Kirim Email
					$EmailAccount = $this->model->selectprepare("pengaturan", $field=null, $params=null, $where=null, "WHERE id='1' AND email !='' AND pass_email !=''");
					$AktifEmail = $this->model->selectprepare("email_setting", $field=null, $params=null, $where=null, "WHERE id_kop='2' AND status='Y'");
					if($EmailAccount->rowCount() >= 1 AND $AktifEmail->rowCount() >= 1){
						$dataEmailAccount = $EmailAccount->fetch(PDO::FETCH_OBJ);
						$dataAktifEmail = $AktifEmail->fetch(PDO::FETCH_OBJ);
						
						$TujuanSurat = "";
						if($tujuan != ''){
							$dataTujuan = json_decode($tujuan, true);
							$ListUser = $this->model->selectprepare("user a join user_jabatan b on a.jabatan=b.id_jab", $field=null, $params=null, $where=null, "ORDER BY a.nama ASC");
							while($dataListUser = $ListUser->fetch(PDO::FETCH_OBJ)){
								if(false !== array_search($dataListUser->id_user, json_decode($tujuan, true))){
									$TujuanSurat .= '- '.$dataListUser->nama .' ('.$dataListUser->nama_jabatan .')<br/>';
								}
							}
						}
						
						$isi = $dataAktifEmail->layout;
						$Rlayout = $isi;
						$arr = array("=NoAgenda=" =>sprintf("%04d", $noagenda), "=NoSurat=" => $nosm, "=Perihal=" => $perihal, "=TujuanSurat=" => $TujuanSurat, "=TglSurat=" =>tgl_indo($tglsmdb), "=TglRetensi=" =>tgl_indo($tgl_retensi), "=TglTerima=" => tgl_indo($tgl_terimadb), "=AsalSurat=" =>$pengirim, "=Keterangan=" => $ket, "=Penerima=" =>$_SESSION['nama']);
						foreach($arr as $nama => $value){
							if(strpos($isi, $nama) !== false) {
								$Rlayout = str_replace($nama, $value, $isi);
								$isi = $Rlayout;
							}
						}
						
						if($tujuan != '' OR $tujuan != 'null'){
							$mail = new PHPMailer;
							$mail->SMTPDebug = 0;                               
							$mail->isSMTP();                                 
							$mail->Host = "smtp.gmail.com";
							$mail->SMTPAuth = true;  
							$mail->Username = $dataEmailAccount->email;
							$mail->Password = $dataEmailAccount->pass_email;                      
							//If SMTP requires TLS encryption then set it
							//$mail->SMTPSecure = "tls";                           
							$mail->Port = 587;                                   
							$mail->From = $dataEmailAccount->email;
							$mail->FromName = $_SESSION['nama'];
							$mail->smtpConnect(
								array(
									"ssl" => array(
										"verify_peer" => false,
										"verify_peer_name" => false,
										"allow_self_signed" => true
									)
								)
							);
							foreach($dataTujuan as $id_tujuan){
								$params = array(':id_user' => $id_tujuan);
								$user_tujuan = $this->model->selectprepare("user", $field=null, $params, "id_user=:id_user", $other=null);
								$data_user_tujuan= $user_tujuan->fetch(PDO::FETCH_OBJ);
								if($data_user_tujuan->email != ''){
									$mail->AddAddress($data_user_tujuan->email, $data_user_tujuan->nama);
								}
							}
							$mail->isHTML(true);
							$topik = "Surat Masuk: ".$perihal;
							$mail->Subject = $topik;
							$mail->Body = $isi;
							$mail->AltBody = $perihal;
							if(!$mail->send()) {
								//echo "Mailer Error: " . $mail->ErrorInfo;
								echo "<script type=\"text/javascript\">alert('Data Berhasil diSimpan. Email notifikasi gagal dikirim!');window.location.href=\"./index.php?op=add_sm\";</script>";
							}else{
								echo "<script type=\"text/javascript\">alert('Data Berhasil diSimpan, Email notifikasi dikirim!');window.location.href=\"./index.php?op=add_sm\";</script>";
							}
						}else{
							echo "<script type=\"text/javascript\">alert('Data Berhasil diSimpan!');window.location.href=\"./index.php?op=add_sm\";</script>";
						}
					}else{
						echo "<script type=\"text/javascript\">alert('Data Berhasil diSimpan!');window.location.href=\"./index.php?op=add_sm\";</script>";
					}
				}else{
					die("<script>alert('Data Gagal di simpan ke Database, Silahkan Coba Kembali..!!');window.history.go(-1);</script>");
				}
			}else{
				if(in_array($tipefile, $extensionList)){
					if(move_uploaded_file($_FILES['filesm']['tmp_name'], $filesm)){
						$insert = $this->model->insertprepare("arsip_sm", $field, $params);
						if($insert->rowCount() >= 1){
							//Kirim Email
							$EmailAccount = $this->model->selectprepare("pengaturan", $field=null, $params=null, $where=null, "WHERE id='1' AND email !='' AND pass_email !=''");
							$AktifEmail = $this->model->selectprepare("email_setting", $field=null, $params=null, $where=null, "WHERE id_kop='2' AND status='Y'");
							if($EmailAccount->rowCount() >= 1 AND $AktifEmail->rowCount() >= 1){
								$dataEmailAccount = $EmailAccount->fetch(PDO::FETCH_OBJ);
								$dataAktifEmail = $AktifEmail->fetch(PDO::FETCH_OBJ);
								
								$TujuanSurat = "";
								if($tujuan != ''){
									$dataTujuan = json_decode($tujuan, true);
									$ListUser = $this->model->selectprepare("user a join user_jabatan b on a.jabatan=b.id_jab", $field=null, $params=null, $where=null, "ORDER BY a.nama ASC");
									while($dataListUser = $ListUser->fetch(PDO::FETCH_OBJ)){
										if(false !== array_search($dataListUser->id_user, json_decode($tujuan, true))){
											$TujuanSurat .= '- '.$dataListUser->nama .' ('.$dataListUser->nama_jabatan .')<br/>';
										}
									}
								}
								
								$isi = $dataAktifEmail->layout;
								$Rlayout = $isi;
								$arr = array("=NoAgenda=" =>sprintf("%04d", $noagenda), "=NoSurat=" => $nosm, "=Perihal=" => $perihal, "=TujuanSurat=" => $TujuanSurat, "=TglSurat=" =>tgl_indo($tglsmdb), "=TglRetensi=" =>tgl_indo($tgl_retensi), "=TglTerima=" => tgl_indo($tgl_terimadb), "=AsalSurat=" =>$pengirim, "=Keterangan=" => $ket, "=Penerima=" =>$_SESSION['nama']);
								foreach($arr as $nama => $value){
									if(strpos($isi, $nama) !== false) {
										$Rlayout = str_replace($nama, $value, $isi);
										$isi = $Rlayout;
									}
								}
								
								if($tujuan != '' OR $tujuan != 'null'){
									$mail = new PHPMailer;
									$mail->SMTPDebug = 0;                               
									$mail->isSMTP();                                 
									$mail->Host = "smtp.gmail.com";
									$mail->SMTPAuth = true;  
									$mail->Username = $dataEmailAccount->email;
									$mail->Password = $dataEmailAccount->pass_email;                      
									//If SMTP requires TLS encryption then set it
									//$mail->SMTPSecure = "tls";                           
									$mail->Port = 587;                                   
									$mail->From = $dataEmailAccount->email;
									$mail->FromName = $_SESSION['nama'];
									$mail->smtpConnect(
										array(
											"ssl" => array(
												"verify_peer" => false,
												"verify_peer_name" => false,
												"allow_self_signed" => true
											)
										)
									);
									foreach($dataTujuan as $id_tujuan){
										$params = array(':id_user' => $id_tujuan);
										$user_tujuan = $this->model->selectprepare("user", $field=null, $params, "id_user=:id_user", $other=null);
										$data_user_tujuan= $user_tujuan->fetch(PDO::FETCH_OBJ);
										if($data_user_tujuan->email != ''){
											$mail->AddAddress($data_user_tujuan->email, $data_user_tujuan->nama);
										}
									}
									$mail->isHTML(true);
									$topik = "Surat Masuk: ".$perihal;
									$mail->Subject = $topik;
									$mail->Body = $isi;
									$mail->AltBody = $perihal;
									$lokasi = "berkas/$filedb";
									if(file_exists($lokasi)){
										$mail->addAttachment($lokasi);
									}
									if(!$mail->send()) {
										//echo "Mailer Error: " . $mail->ErrorInfo;
										echo "<script type=\"text/javascript\">alert('Data Berhasil diSimpan. Email notifikasi gagal dikirim!');window.location.href=\"./index.php?op=add_sm\";</script>";
									}else{
										echo "<script type=\"text/javascript\">alert('Data Berhasil diSimpan, Email notifikasi dikirim!');window.location.href=\"./index.php?op=add_sm\";</script>";
									}
								}else{
									echo "<script type=\"text/javascript\">alert('Data Berhasil diSimpan!');window.location.href=\"./index.php?op=add_sm\";</script>";
								}
							}else{
								echo "<script type=\"text/javascript\">alert('Data Berhasil diSimpan!');window.location.href=\"./index.php?op=add_sm\";</script>";
							}
						}else{
							die("<script>alert('Data Gagal di simpan ke Database, Silahkan Coba Kembali..!!');window.history.go(-1);</script>");
						}
					}else{
						echo "<script type=\"text/javascript\">alert('File gagal di Upload ke Folder, Silahkan ulangi!!!');window.history.go(-1);</script>";
					}
				}else{
					echo "<script type=\"text/javascript\">alert('File gagal di Upload, Format file tidak di dukung!!!');window.history.go(-1);</script>";
				}
			}
		/*}else{
			echo "<script type=\"text/javascript\">alert('PERHATIAN..! Nomor Agenda Surat Masuk yang dimasukkan sudah pernah terdata di Sistem. Silahkan Ulangi.');window.history.go(-1);</script>";
		}*/
	}
}else{
	$cek_noaagenda = $this->model->selectprepare("arsip_sm", $field=null, $params=null, $where=null, "ORDER BY id_sm DESC LIMIT 1");
	if(isset($_GET['smid'])){
		$smid = htmlspecialchars($purifier->purify(trim($_GET['smid'])), ENT_QUOTES);
		$params = array(':id_sm' => $smid);
		$cek_sm = $this->model->selectprepare("arsip_sm", $field=null, $params, "id_sm=:id_sm");
		if($cek_sm->rowCount() >= 1){
			$data_sm = $cek_sm->fetch(PDO::FETCH_OBJ);
			$title= "Edit Data Surat Masuk";
			$ketfile = "File Surat ";
			$noagenda = 'value="'.$data_sm->no_agenda .'"';
			$nosm = 'value="'.$data_sm->no_sm .'"';
			$tgl_surat = explode("-", $data_sm->tgl_surat);
			$tgl_surat = $tgl_surat[2]."-".$tgl_surat[1]."-".$tgl_surat[0];
			$tgl_surat = 'value="'.$tgl_surat.'"';
			$tgl_terima = explode("-", $data_sm->tgl_terima);
			$tgl_terima = $tgl_terima[2]."-".$tgl_terima[1]."-".$tgl_terima[0];
			$tgl_terima = 'value="'.$tgl_terima.'"';
			$pengirim = 'value="'.$data_sm->pengirim .'"';
			if($data_sm->tgl_retensi == '0000-00-00'){
				$tgl_retensi = 'value=""';
			}else{
				$tgl_retensi = 'value="'.date_format(date_create($data_sm->tgl_retensi), "d-m-Y").'"';
			}
			//$tujuan = 'value="'.$data_sm->tujuan_surat .'"';
			$perihal = $data_sm->perihal;
			$ket = $data_sm->ket;
			$id_klasifikasi = $data_sm->klasifikasi;			
			if(isset($data_sm->tujuan_surat) == '' OR $data_sm->tujuan_surat == "null"){
				$dummy_arr = '[""]';
				$cekDiteruskan = json_decode($dummy_arr, true);
			}else{
				$cekDiteruskan = json_decode($data_sm->tujuan_surat, true);
			}
			$noAgenda = sprintf("%04d", $data_sm->no_agenda);
			$nonoAgendaShow = $data_sm->custom_noagenda;
		}else{
			$title= "Entri Surat Masuk";
			$ketfile = "File Surat ";
			$validasifile = "required";
			$dummy_arr = '[""]';
			$cekDiteruskan = json_decode($dummy_arr, true);
			$params = array(':id' => 1);
			$cek_noagenda_custom = $this->model->selectprepare("pengaturan", $field=null, $params, "id=:id")->fetch(PDO::FETCH_OBJ);

			if($cek_noaagenda->rowCount() >= 1){
				$data_cek_noaagenda = $cek_noaagenda->fetch(PDO::FETCH_OBJ);
				$thn_data_surat = substr($data_cek_noaagenda->created,0,4); 
				if($thn_data_surat == date('Y')){
					$noAgenda = sprintf("%04d", $data_cek_noaagenda->no_agenda+1);
					$nonoAgendaShow = $data_cek_noaagenda->no_agenda+1;
				}else{					
					if($cek_noagenda_custom->no_agenda_sm_start != ''){
						$noAgenda = sprintf("%04d", $cek_noagenda_custom->no_agenda_sm_start);
						$nonoAgendaShow = $cek_noagenda_custom->no_agenda_sm_start;
					}else{
						$noAgenda = sprintf("%04d", 1);
						$nonoAgendaShow = 1;
					}
				}				
			}else{
				if($cek_noagenda_custom->no_agenda_sm_start != ''){
					$noAgenda = sprintf("%04d", $cek_noagenda_custom->no_agenda_sm_start);
					$nonoAgendaShow = $cek_noagenda_custom->no_agenda_sm_start;
				}else{
					$noAgenda = sprintf("%04d", 1);
					$nonoAgendaShow = 1;
				}
			}
		}
	}else{
		$title= "Entri Surat Masukk";
		$validasifile = "required";
		$ketfile = "File Surat ";
		$dummy_arr = '[""]';
		$cekDiteruskan = json_decode($dummy_arr, true);
		$params = array(':id' => 1);
		$cek_noagenda_custom = $this->model->selectprepare("pengaturan", $field=null, $params, "id=:id")->fetch(PDO::FETCH_OBJ);

		if($cek_noaagenda->rowCount() >= 1){
			$data_cek_noaagenda = $cek_noaagenda->fetch(PDO::FETCH_OBJ);
			$thn_data_surat = substr($data_cek_noaagenda->created,0,4); 
			if($thn_data_surat == date('Y')){
				$noAgenda = sprintf("%04d", $data_cek_noaagenda->no_agenda+1);
				$nonoAgendaShow = $data_cek_noaagenda->no_agenda+1;
			}else{					
				if($cek_noagenda_custom->no_agenda_sm_start != ''){
					$noAgenda = sprintf("%04d", $cek_noagenda_custom->no_agenda_sm_start);
					$nonoAgendaShow = $cek_noagenda_custom->no_agenda_sm_start;
				}else{
					$noAgenda = sprintf("%04d", 1);
					$nonoAgendaShow = 1;
				}
			}
		}else{
			if($cek_noagenda_custom->no_agenda_sm_start != ''){
				$noAgenda = sprintf("%04d", $cek_noagenda_custom->no_agenda_sm_start);
				$nonoAgendaShow = $cek_noagenda_custom->no_agenda_sm_start;
			}else{
				$noAgenda = sprintf("%04d", 1);
				$nonoAgendaShow = 1;
			}
		}
	}
	//print_r($cekDiteruskan);?>
	

	<div class="col-md-12 ">
		<!-- Horizontal Form -->
		<div class="box box-info widget-body ">
			<div class="box-header with-border">
				<h3 class="box-title"><b><?php echo $title;?></b></h3>
				<div class="box-tools pull-right">
					<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
				</div>
			</div>
			<!-- /.box-header -->
			<!-- form start -->
			<form class="form-horizontal" role="form" enctype="multipart/form-data" method="POST" name="formku" action="<?php echo $_SESSION['url'];?>">
				<div class="box-body">
					<div class="form-group">
						<label for="inputEmail3" class="col-sm-2 control-label" control-label">No Agenda*</label>
						<div class="col-sm-9">
							<?php 
							$params = array(':id' => 1);
							$cek_noagenda_custom = $this->model->selectprepare("pengaturan", $field=null, $params, "id=:id")->fetch(PDO::FETCH_OBJ);
							if($cek_noagenda_custom->no_agenda_sm != ''){
								//$noAgendaDb = $noAgenda;
								$noAgenda = $noAgenda."/$cek_noagenda_custom->no_agenda_sm";								
							} ?>
							<input class="form-control" id="inputEmail3" readonly="" placeholder="Nomor Agenda Surat" type="text" name="noagenda" <?php if(isset($noAgenda)){ echo 'value="'.$noAgenda .'"'; } ?> />
							<input type="hidden" name="noagenda" value="<?php echo $nonoAgendaShow;?>"/>
							<input type="hidden" name="noagenda_custom" value="<?php echo $noAgenda;?>"/>
							<?php  ?>
						</div>
						<span style="padding-right: auto;"  data-placement="left" class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Di isi sesuai dengan nomor agenda surat masuk.">
							<i class="fa fa-question-circle"></i>
						</span>
					</div>
					<div class="form-group">
						<label for="inputEmail3" class="col-sm-2 control-label" control-label>No Surat*</label>

						<div class="col-sm-9">
							<input class="form-control" placeholder="Nomor surat masuk" type="text" name="nosm" <?php if(isset($nosm)){ echo $nosm; }?> id="form-field-mask-1" required/>
						</div>
						<span style="padding-right: auto;"  data-placement="left" class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Di isi sesuai dengan nomor surat masuk yang diterima." data-content="Di isi sesuai dengan nomor surat masuk yang diterima.">
							<i class="fa fa-question-circle"></i>
						</span>
					</div>
					<div class="form-group">
						<label for="inputEmail3" class="col-sm-2 control-label">Klasifikasi Surat</label>

						<div class="col-sm-9">
							<select class="form-control select2" id="form-field-select-3" name="id_klasifikasi" data-placeholder="Pilih Klasifikasi..." required>
								<option value="">Pilih Klasifikasi</option><?php
								$KlasArsip= $this->model->selectprepare("klasifikasi", $field=null, $params=null, $where=null, "ORDER BY nama ASC");
								if($KlasArsip->rowCount() >= 1){
									while($dataKlasArsip = $KlasArsip->fetch(PDO::FETCH_OBJ)){
										if(isset($id_klasifikasi) && $id_klasifikasi == $dataKlasArsip->id_klas){ ?>
											<option value="<?php echo $dataKlasArsip->id_klas;?>" selected><?php echo $dataKlasArsip->nama;?></option><?php
										}else{?>
											<option value="<?php echo $dataKlasArsip->id_klas;?>"><?php echo $dataKlasArsip->nama;?></option><?php
										}
									}
								}else{?>
									<option value="">Data klasifikasi belum ada</option><?php
								}?>
							</select>
						</div>
						<span style="padding-right: auto;"  data-placement="left" class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Pilih klasifikasi arsip." data-content="Di isi sesuai dengan nomor surat masuk yang diterima.">
							<i class="fa fa-question-circle"></i>
						</span>
					</div>
					<div class="form-group">
						<label for="inputPassword3" class="col-sm-2 control-label">Tanggal Surat</label>
						<div class="col-sm-9 date">
							<input class="form-control pull-right" id="datepicker1" data-date-format="dd-mm-yyyy" placeholder="Tanggal pada surat masuk" type="text" name="tglsm" <?php if(isset($tgl_surat)){ echo $tgl_surat; }?> id="form-field-mask-1" required/>
						</div>
						<span style="padding-right: auto;"  data-placement="left" class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Di isi sesuai dengan tanggal pada surat masuk. ex. 01-12-2015">
							<i class="fa fa-question-circle"></i>
						</span>
					</div>
					<div class="form-group">
						<label for="inputPassword3" class="col-sm-2 control-label">Tanggal Terima</label>
						<div class="col-sm-9 date">
							<input class="form-control pull-right" id="datepicker2" data-date-format="dd-mm-yyyy" placeholder="Tanggal terima surat masuk" type="text" name="tgl_terima" <?php if(isset($tgl_terima)){ echo $tgl_terima; }?>  required/>
						</div>
						<span style="padding-right: auto;"  data-placement="left" class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Di isi sesuai dengan tanggal surat masuk diterima. ex. 01-12-2015">
							<i class="fa fa-question-circle"></i>
						</span>
					</div>

					<div class="form-group">
						<label for="inputPassword3" class="col-sm-2 control-label">Retensi Arsip</label>
						<div class="col-sm-9 date">
							<input class="form-control pull-right" id="datepicker3" data-date-format="dd-mm-yyyy" placeholder="Tanggal retensi arsip surat" type="text" name="tgl_retensi" <?php if(isset($tgl_retensi)){ echo $tgl_retensi; }?>/>
						</div>
						<span style="padding-right: auto;"  data-placement="left" class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Tanggal retensi (masa berlaku) arsip surat masuk">
							<i class="fa fa-question-circle"></i>
						</span>
					</div>
					<div class="form-group">
						<label for="inputEmail3" class="col-sm-2 control-label" control-label>Surat Dari*</label>

						<div class="col-sm-9">
							<input class="form-control" placeholder="Sumber surat/pengirim" type="text" name="pengirim" <?php if(isset($pengirim)){ echo $pengirim; }?> title="Di isi sesuai sumber pengirim surat (nama lembaga atau peroranngan)" data-placement="bottom" id="form-field-mask-1" required/>
						</div>
						<span style="padding-right: auto;"  data-placement="left" class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Di isi sesuai sumber pengirim surat (nama lembaga/peroranngan)." data-content="Di isi sesuai dengan nomor surat masuk yang diterima.">
							<i class="fa fa-question-circle"></i>
						</span>
					</div>

					<div class="form-group">
						<label for="inputPassword3" class="col-sm-2 control-label">Diteruskan ke*</label>

						<div class="col-sm-9">
							<select class="select2 form-control" name="tujuan[]" multiple="" data-placeholder="Pilih user..." required style="width: 100%;">
								<?php
								$Diteruskan = $this->model->selectprepare("user a join user_jabatan b on a.jabatan=b.id_jab", $field=null, $params=null, $where=null, "ORDER BY a.nama ASC");
								if($Diteruskan->rowCount() >= 1){
									while($dataDiteruskan = $Diteruskan->fetch(PDO::FETCH_OBJ)){
										$DiteruskanSurat = $dataDiteruskan->nama ." (".$dataDiteruskan->nama_jabatan .")";
										if(false !== array_search($dataDiteruskan->id_user, $cekDiteruskan)){?>
											<option value="<?php echo $dataDiteruskan->id_user;?>" selected><?php echo $DiteruskanSurat;?></option><?php
										}else{?>
											<option value="<?php echo $dataDiteruskan->id_user;?>"><?php echo $DiteruskanSurat;?></option><?php
										}
									}								
								}else{?>
									<option value="">Not Found</option><?php
								}?>
							</select>
						</div>
						<span style="padding-right: auto;"  data-placement="left" class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Pilih tujuan Surat dieruskan (support multiple choise).">
							<i class="fa fa-question-circle"></i>
						</span>
					</div>
					<div class="form-group" style="margin-top: 30px;">
						<label for="inputEmail3" class="col-sm-2 control-label" control-label>Perihal*</label>
						<div class="col-sm-9">
							<textarea class="form-control limited"  placeholder="Perihal/subjek surat" name="perihal" id="form-field-9" maxlength="150" required><?php if(isset($perihal)){ echo $perihal; }?></textarea>
							
						</div>
						<span style="padding-right: auto;"  data-placement="left" class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Di isi sesuai perihal atau subjek surat masuk.">
							<i class="fa fa-question-circle"></i>
						</span>
					</div>

					<div class="form-group" style="margin-top: 30px;">
						<label for="inputEmail3" class="col-sm-2 control-label" control-label>Keterangan</label>
						<div class="col-sm-9">
							<textarea class="form-control limited"  placeholder="Keterangan tambahan (jika ada)" name="ket" id="form-field-9" maxlength="150"><?php if(isset($ket)){ echo $ket; }?></textarea>
							
						</div>
						<span style="padding-right: auto;"  data-placement="left" class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Di isi dengan keterangan tambahan jika ada (seperti jadwal undangan, tempat, tanggal penting dsb).">
							<i class="fa fa-question-circle"></i>
						</span>
					</div>
					<div class="form-group">
						<label for="inputEmail3" class="col-sm-2 control-label" control-label> <?php echo $ketfile;?></label>

						<div class="col-sm-9">
							<input type="file" class="form-control" name="filesm" id="id-input-file-1"/>
						</div>
						<span style="padding-right: auto;"  data-placement="left" class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Pilih File surat masuk yang ingin di upload. Caranya klik menu Pilih File. Tipe file : .pdf, .jpg, .png">
							<i class="fa fa-question-circle"></i>
						</span>
					</div>
					
					
				</div>
				<!-- /.box-body -->
				<div class="box-footer">
					<a href="./index.php?op=sm" type="reset" class="btn btn-danger">Cancel</a>
					<button type="submit" class="btn btn-info pull-right">Submit</button>
				</div>
				<!-- /.box-footer -->

			</form>
		</div>
	</div>
	<?php
}?>

