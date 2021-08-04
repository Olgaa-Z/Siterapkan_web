<?php
$params = array(':id_sk' => trim($_GET['skid']));
$sk = $this->model->selectprepare("arsip_sk a INNER JOIN user b on a.id_user=b.id_user", $field=null, $params, "a.id_sk=:id_sk", "ORDER BY tgl_surat DESC");
if($sk->rowCount() >= 1){
	$data_sk= $sk->fetch(PDO::FETCH_OBJ);
	$idsk= $data_sk->id_sk;
	if(isset($_GET['act']) && $_GET['act'] == "del"){
		@unlink('berkas/'.$data_sk->file);
		$params = array(':id_sk' => $idsk);
		$delete = $this->model->hapusprepare("arsip_sk", $params, "id_sk=:id_sk");
		if($delete){
			$cek = $this->model->selectprepare("arsip_sk", $field=null, $params=null, $where=null);
			if($cek->rowCount() <= 0){
				$delete = $this->model->truncate("arsip_sk");
			}
			echo "<script type=\"text/javascript\">alert('Data Berhasil di Hapus...!!');window.location.href=\"./index.php?op=sk\";</script>";
		}else{
			die("<script>alert('Gagal menghapus data surat keluar, Silahkan Coba Kembali..!!');window.history.go(-1);</script>");
		}
	}?>

	<section class="invoice">
		<!-- title row -->
		<div class="row">
			<div class="col-xs-12">
				<span class="blue bigger-125">
					Perihal : <?php echo $data_sk->perihal;?>
				</span>
				<h2 class="page-header">
					
					<img class="middle" alt="John's Avatar" src="assets/images/avatars/<?php echo $data_sk->picture;?>" width="32" /> <?php echo $data_sk->nama;?>
					<small class="pull-right"><i class="ace-icon fa fa-clock-o bigger-110 orange middle" style="margin-right: 10px;"></i><?php echo tgl_indo($data_sk->tgl_surat);?></small>
				</h2>
			</div>


			<!-- /.col -->
		</div>

		<div class="col-xs-10">

			<div class="table-responsive">
				<table class="table">
					<tr>
						<th style="width:50%">Tgl/No Agenda</th>
						<td>:
							<td><?php echo tgl_indo($data_sk->tgl_surat);?> | <?php echo $data_sk->custom_noagenda;?></td>

						</tr>
						<tr>
							<th>No surat</th>
							<td>:
								<td><?php echo $data_sk->no_sk;?></td>
							</tr>
							<tr>
								<th>Tujuan Surat</th>
								<td>:
									<td><?php echo $data_sk->tujuan_surat;?></td>
								</tr>
								<tr>
									<th>Perihal </th>
									<td>:
										<td><?php echo $data_sk->perihal;?></td>
									</tr>
									<tr>
										<th>Pengolah</th>
										<td>:
											<td><?php echo $data_sk->pengolah;?></td>
										</tr>
									</table>
								</div>
							</div>

							<!-- info row -->

							<!-- /.row -->

							<!-- Table row -->
							<div class="row">

							</div>
							<!-- /.row -->

							<div class="row" style="margin-bottom: 50px;">
								<!-- accepted payments column -->
								<div class="col-xs-12">
									<p class="lead">Keterangan:</p>
									<p class="lead"><h5><?php echo $data_sk->ket;?></h5></p>
								</div>
								<!-- /.col -->

								<!-- /.col -->
							</div>


							<!-- /.row -->

							<!-- this row will not appear when printing -->
							<hr>
							<div class="row no-print">
								<div class="col-xs-12">
									<?php
									if($data_sk->file != ''){?>
										<a href="./berkas/<?php echo $data_sk->file;?>" target="_blank" class="btn btn-primary"><i class="ace-icon fa fa-file-pdf-o align-top bigger-125 icon-on-right"></i> Lihat Surat</a>
										<?php
									}
									if($HakAkses->sk == "W"){?>
										<a href="./index.php?op=add_sk&skid=<?php echo $data_sk->id_sk;?>" type="button" class="btn btn-success pull-right"><i class="ace-icon fa fa-pencil align-top bigger-125 icon-on-right"></i> Edit Surat
										</a>
										<?php
									}?>
								</div>
							</div>
						</section>

						<?php
					}else{?>
						<div class="alert alert-danger">
							<button type="button" class="close" data-dismiss="alert">
								<i class="ace-icon fa fa-times"></i>
							</button>
							<p>
								<strong><i class="ace-icon fa fa-check"></i>Perhatian!</strong>
								Data Surat keluar tidak ditemukan. Terimakasih.
							</p>
							<p>
								<a href="./index.php?op=sk"><button class="btn btn-minier btn-danger">Kembali</button></a>
							</p>
							</div><?php
						}?>