<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
		<div class="row">
			<div class="col-xs-12"> <?php
			$CekEmptyFile = $this->model->selectprepare("arsip_file", $field=null, $params=null, $order=null);
			if($CekEmptyFile->rowCount() <= 0){
				$EmptyTabel = $this->model->truncate("arsip_file");
			}
			/* PAGINATION */
			$batas = 15;
			$pg = isset( $_GET['halaman'] ) ? $_GET['halaman'] : "";
			if(empty($pg)){
				$posisi = 0;
				$pg = 1;
			}else{
				$posisi = ($pg-1) * $batas;
			}
			/* END PAGINATION */
			if(isset($_GET['keyword'])){
				$keyword = "%".$_GET['keyword']."%";
				if(empty($_GET['keyword'])){
					$ArsipFile = $this->model->selectprepare("arsip_file a JOIN klasifikasi_arsip b on a.id_klasifikasi=b.id_klasifikasi", $field=null, $params=null, $where=null, "order by a.tgl_arsip DESC LIMIT $posisi, $batas");
					$ArsipFile2 = $this->model->selectprepare("arsip_file a JOIN klasifikasi_arsip b on a.id_klasifikasi=b.id_klasifikasi", $field=null, $params=null, $where=null);
				}else{
					$params = array(':ket' => $keyword, ':nama_klasifikasi' => $keyword, ':tgl_arsip' => $keyword, ':no_arsip' => $keyword, ':keamanan' => $keyword);
					$ArsipFile = $this->model->selectprepare("arsip_file a JOIN klasifikasi_arsip b on a.id_klasifikasi=b.id_klasifikasi", $field=null, $params, "a.ket LIKE :ket OR b.nama_klasifikasi LIKE :nama_klasifikasi OR a.tgl_arsip LIKE :tgl_arsip OR a.no_arsip LIKE :no_arsip OR a.keamanan LIKE :keamanan", "order by a.tgl_arsip DESC LIMIT $posisi, $batas");
					$ArsipFile2 = $this->model->selectprepare("arsip_file a JOIN klasifikasi_arsip b on a.id_klasifikasi=b.id_klasifikasi", $field=null, $params, "a.ket LIKE :ket OR b.nama_klasifikasi LIKE :nama_klasifikasi OR a.tgl_arsip LIKE :tgl_arsip OR a.no_arsip LIKE :no_arsip OR a.keamanan LIKE :keamanan", $other=null);
				}
			}else{
				$field = array("id_arsip","DATE_FORMAT(tgl_arsip, '%Y') as thn");
				$lastData = $this->model->selectprepare("arsip_file", $field, $params=null, $where=null, "GROUP BY DATE_FORMAT(tgl_arsip, '%Y') order by DATE_FORMAT(tgl_arsip, '%Y') DESC LIMIT 1");
				if($lastData->rowCount() >= 1){
					$dataLast = $lastData->fetch(PDO::FETCH_OBJ);
					if(isset($_GET['yearfile'])){
						$params = array(':year' => $_GET['yearfile']);
					}else{
						$params = array(':year' => $dataLast->thn);
					}
				}else{
					$params = array(':year' => '');
				}
				$ArsipFile = $this->model->selectprepare("arsip_file a JOIN klasifikasi_arsip b on a.id_klasifikasi=b.id_klasifikasi", $field=null, $params, "DATE_FORMAT(a.tgl_arsip, '%Y')=:year", "order by a.tgl_arsip DESC LIMIT $posisi, $batas");
			}
			if($ArsipFile->rowCount() >= 1){
				while($dataArsipFile = $ArsipFile->fetch(PDO::FETCH_OBJ)){
					$dump_ArsipFile[]=$dataArsipFile;
				}?>
				<table class="table table-striped table-bordered table-hover no-margin-bottom no-border-top">
					<thead>
						<tr>
							<th width="10">No</th>
							<th width="100">Nomor Arsip</th>
							<th width="120">Tanggal Arsip</th>
							<th>Ket</th>
							<th width="20">File</th>
							<th class="detail-col" width="40">Detail</th><?php
							if($HakAkses->arsip == "W"){?>
								<th width="80">Aksi</th><?php
							}?>
						</tr>
					</thead>
					<tbody><?php
					$no=1+$posisi;
					foreach($dump_ArsipFile as $key => $object){
						$tglFile = explode("-", $object->tgl_upload);
						$tglFile = $tglFile[2]."-".$tglFile[1]."-".$tglFile[0];
						$tgl_arsip = explode("-", $object->tgl_arsip);
						$tgl_arsip = $tgl_arsip[2]."-".$tgl_arsip[1]."-".$tgl_arsip[0];?>
						<tr>
							<td><center><?php echo $no;?></center></td>
							<td><?php echo sprintf("%04d", $object->no_arsip);?></td>
							<td><?php echo $tgl_arsip;?></td>
							<td><?php echo $object->ket;?></td>
							<td><a href="./berkas/<?php echo $object->file_arsip;?>" target="_blank"><button class="btn btn-minier btn-success">view</button></a></td>
							<td class="center">
								<div class="action-buttons">
											<!-- <a href="#" class="green bigger-140 show-details-btn" title="Show Details">
												<i class="ace-icon fa fa-angle-double-down"></i>
												<span class="sr-only">Details</span>
											</a> -->
											<button class="btn btn-minier btn-primary" type="button" data-toggle="modal" data-target="#exampleModalCenter-<?= $no?>">Detail</button>
										</div>
										</td><?php
										if($HakAkses->arsip == "W"){?>
											<td><center>
												<div class="hidden-sm hidden-xs btn-group">
													<a href="./index.php?op=add_arsip&id_arsip=<?php echo $object->id_arsip;?>">
														<button class="btn btn-minier btn-info">
															<i class="ace-icon fa fa-pencil bigger-100"></i>
														</button>
													</a>
													<a href="./index.php?op=add_arsip&id_arsip=<?php echo $object->id_arsip;?>&act=del" onclick="return confirm('Anda yakin akan menghapus data ini??')">
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
																<a href="./index.php?op=add_arsip&id_arsip=<?php echo $object->id_arsip;?>">
																	<button class="btn btn-minier btn-info">
																		<i class="ace-icon fa fa-pencil bigger-100"></i>
																	</button>
																</a>
															</li>
															<li>
																<a href="./index.php?op=add_arsip&id_arsip=<?php echo $object->id_arsip;?>&act=del" onclick="return confirm('Anda yakin akan menghapus data ini??')">
																	<button class="btn btn-minier btn-danger">
																		<i class="ace-icon fa fa-trash-o bigger-110"></i>
																	</button>
																</a>
															</li>
														</ul>
													</div>
												</div></center>
												</td><?php
											}?>
										</tr>
									<!-- 	<tr class="detail-row">
											<td colspan="12">
												<div class="table-detail">
													<div class="row">
														<div class="col-xs-12 col-sm-12">
															<div class="space visible-xs"></div>
															<div class="profile-user-info profile-user-info-striped">
																<div class="profile-info-row">
																	<div class="profile-info-name"> No Arsip </div>
																	<div class="profile-info-value"><span><?php echo sprintf("%04d", $object->no_arsip);?></span></div>
																</div>
																<div class="profile-info-row">
																	<div class="profile-info-name"> Keamanan </div>
																	<div class="profile-info-value"><span><?php echo $object->keamanan;?></span></div>
																</div>
																<div class="profile-info-row">
																	<div class="profile-info-name"> Klasifikasi </div>
																	<div class="profile-info-value"><span><?php echo $object->nama_klasifikasi;?></span></div>
																</div>
																<div class="profile-info-row">
																	<div class="profile-info-name"> Tanggal Arsip </div>
																	<div class="profile-info-value"><span><?php echo $tgl_arsip;?></span></div>
																</div>
																<div class="profile-info-row">
																	<div class="profile-info-name"> Tanggal Upload </div>
																	<div class="profile-info-value"><span><?php echo $tglFile;?></span></div>
																</div>
																<div class="profile-info-row">
																	<div class="profile-info-name"> Ket </div>
																	<div class="profile-info-value"><span><?php echo $object->ket;?></span></div>
																</div>
																<div class="profile-info-row">
																	<div class="profile-info-name"> File </div>
																	<div class="profile-info-value">
																		<span><?php 
																		if($object->file_arsip != ""){?>
																			<a href="./berkas/<?php echo $object->file_arsip;?>" target="_blank">Lihat File</a><?php
																		}else{ ?>
																			- <?php
																		}?>
																	</span>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</td>
									</tr> -->

									<div class="modal fade " id="exampleModalCenter-<?= $no?>" role="dialog" aria-labelledby="exampleModalCenterTitle">
										<div class="table-detail">
											<div class="row">
												<div class="modal-dialog modal-dialog-centered" role="document">
													<div class="modal-content">
														<div class="modal-header">
															<h3 class="modal-title" id="exampleModalLongTitle">Detail Arsip</h3>
															<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																<span aria-hidden="true">&times;</span>
															</button>
														</div>
														<div class="modal-body">

															<div class="table-detail">
																<div class="row">
																	<div class="col-xs-12 col-sm-12">
																		<div class="space visible-xs"></div>
																		<div class="profile-user-info profile-user-info-striped">
																			<ul class="list-group list-group-unbordered">
																				<li class="list-group-item">
																					<b>Nomor surat </b> <b class="pull-right"><span><?php echo sprintf("%04d", $object->no_arsip);?></span></b>
																				</li>
																				<li class="list-group-item">
																					<b>Keamanan</b> <b class="pull-right"><span><?php echo $object->keamanan;?></span></b>
																				</li>
																				<li class="list-group-item">
																					<b>Klasifikasi</b> <b class="pull-right"><span><?php echo $object->nama_klasifikasi;?></span></b>
																				</li>
																				<li class="list-group-item">
																					<b> Tanggal Arsip</b> <b class="pull-right"><span><?php echo $tgl_arsip;?></span></b>
																				</li>
																				<li class="list-group-item">
																					<b> Tanggal Upload</b> <b class="pull-right"><span><?php echo $tglFile;?></span></b>
																				</li>
																				<li class="list-group-item">
																					<b>Ket </b> <b class="pull-right"><span><?php echo $object->ket;?></span></b>
																				</li>
																				<li class="list-group-item">
																					<b> File Surat</b> <b class="pull-right"><span><?php 
																					if($object->file_arsip != ""){?>
																						<a href="./berkas/<?php echo $object->file_arsip;?>" target="_blank">Lihat File</a><?php
																					}else{ ?>
																						- <?php
																					}?>
																				</span></b>
																			</li>
																		</ul>

																	</div>
																</div>
															</div>
														</div>





													</div>
													<div class="modal-footer">
														<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
													</div>
												</div>

											</div>
										</div>
									</div>
								</div>


								<?php
								$no++;
							}?>
						</tbody>
						</table><?php
					}else{?>
						<div class="alert alert-danger">
							<button type="button" class="close" data-dismiss="alert">
								<i class="ace-icon fa fa-times"></i>
							</button>
							<p>
								<strong><i class="ace-icon fa fa-check"></i>Perhatian!</strong>
								Data Arsip belum ada. Terimakasih.
							</p>
							</div><?php
						}
						/* PAGINATION */
				//hitung jumlah data
						if(isset($_GET['keyword'])){
							$jml_data = $ArsipFile2->rowCount();
							$link_order="&keyword=$_GET[keyword]";
						}else{
							if(isset($_GET['yearfile'])){
								$params = array(':year' => $_GET['yearfile']);
								$link_order="&yearfile=$_GET[yearfile]";
							}else{
								if($lastData->rowCount() >= 1){
									$params = array(':year' => $dataLast->thn);
									$link_order="";
								}
							}
							$jlhdata = $this->model->selectprepare("arsip_file a JOIN klasifikasi_arsip b on a.id_klasifikasi=b.id_klasifikasi", $field=null, $params, "DATE_FORMAT(a.tgl_arsip, '%Y')=:year", $other=null);
							$jml_data = $jlhdata->rowCount();
						}
				//Jumlah halaman
						$JmlHalaman = ceil($jml_data/$batas); 
				//Navigasi ke sebelumnya
						if($pg > 1){
							$link = $pg-1;
							$prev = "index.php?op=arsip_file&halaman=$link$link_order";
							$prev_disable = " ";
						}else{
							$prev = "#";
							$prev_disable = "disabled";
						}
				//Navigasi ke selanjutnya
						if($pg < $JmlHalaman){
							$link = $pg + 1;
							$next = "index.php?op=arsip_file&halaman=$link$link_order";
							$next_disable = " ";
						}else{
							$next = "#";
							$next_disable = "disabled";
						}
						if($batas < $jml_data){?>
							<ul class="pager">
								<li class="previous <?php echo $prev_disable;?>"><a href="<?php echo $prev;?>">&larr; Sebelumnya </a></li>
								<li class="next <?php echo $next_disable;?>"><a href="<?php echo $next;?>">Selanjutnya &rarr;</a></li>
							</ul>
							<span class="text-muted">Halaman <?php echo $pg;?> dari <?php echo $JmlHalaman;?> (Total : <?php echo $jml_data;?> records)</span> <?php
						}
						/* END PAGINATION */ ?>
					</div><!-- /.span -->
				</div><!-- /.row -->
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