<?php
if ($_SERVER["REQUEST_METHOD"] == "POST"){
	$noagenda = htmlspecialchars($purifier->purify(trim($_POST['noagenda'])), ENT_QUOTES);
	$noagenda_custom = trim($_POST['noagenda_custom']);
	$nosk = htmlspecialchars($purifier->purify(trim($_POST['nosk'])), ENT_QUOTES);
	$tglsk = htmlspecialchars($purifier->purify(trim($_POST['tglsk'])), ENT_QUOTES);
	$tglsk = explode("-",$tglsk);
	$tglskdb = $tglsk[2]."-".$tglsk[1]."-".$tglsk[0];
	$pengolah = htmlspecialchars($purifier->purify(trim($_POST['pengolah'])), ENT_QUOTES);
	$klasifikasi = htmlspecialchars($purifier->purify(trim($_POST['id_klasifikasi'])), ENT_QUOTES);
	$tujuan = htmlspecialchars($purifier->purify(trim($_POST['tujuan'])), ENT_QUOTES);
	$perihal = htmlspecialchars($purifier->purify(trim($_POST['perihal'])), ENT_QUOTES);
	$ket = htmlspecialchars($purifier->purify(trim($_POST['ket'])), ENT_QUOTES);
	
	$fileName = htmlspecialchars($_FILES['filesk']['name'], ENT_QUOTES);
	$tipefile = pathinfo($fileName,PATHINFO_EXTENSION);
	$extensionList = array("pdf","jpg","jpeg","png","PNG", "JPG", "JPEG","PDF");
	$namaDir = 'berkas/';
	$filesk = $namaDir."SK"."_".$tglskdb."_". slugify($perihal)."_". date("d-m-Y_H-i-s", time()) .".".$tipefile;
	if(empty($fileName)){
		$filedb = "";
	}else{
		$filedb = "SK"."_".$tglskdb."_". slugify($perihal)."_". date("d-m-Y_H-i-s", time()) .".".$tipefile;
	}
	$tgl_upload = date("Y-m-d H:i:s", time());
	
	//echo "$filesk <br/>";
	//print_r($_POST);
	if(isset($_GET['skid'])){
		$skid = htmlspecialchars($purifier->purify(trim($_GET['skid'])), ENT_QUOTES);
		$params = array(':id_sk' => $skid);
		$lihat_sk = $this->model->selectprepare("arsip_sk", $field=null, $params, "id_sk=:id_sk");
		if($lihat_sk->rowCount() >= 1){
			$data_lihat_sk = $lihat_sk->fetch(PDO::FETCH_OBJ);
			$idsk = $data_lihat_sk->id_sk;
			if(empty($fileName)){
				//echo "No Update File";
				$field = array('no_sk' => $nosk, 'klasifikasi' => $klasifikasi, 'tgl_surat' => $tglskdb, 'pengolah' => $pengolah, 'tujuan_surat' => $tujuan, 'perihal' => $perihal, 'no_agenda' => $noagenda, 'custom_noagenda' => $noagenda_custom, 'ket' => $ket);
			}else{
				if(in_array($tipefile, $extensionList)){
					@unlink($namaDir.$data_lihat_sk->file);
					$field = array('no_sk' => $nosk, 'klasifikasi' => $klasifikasi, 'tgl_surat' => $tglskdb, 'pengolah' => $pengolah, 'tujuan_surat' => $tujuan, 'perihal' => $perihal, 'no_agenda' => $noagenda, 'custom_noagenda' => $noagenda_custom, 'ket' => $ket, 'file' => $filedb);
					move_uploaded_file($_FILES['filesk']['tmp_name'], $filesk);
				}else{
					echo "<script type=\"text/javascript\">alert('File gagal di Upload, Format file tidak di dukung!!!');window.location.href=\"./index.php?op=add_sk&skid=$idsk\";</script>";
				}
			}
			$params = array(':id_sk' => $idsk);
			$update = $this->model->updateprepare("arsip_sk", $field, $params, "id_sk=:id_sk");
			if($update){
				echo "<script type=\"text/javascript\">alert('Data Berhasil diperbaharui...!!');window.location.href=\"./index.php?op=sk&skid=$idsk\";</script>";
			}else{
				die("<script>alert('Data menyimpan ke Database, Silahkan Coba Kembali..!!');window.history.go(-1);</script>");
			}
		}
	}else{
		$params = array(':no_sk' => $nosk);
		$cek_nosk = $this->model->selectprepare("arsip_sk", $field=null, $params, "no_sk=:no_sk");
		if($cek_nosk->rowCount() <= 0){
			$field = array('id_user' => $_SESSION['id_user'], 'no_sk'=>$nosk, 'klasifikasi' => $klasifikasi, 'tgl_surat'=>$tglskdb, 'pengolah'=>$pengolah, 'tujuan_surat'=>$tujuan, 'perihal'=>$perihal, 'no_agenda' => $noagenda, 'custom_noagenda' => $noagenda_custom, 'ket'=>$ket, 'file'=>$filedb, 'created'=>$tgl_upload);
			$params = array(':id_user' => $_SESSION['id_user'], ':no_sk'=>$nosk, ':klasifikasi' => $klasifikasi, ':tgl_surat'=>$tglskdb, ':pengolah'=>$pengolah, ':tujuan_surat'=>$tujuan, ':perihal'=>$perihal, ':no_agenda' => $noagenda, ':custom_noagenda' => $noagenda_custom, ':ket'=>$ket, ':file'=>$filedb, ':created'=>$tgl_upload);
			if(empty($fileName)){
				$insert = $this->model->insertprepare("arsip_sk", $field, $params);
				if($insert->rowCount() >= 1){
					echo "<script type=\"text/javascript\">alert('Data Berhasil Tersimpan...!!');window.location.href=\"$_SESSION[url]\";</script>";
				}else{
					die("<script>alert('Data Gagal di simpan ke Database, Silahkan Coba Kembali..!!');window.history.go(-1);</script>");
				}
			}else{
				if(in_array($tipefile, $extensionList)){
					if(move_uploaded_file($_FILES['filesk']['tmp_name'], $filesk)){
						$insert = $this->model->insertprepare("arsip_sk", $field, $params);
						if($insert->rowCount() >= 1){
							echo "<script type=\"text/javascript\">alert('Data Berhasil Tersimpan...!!');window.location.href=\"$_SESSION[url]\";</script>";
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
		}else{
			echo "<script type=\"text/javascript\">alert('PERHATIAN..! Nomor Surat Keluar yang dimasukkan sudah pernah terdata di Sistem. Silahkan Ulangi.');window.history.go(-1);</script>";
		}
	}
}else{
	if(isset($_GET['skid'])){
		$skid = htmlspecialchars($purifier->purify(trim($_GET['skid'])), ENT_QUOTES);
		$params = array(':id_sk' => $skid);
		$cek_sk = $this->model->selectprepare("arsip_sk", $field=null, $params, "id_sk=:id_sk");
		if($cek_sk->rowCount() >= 1){
			$data_sk = $cek_sk->fetch(PDO::FETCH_OBJ);
			$title= "Edit Data Surat Keluar";
			$ketfile = "File Surat ";
			$pengolah = 'value="'.$data_sk->pengolah .'"';
			$nosk = 'value="'.$data_sk->no_sk .'"';
			$noagenda = $data_sm->no_agenda;
			$id_klasifikasi = $data_sk->klasifikasi;
			$tgl_surat = explode("-", $data_sk->tgl_surat);
			$tgl_surat = $tgl_surat[2]."-".$tgl_surat[1]."-".$tgl_surat[0];
			$tgl_surat = 'value="'.$tgl_surat.'"';
			$tujuan_surat = 'value="'.$data_sk->tujuan_surat .'"';
			$perihal = $data_sk->perihal;
			$ket = $data_sk->ket;
		}else{
			$title= "Entri Surat Keluar";
			$ketfile = "File Surat";
			$validasifile = "required";
		}
	}else{
		$title= "Entri Surat Keluar";
		$ketfile = "File Surat";
	}

	$params = array(':id' => 1);
	$cek_noagenda_custom = $this->model->selectprepare("pengaturan", $field=null, $params, "id=:id")->fetch(PDO::FETCH_OBJ);

	$cek_noaagenda = $this->model->selectprepare("arsip_sk", $field=null, $params=null, $where=null, "ORDER BY id_sk DESC LIMIT 1");
	if($cek_noaagenda->rowCount() >= 1){
		$data_cek_noaagenda = $cek_noaagenda->fetch(PDO::FETCH_OBJ);
		if(isset($data_sk->no_agenda)){
			$noAgenda = sprintf("%04d", $data_sk->no_agenda);
			$nonoAgendaShow = $data_sk->no_agenda;
		}elseif(isset($data_cek_noaagenda->no_agenda)){
			$thn_data_surat = substr($data_cek_noaagenda->created,0,4);
			if($thn_data_surat == date('Y')){
				$noAgenda = sprintf("%04d", $data_cek_noaagenda->no_agenda+1);
				$nonoAgendaShow = $data_cek_noaagenda->no_agenda+1;
			}else{
				if($cek_noagenda_custom->no_agenda_sk_start != ''){
					$noAgenda = sprintf("%04d", $cek_noagenda_custom->no_agenda_sk_start);
					$nonoAgendaShow = $cek_noagenda_custom->no_agenda_sk_start;
				}else{
					$noAgenda = sprintf("%04d", 1);
					$nonoAgendaShow = 1;
				}
			}
		}else{
			if($cek_noagenda_custom->no_agenda_sk_start != ''){
				$noAgenda = sprintf("%04d", $cek_noagenda_custom->no_agenda_sk_start);
				$nonoAgendaShow = $cek_noagenda_custom->no_agenda_sk_start;
			}else{
				$noAgenda = sprintf("%04d", 1);
				$nonoAgendaShow = 1;
			}
		}
	}else{
		if($cek_noagenda_custom->no_agenda_sk_start != ''){
			$noAgenda = sprintf("%04d", $cek_noagenda_custom->no_agenda_sk_start);
			$nonoAgendaShow = $cek_noagenda_custom->no_agenda_sk_start;
		}else{
			$noAgenda = sprintf("%04d", 1);
			$nonoAgendaShow = 1;
		}
	}?>

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
						<label for="inputEmail3" class="col-sm-2 control-label" control-label>No Agenda*</label>
						<div class="col-sm-9">
							<?php 
							$params = array(':id' => 1);
							$cek_noagenda_custom = $this->model->selectprepare("pengaturan", $field=null, $params, "id=:id")->fetch(PDO::FETCH_OBJ);
							if($cek_noagenda_custom->no_agenda_sk != ''){
								$noAgenda = $noAgenda."/$cek_noagenda_custom->no_agenda_sk";								
							} ?>
							<input class="form-control" id="inputEmail3" readonly="" placeholder="Nomor Agenda Surat" type="text" name="noagenda" <?php if(isset($noAgenda)){ echo 'value="'.$noAgenda .'"'; } ?>  />
							<input type="hidden" name="noagenda" value="<?php echo $nonoAgendaShow;?>"/>
							<input type="hidden" name="noagenda_custom" value="<?php echo $noAgenda;?>"/>
							<?php  ?>
						</div>
						<span style="padding-right: auto;"  data-placement="left" class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Di isi sesuai dengan nomor agenda surat keluar.">
							<i class="fa fa-question-circle"></i>
						</span>
					</div>
					<div class="form-group">
						<label for="inputEmail3" class="col-sm-2 control-label" control-label>No Surat*</label>

						<div class="col-sm-9">
							<input class="form-control" placeholder="Nomor surat keluar" type="text" name="nosk" <?php if(isset($nosk)){ echo $nosk; }?> id="form-field-mask-1" required/>
						</div>
						<span style="padding-right: auto;"  data-placement="left" class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Di isi sesuai dengan nomor surat keluar." >
							<i class="fa fa-question-circle"></i>
						</span>
					</div>
					<div class="form-group">
						<label for="inputEmail3" class="col-sm-2 control-label">Klasifikasi Surat *</label>

						<div class="col-sm-9">
							<select class="form-control" id="form-field-select-3" name="id_klasifikasi" data-placeholder="Pilih Klasifikasi..." required>
								<option value="">Pilih Klasifikasi</option><?php
								$KlasArsip= $this->model->selectprepare("klasifikasi_sk", $field=null, $params=null, $where=null, "ORDER BY nama ASC");
								if($KlasArsip->rowCount() >= 1){
									while($dataKlasArsip = $KlasArsip->fetch(PDO::FETCH_OBJ)){
										if(isset($id_klasifikasi) && $id_klasifikasi == $dataKlasArsip->id_klas){?>
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
						<span style="padding-right: auto;"  data-placement="left" class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Pilih klasifikasi arsip.">
							<i class="fa fa-question-circle"></i>
						</span>
					</div>
					<div class="form-group">
						<label for="inputPassword3" class="col-sm-2 control-label">Tanggal Surat*</label>
						<div class="col-sm-9 date">
							<input class="form-control pull-right" id="datepicker1" data-date-format="dd-mm-yyyy" placeholder="Tanggal surat keluar" type="text" name="tglsk" <?php if(isset($tgl_surat)){ echo $tgl_surat; }?> id="form-field-mask-1" required/>
						</div>
						<span style="padding-right: auto;"  data-placement="left" class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Di isi sesuai dengan tanggal pada surat keluar. ex. 01-12-2015">
							<i class="fa fa-question-circle"></i>
						</span>
					</div>
					<div class="form-group">
						<label for="inputEmail3" class="col-sm-2 control-label" control-label>Pengolah *</label>

						<div class="col-sm-9">
							<input class="form-control" placeholder="Nama atau bagian pengolah surat" type="text" name="pengolah" <?php if(isset($pengolah)){ echo $pengolah; }?> title="Di isi dengan nama atau bagian yang mengolah surat(nama perorangan/bagian)." data-placement="bottom" id="form-field-mask-1" required/>
						</div>
						<span style="padding-right: auto;"  data-placement="left" class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Di isi dengan nama/bagian pengolah surat(nama perorangan/bagian).">
							<i class="fa fa-question-circle"></i>
						</span>
					</div>

					<div class="form-group">
						<label for="inputPassword3" class="col-sm-2 control-label">Tujuan Surat *</label>
						<div class="col-sm-9">
							<input class="form-control" placeholder="Nama lembaga / Perorangan" type="text" name="tujuan" <?php if(isset($tujuan_surat)){ echo $tujuan_surat; }?> data-placement="bottom" id="form-field-mask-1" required/>
						</div>
						<span style="padding-right: auto;"  data-placement="left" class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Di isi sesuai dengan tujuan surat (nama lembaga atau perorangan).">
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
						<span style="padding-right: auto;"  data-placement="left" class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Di isi dengan keterangan tambahan jika ada.">
							<i class="fa fa-question-circle"></i>
						</span>
					</div>
					<div class="form-group">
						<label for="inputEmail3" class="col-sm-2 control-label" control-label> <?php echo $ketfile;?></label>

						<div class="col-sm-9">
							<input class="form-control" type="file" name="filesk" id="id-input-file-1"/>
						</div>
						<span style="padding-right: auto;"  data-placement="left" class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Pilih File surat keluar yang ingin di upload. Caranya klik menu Pilih File. Tipe file : .pdf, .jpg, .png">
							<i class="fa fa-question-circle"></i>
						</span>
					</div>
					
					
				</div>
				<!-- /.box-body -->
				<div class="box-footer">
					<a href="./index.php?op=sk"  type="reset" class="btn btn-danger">Cancel</a>
					<button type="submit" class="btn btn-info pull-right">Submit</button>
				</div>
				<!-- /.box-footer -->

			</form>
		</div>
	</div>



<?php
}?>