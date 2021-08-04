<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
		<div class="row">
			<div class="col-xs-12"> <?php
			if(isset($_GET['skid'])){
				require_once "view_sk_detail.php";
			}else{
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
					$params = array(':no_sk' => $keyword, ':pengolah' => $keyword, ':tujuan_surat' => $keyword, ':perihal' => $keyword);
					$arsip_sk = $this->model->selectprepare("arsip_sk", $field=null, $params, "no_sk LIKE :no_sk OR pengolah LIKE :pengolah 
						OR tujuan_surat LIKE :tujuan_surat OR perihal LIKE :perihal", "order by tgl_surat DESC LIMIT $posisi, $batas");
					$arsip_sk2 = $this->model->selectprepare("arsip_sk", $field=null, $params, "no_sk LIKE :no_sk OR pengolah LIKE :pengolah 
						OR tujuan_surat LIKE :tujuan_surat OR perihal LIKE :perihal", $other=null);
				}else{
					$field = array("id_sk","DATE_FORMAT(tgl_surat, '%Y') as thn");
					$lastData = $this->model->selectprepare("arsip_sk", $field, $params=null, $where=null, "GROUP BY DATE_FORMAT(tgl_surat, '%Y') order by DATE_FORMAT(tgl_surat, '%Y') DESC LIMIT 1");
					$dataLast = $lastData->fetch(PDO::FETCH_OBJ);
					if(isset($_GET['yearsk'])){
						$params = array(':year' => $_GET['yearsk']);
					}else{
						$params = array(':year' => $dataLast->thn);
					}
					$arsip_sk = $this->model->selectprepare("arsip_sk", $field=null, $params, "DATE_FORMAT(tgl_surat, '%Y')=:year", "order by tgl_surat DESC LIMIT $posisi, $batas");
				}
				if($arsip_sk->rowCount() >= 1){
					while($data_sk = $arsip_sk->fetch(PDO::FETCH_OBJ)){
						$dump_sk[]=$data_sk;
					}?>
					<!--Modal Preview PDF-->
					<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
						<div class="modal-dialog modal-lg">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
									<h4 class="modal-title" id="myModalLabel">Preview Surat Keluar</h4>
								</div>
								<div class="modal-body" style="height: 450px;"></div>
								<div class="modal-footer">
									<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
								</div>
							</div>
						</div>
					</div>
					<!--Modal Preview PDF-->
					<table id="simple-table" class="table  table-bordered table-hover">
						<thead>
							<tr>
								<th width="50">No</th>
								<th width="200">No Agenda</th>
								<th width="100">No Surat</th>
								<th width="120">Tujuan</th>
								<th>Perihal</th>
								<th width="100">Tgl Surat</th>
								<th class="detail-col" width="40">Detail</th>
								<th width="100">Aksi</th>
							</tr>
						</thead>
						<tbody><?php
						$no=1+$posisi;
						foreach($dump_sk as $key => $object){
							$tglsrt = explode("-", $object->tgl_surat);
							$tglsrt = $tglsrt[2]."-".$tglsrt[1]."-".$tglsrt[0];
							$CekKlasifikasi = $this->model->selectprepare("klasifikasi_sk", $field=null, $params=null, $where=null, "WHERE id_klas='$object->klasifikasi'");
							$ViewKlasifikasi = $CekKlasifikasi->fetch(PDO::FETCH_OBJ);?>
							<tr>
								<td><?php echo $no;?></td>
								<td><a href="./index.php?op=sk&skid=<?php echo $object->id_sk;?>"><?php echo $object->custom_noagenda; ?></a></td>
								<td><?php echo $object->no_sk;?></td>
								<td><?php echo $object->tujuan_surat;?></td>
								<td><?php echo $object->perihal;?></td>
								<td><?php echo $tglsrt;?></td>
								<td class="center">
									<div class="action-buttons">
												<!-- <a href="#" class="green bigger-140 show-details-btn" title="Show Details">
													<i class="ace-icon fa fa-angle-double-down"></i>
													<span class="sr-only">Details</span>
												</a> -->
												<button type="button" data-toggle="modal" data-target="#exampleModalCenter-<?= $no?>">Buka</button>
											</div>
										</td>
										<td align="center">
											<div class="hidden-sm hidden-xs btn-group">
												<a href="./index.php?op=add_sk&skid=<?php echo $object->id_sk;?>">								
													<button class="btn btn-minier btn-info">
														<i class="ace-icon fa fa-pencil bigger-100"></i>
													</button>
												</a>
												<a href="./index.php?op=sk&skid=<?php echo $object->id_sk;?>&act=del" onclick="return confirm('Anda yakin akan menghapus data ini??')">
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
															<a href="./index.php?op=add_sk&skid=<?php echo $object->id_sk;?>">
																<button class="btn btn-minier btn-info">
																	<i class="ace-icon fa fa-pencil bigger-100"></i>
																</button>
															</a>
														</li>
														<li>
															<a href="./index.php?op=sk&skid=<?php echo $object->id_sk;?>&act=del" onclick="return confirm('Anda yakin akan menghapus data ini??')">
																<button class="btn btn-minier btn-danger">
																	<i class="ace-icon fa fa-trash-o bigger-110"></i>
																</button>
															</a>
														</li>
													</ul>
												</div>
											</div>
										</td>
									</tr>
								<!-- 	<tr class="detail-row">
										<td colspan="12">
											<div class="table-detail">
												<div class="row">
													<div class="col-xs-12 col-sm-12">
														<div class="space visible-xs"></div>
														<div class="profile-user-info profile-user-info-striped">
															<div class="profile-info-row">
																<div class="profile-info-name"> No Agenda </div>
																<div class="profile-info-value"><span><a href="./index.php?op=sm&smid=<?php echo $object->id_sm;?>"><?php echo $object->custom_noagenda;?></a></span></div>
															</div>
															<div class="profile-info-row">
																<div class="profile-info-name"> Nomor surat </div>
																<div class="profile-info-value"><span><?php echo $object->no_sk;?></span></div>
															</div>
															<div class="profile-info-row">
																<div class="profile-info-name"> Tujuan </div>
																<div class="profile-info-value"><span><?php echo $object->tujuan_surat;?></span></div>
															</div>
															<div class="profile-info-row">
																<div class="profile-info-name"> Perihal </div>
																<div class="profile-info-value"><span><?php echo $object->perihal;?></span></div>
															</div>
															<div class="profile-info-row">
																<div class="profile-info-name"> Klasifikasi </div>
																<div class="profile-info-value"><span><?php echo $ViewKlasifikasi->nama; ?></span></div>
															</div>
															<div class="profile-info-row">
																<div class="profile-info-name"> Tgl Surat </div>
																<div class="profile-info-value"><span><?php echo $tglsrt;?></span></div>
															</div>
															<div class="profile-info-row">
																<div class="profile-info-name"> File Surat </div>
																<div class="profile-info-value">
																	<span><?php 
																		if($object->file != ""){?>
																			<a href="./berkas/<?php echo $object->file;?>" target="_blank">Lihat File Surat</a><?php
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
															<h3 class="modal-title" id="exampleModalLongTitle">Detail Surat Keluar</h3>
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
																					<b> No Agenda </b> <a href="./index.php?op=sk&skid=<?php echo $object->id_sk;?>" class="pull-right"><span><?php echo $object->custom_noagenda; ?></a></span></a>
																				</li>
																				<li class="list-group-item">
																					<b>Nomor surat </b> <b class="pull-right"><span><?php echo $object->no_sk;?></span></b>
																				</li>
																				<li class="list-group-item">
																					<b>Tujuan</b> <b class="pull-right"><span><?php echo $object->tujuan_surat;?></span></b>
																				</li>
																				<li class="list-group-item" style="height: 100px;">
																					<b>Perihal </b> <b class="pull-right"><span><?php echo $object->perihal;?></span></b>
																				</li>
																				<li class="list-group-item">
																					<b> Klasifikasi</b> <b class="pull-right"><span><?php echo $ViewKlasifikasi->nama; ?></span></b>
																				</li>
																				<li class="list-group-item">
																					<b> Tgl Surat </b> <b class="pull-right"><span><?php echo $tglsrt;?></span></b>
																				</li>
																				<li class="list-group-item">
																					<b> File Surat</b> <b class="pull-right"><span><?php 
																					if($object->file != ""){?>
																						<a href="./berkas/<?php echo $object->file;?>" target="_blank">Lihat File Surat</a><?php
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
								Data tidak ditemukan. Terimakasih.
							</p>
							</div><?php
						}
						/* PAGINATION */
					//hitung jumlah data
						if(isset($_GET['keyword'])){
							$jml_data = $arsip_sk2->rowCount();
							$link_order="&keyword=$_GET[keyword]";
						}else{
							if(isset($_GET['yearsk'])){
								$params = array(':year' => $_GET['yearsk']);
								$link_order="&yearsk=$_GET[yearsk]";
							}else{
								$params = array(':year' => $dataLast->thn);
								$link_order="";
							}
							$jlhdata = $this->model->selectprepare("arsip_sk", $field=null, $params, "DATE_FORMAT(tgl_surat, '%Y')=:year", $other=null);
							$jml_data = $jlhdata->rowCount();
						}
					//Jumlah halaman
						$JmlHalaman = ceil($jml_data/$batas); 
					//Navigasi ke sebelumnya
						if($pg > 1){
							$link = $pg-1;
							$prev = "index.php?op=sk&halaman=$link$link_order";
							$prev_disable = " ";
						}else{
							$prev = "#";
							$prev_disable = "disabled";
						}
					//Navigasi ke selanjutnya
						if($pg < $JmlHalaman){
							$link = $pg + 1;
							$next = "index.php?op=sk&halaman=$link$link_order";
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
						/* END PAGINATION */
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