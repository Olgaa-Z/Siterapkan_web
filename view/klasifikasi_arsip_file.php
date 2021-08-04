<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
		<div class="row">
			<div class="col-xs-12"> <?php
			if ($_SERVER["REQUEST_METHOD"] == "POST"){
				$nama = htmlspecialchars($purifier->purify(trim($_POST['nama'])), ENT_QUOTES);
				$tgl = date("Y-m-d H:i:s", time());
				if(isset($_GET['id_klasifikasi'])){
					$id_klasifikasi = htmlspecialchars($purifier->purify(trim($_GET['id_klasifikasi'])), ENT_QUOTES);
					$params = array(':id_klasifikasi' => $id_klasifikasi);
					$Klasifikasi = $this->model->selectprepare("klasifikasi_arsip", $field=null, $params, "id_klasifikasi=:id_klasifikasi");
					if($Klasifikasi->rowCount() >= 1){
						$data_Klasifikasi= $Klasifikasi->fetch(PDO::FETCH_OBJ);
						$idklasifikasi = $data_Klasifikasi->id_klasifikasi;
						$field = array('nama_klasifikasi' => $nama);
						$params = array(':id_klasifikasi' => $id_klasifikasi);
						$update = $this->model->updateprepare("klasifikasi_arsip", $field, $params, "id_klasifikasi=:id_klasifikasi");
						if($update){
							echo "<script type=\"text/javascript\">alert('Data Berhasil diperbaharui...!!');window.location.href=\"./index.php?op=klasifikasi_file\";</script>";
						}else{
							die("<script>alert('Data menyimpan ke Database, Silahkan Coba Kembali..!!');window.history.go(-1);</script>");
						}
					}
				}else{
					$field = array('nama_klasifikasi' => $nama, 'created'=>$tgl);
					$params = array(':nama_klasifikasi'=> $nama, ':created'=>$tgl);
					$insert = $this->model->insertprepare("klasifikasi_arsip", $field, $params);
					if($insert->rowCount() >= 1){
						echo "<script type=\"text/javascript\">alert('Data Berhasil Tersimpan...!!');window.location.href=\"./index.php?op=klasifikasi_file\";</script>";
					}else{
						die("<script>alert('Data Gagal di simpan ke Database, Silahkan Coba Kembali..!!');window.history.go(-1);</script>");
					}
				}
			}else{
				if(isset($_GET['id_klasifikasi']) && empty($_GET['act'])){
					$id_klasifikasi = htmlspecialchars($purifier->purify(trim($_GET['id_klasifikasi'])), ENT_QUOTES);
					$params = array(':id_klasifikasi' => $id_klasifikasi);
					$cek_klas = $this->model->selectprepare("klasifikasi_arsip", $field=null, $params, "id_klasifikasi=:id_klasifikasi");
					if($cek_klas->rowCount() >= 1){
						$data_cek_klas = $cek_klas->fetch(PDO::FETCH_OBJ);
						$title= "Edit Data Klasifikasi Arsip File";
						$nama = 'value="'.$data_cek_klas->nama_klasifikasi .'"';
					}else{
						$title= "Entri Data Klasifikasi Arsip File";
					}
				}else{
					$title= "Entri Data Klasifikasi Arsip File";
				}
				if(isset($_GET['id_klasifikasi']) && (isset($_GET['act']) && $_GET['act'] == "del")){
					$id_klasifikasi = htmlspecialchars($purifier->purify(trim($_GET['id_klasifikasi'])), ENT_QUOTES);
					$params = array(':id_klasifikasi' => $id_klasifikasi);
					$lihat_sm = $this->model->selectprepare("arsip_file", $field=null, $params, "id_klasifikasi=:id_klasifikasi");
					if($lihat_sm->rowCount() >= 1){
						die("<script>alert('Nama klasifikasi ini tidak dapat dihapus karena terkait dengan data file arsip. Jika tetap ingin menghapus, silahkan hapus data file arsip terkait terlebih dahulu. Terimakasih');window.history.go(-1);</script>");
					}else{
						$params = array(':id_klasifikasi' => $id_klasifikasi);
						$delete = $this->model->hapusprepare("klasifikasi_arsip", $params, "id_klasifikasi=:id_klasifikasi");
						if($delete){
							echo "<script type=\"text/javascript\">alert('Data Berhasil di Hapus...!!');window.location.href=\"./index.php?op=klasifikasi_file\";</script>";
						}else{
							die("<script>alert('Gagal menghapus data klasifikasi, Silahkan Coba Kembali..!!');window.history.go(-1);</script>");
						}
					}
				}?>
				<div class="box-body">
					<div class="row">

						<div class="col-md-12">
							<div class="box box-primary">
								<div class="box-header">
									<h3 class="box-title"><?php echo $title;?></h3>
								</div>
								<div class="box-body">

									<div class="widget-body">
										<div class="widget-main">
											<form class="form-horizontal" role="form" method="POST" name="formku" action="<?php echo $_SESSION['url'];?>">
												<div class="form-group">
													<label class="col-sm-2 control-label no-padding-right" for="form-field-mask-1"> Nama Klasifikasi*</label>
													<span style="padding-right: auto;"  data-placement="left" class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Di isi dengan nama/ket klasifikasi surat" >
														<i class="fa fa-question-circle"></i>
													</span>
													<div class="col-sm-4">
														<input class="form-control" placeholder="Nama/ket klasifikasi surat" type="text" name="nama" <?php if(isset($nama)){ echo $nama; }?> id="form-field-mask-1" required/>
													</div>
												</div>
												<div class="clearfix form-actions">
													<div class="col-md-offset-3 col-md-9">
														<div class="col-sm-2">
															<button type="submit" class="btn btn-info" type="button">
																<i class="ace-icon fa fa-check bigger-110"></i>
																Submit
															</button>
														</div>
													</div>
												</div>
											</form>
										</div>
									</div>
								</div>
								<!-- /.box-body -->
							</div>
							<!-- /.box -->
						</div>
					</div>
				</div>
				<!-- end end end -->
				

				<div class="space-4"></div>
				<div class="widget-box"><?php
				$GetKlasifikasi = $this->model->selectprepare("klasifikasi_arsip", $field=null, $params=null, $where=null, "order by nama_klasifikasi ASC");
				if($GetKlasifikasi->rowCount() >= 1){
					while($data_GetKlasifikasi = $GetKlasifikasi->fetch(PDO::FETCH_OBJ)){
						$dump_klasifikasi[]=$data_GetKlasifikasi;
					}?>
					<div class="widget-body">
						<div class="widget-main">
							<table class="table table-striped table-bordered table-hover no-margin-bottom no-border-top">
								<thead>
									<tr>
										<th width="10">No</th>
										<th>Nama/Ket klasifikasi</th>
										<th width="80">Aksi</th>
									</tr>
								</thead>
								<tbody><?php
								$no=1;
								foreach($dump_klasifikasi as $key => $object){?>
									<tr>
										<td><?php echo $no;?></td>
										<td><?php echo $object->nama_klasifikasi;?></td>
										<td><center>
											<div class="hidden-sm hidden-xs btn-group">
												<a href="./index.php?op=klasifikasi_file&id_klasifikasi=<?php echo $object->id_klasifikasi;?>">								
													<button class="btn btn-minier btn-info">
														<i class="ace-icon fa fa-pencil bigger-100"></i>
													</button>
												</a>
												<a href="./index.php?op=klasifikasi_file&id_klasifikasi=<?php echo $object->id_klasifikasi;?>&act=del" onclick="return confirm('Anda yakin akan menghapus data ini??')">
													<button class="btn btn-minier btn-danger">
														<i class="ace-icon fa fa-trash-o bigger-110"></i>
													</button>
												</a>
											</div>
											<div class="hidden-md hidden-lg">
												<div class="inline pos-rel">
													<button class="btn btn-minier btn-primary dropdown-toggle" data-toggle="dropdown" data-position="auto">
														<i class="ace-icon fa fa-cog icon-only bigger-110"></i>
													</button>
													<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
														<li>
															<a href="./index.php?op=klasifikasi_file&id_klasifikasi=<?php echo $object->id_klasifikasi;?>">								
																<button class="btn btn-minier btn-info">
																	<i class="ace-icon fa fa-pencil bigger-100"></i>
																</button>
															</a>
														</li>
														<li>
															<a href="./index.php?op=klasifikasi_file&id_klasifikasi=<?php echo $object->id_klasifikasi;?>&act=del" onclick="return confirm('Anda yakin akan menghapus data ini??')">
																<button class="btn btn-minier btn-danger">
																	<i class="ace-icon fa fa-trash-o bigger-110"></i>
																</button>
															</a>
														</li>
													</ul>
												</div>
											</div></center>
										</td>
										</tr><?php
										$no++;
									}?>
								</tbody>
							</table>
						</div>
						</div><?php
					}?>
					</div><?php
				}?>
			</div><!-- /.span -->
		</div><!-- /.row -->
		<!-- PAGE CONTENT ENDS -->
	</div><!-- /.col -->
</div><!-- /.row -->

<script src="assets/js/jquery-2.1.4.min.js"></script>

<!-- page specific plugin scripts -->
<script src="assets/js/jquery.dataTables.min.js"></script>
<script src="assets/js/jquery.dataTables.bootstrap.min.js"></script>
<script src="assets/js/dataTables.buttons.min.js"></script>
<script src="assets/js/buttons.flash.min.js"></script>
<script src="assets/js/buttons.html5.min.js"></script>
<script src="assets/js/buttons.print.min.js"></script>
<script src="assets/js/buttons.colVis.min.js"></script>
<script src="assets/js/dataTables.select.min.js"></script>

<!-- inline scripts related to this page -->
<script type="text/javascript">
	jQuery(function($) {				
		//select/deselect a row when the checkbox is checked/unchecked
		$('#simple-table').on('click', 'td input[type=checkbox]' , function(){
			var $row = $(this).closest('tr');
			if($row.is('.detail-row ')) return;
			if(this.checked) $row.addClass(active_class);
			else $row.removeClass(active_class);
		});	
		
		/***************/
		$('.show-details-btn').on('click', function(e) {
			e.preventDefault();
			$(this).closest('tr').next().toggleClass('open');
			$(this).find(ace.vars['.icon']).toggleClass('fa-angle-double-down').toggleClass('fa-angle-double-up');
		});
		/***************/			
	})
</script>