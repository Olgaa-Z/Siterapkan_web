<?php
$params = array(':id_sm' => trim($_GET['memoid']));
$userLike = "'%\"$_SESSION[id_user]\"%'";
$memo = $this->model->selectprepare("arsip_sm", $field=null, $params, "id_sm=:id_sm", "AND tujuan_surat LIKE $userLike");
if($memo->rowCount() >= 1){
	$data_memo = $memo->fetch(PDO::FETCH_OBJ);
	$CekUser = $this->model->selectprepare("user", $field=null, $params=null, $where=null, "WHERE id_user = '$_SESSION[id_user]'");
	$DataUser = $CekUser->fetch(PDO::FETCH_OBJ);
	if(isset($DataUser->rule_disposisi) == '' OR $DataUser->rule_disposisi == "null"){
		$dummy_arr = '[""]';
		$RuleDisposisi = json_decode($dummy_arr, true);
	}else{
		$RuleDisposisi = json_decode($DataUser->rule_disposisi, true);
	}
	
	$tgl_memo = substr($data_memo->tgl_terima,0,10);
	$params = array(':id_user' => $_SESSION['id_user'], ':id_sm' => $data_memo->id_sm, ':kode' => 'SM');
	$lihat_sm = $this->model->selectprepare("surat_read", $field=null, $params, "id_sm=:id_sm AND id_user=:id_user AND kode=:kode");
	if($lihat_sm->rowCount() <= 0){
		$field = array('id_user' => $_SESSION['id_user'], 'id_sm' => $data_memo->id_sm, 'kode' => 'SM');
		$insert2 = $this->model->insertprepare("surat_read", $field, $params);
	}
	$params = array(':id_user' => $_SESSION['id_user'], ':id_sm' => $data_memo->id_sm);
	$cekDisposisi = $this->model->selectprepare("memo", $field=null, $params, "id_user=:id_user AND id_sm=:id_sm");
	$dataDisposisi= $cekDisposisi->fetch(PDO::FETCH_OBJ);
	
	if($cekDisposisi->rowCount() >= 1){
		if($dataDisposisi->tembusan == '' OR $dataDisposisi->tembusan == "null"){
			$dummy_arr = '[""]';
			$cekTembusan = json_decode($dummy_arr, true);
			//echo "dddd".$dataDisposisi->tembusan;
		}else{
			$cekTembusan = json_decode($dataDisposisi->tembusan, true);
		}
	}else{
		$dummy_arr = '[""]';
		$cekTembusan = json_decode($dummy_arr, true);
	}
	
	
	$ListUser = $this->model->selectprepare("user a join user_jabatan b on a.jabatan=b.id_jab", $field=null, $params=null, $where=null, "WHERE a.id_user != '$_SESSION[id_user]' ORDER BY a.nama ASC");
	while($dataListUser = $ListUser->fetch(PDO::FETCH_OBJ)){
		$dumpListUser[] = $dataListUser;
	}?>
	<div class="widget-box">
		<div class="message-header clearfix">
			<div class="pull-left" style="padding:0 9px;">
				<span class="blue bigger-125"> Surat Masuk: <?php echo $data_memo->pengirim;?>, Ref: <?php echo $data_memo->custom_noagenda;?></span>
				<div class="space-4"></div>
				<img class="middle" alt="<?php echo $DataUser->nama;?>" src="assets/images/avatars/<?php echo $DataUser->picture;?>" width="32" />
				<a href="#" class="sender"><?php echo $DataUser->nama;?></a>
				<i class="ace-icon fa fa-clock-o bigger-110 orange middle"></i>
				<span class="time grey"><?php echo tgl_indo($tgl_memo);?></span>
			</div>
		</div>
		<div class="hr hr-double"></div>
		<div class="message-body">
			<p>
				Tgl terima/No agenda: <br/><b><?php echo tgl_indo($data_memo->tgl_terima);?> | <?php echo $data_memo->custom_noagenda;?></b>
			</p>
			<p>
				Dari: <br/><b><?php echo $data_memo->pengirim;?></b>
			</p>
			<p>
				Tgl/No surat: <br/><b><?php echo tgl_indo($data_memo->tgl_surat);?> | <?php echo $data_memo->no_sm;?></b>
			</p>
			<p>
				Perihal: <br/><b><?php echo $data_memo->perihal;?></b>
			</p>
			<?php
			if($data_memo->file != ''){?>
				<p>
					<a href="./berkas/<?php echo $data_memo->file;?>" target="_blank"><button class="btn btn-success btn-minier ">Lihat File Surat<i class="ace-icon fa fa-book align-top bigger-125 icon-on-right"></i></button></a>
				</p><?php
			}?>			
			<p>
				Retensi surat / Jatuh tempo : <br/><b><?php echo ($data_memo->tgl_retensi == '0000-00-00' ? '-' : tgl_indo($data_memo->tgl_retensi));?></b>
			</p>
			<p>
				Detail Surat:<br/>
				<span class="label label-xs label-primary label-white middle">
					<a href="./index.php?op=memoprint&memoid=<?php echo $data_memo->id_sm;?>" target="_blank"><b>Lihat</b></a>
				</span>
				<span class="label label-xs label-danger label-white middle">
					<a href="./index.php?op=memoprint&memoid=<?php echo $data_memo->id_sm;?>&act=pdf" target="_blank"><b>Cetak</b> <i class="ace-icon fa fa-file-pdf-o align-top bigger-125 icon-on-right"></i></a>
				</span>
			</p>
			<hr/>
		</div>
	</div><?php
}else{?>
	<div class="alert alert-danger">
		<button type="button" class="close" data-dismiss="alert">
			<i class="ace-icon fa fa-times"></i>
		</button>
		<p>
			<strong><i class="ace-icon fa fa-check"></i>Perhatian!!!</strong>
			Belum ada data. Terimakasih.
		</p>
	</div><?php	
}
?>