<?php
if(isset($_GET['memoid'])){
	require_once "view_memo_detail.php";
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
	$userLike = "'%\"$_SESSION[id_user]\"%'";
	//$userLike = "'%\"3\"%'";
	if(isset($_GET['keyword'])){
		$keyword = "%".$_GET['keyword']."%";
		if(empty($_GET['keyword'])){
			$SM = $this->model->selectprepare("arsip_sm a JOIN user b on a.id_user=b.id_user", $field=null, $params=null, $where=null, "WHERE a.tujuan_surat LIKE $userLike order by a.tgl_terima DESC LIMIT $posisi, $batas");
			
			$SM2 = $this->model->selectprepare("arsip_sm a JOIN user b on a.id_user=b.id_user", $field=null, $params=null, $where=null, "WHERE a.tujuan_surat LIKE $userLike", $other=null);
		}else{
			$params = array(':pengirim' => $keyword, ':perihal' => $keyword);
			$SM = $this->model->selectprepare("arsip_sm a JOIN user b on a.id_user=b.id_user", $field=null, $params, "(a.pengirim LIKE :pengirim OR a.perihal LIKE :perihal)", "AND a.tujuan_surat LIKE $userLike order by a.tgl_terima DESC LIMIT $posisi, $batas");
			
			$SM2 = $this->model->selectprepare("arsip_sm a JOIN user b on a.id_user=b.id_user", $field=null, $params=null, "(a.pengirim LIKE :pengirim OR a.perihal LIKE :perihal)", "AND a.tujuan_surat LIKE $userLike");
		}
	}else{
		$SM = $this->model->selectprepare("arsip_sm a JOIN user b on a.id_user=b.id_user", $field=null, $params=null, $where=null, "WHERE a.tujuan_surat LIKE $userLike order by a.tgl_terima DESC LIMIT $posisi, $batas");
	}
	if($SM->rowCount() >= 1){?>
		<div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Surat Masuk</h3>

              <div class="box-tools">
                <div class="input-group input-group-sm hidden-xs" style="width: 150px;">
                  <!-- <input type="text" name="table_search" class="form-control pull-right" placeholder="Search"> -->

                  <!-- <div class="input-group-btn">
                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                  </div> -->
                </div>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
              <table class="table table-hover">
                <tr>
                  <th>No</th>
                  <th>Pengirim</th>
                  <th>Tanggal diterima</th>
                  <th>Perihal</th>
                  <th>Expired</th>
                </tr> 
		<div class="widget-box">
			<div id="inbox" class="tab-pane in active">
				<div class="message-container">
					<div class="message-list-container">
						<div class="message-list" id="message-list"><?php
						$no=1+$posisi;
						while($ViewSM = $SM->fetch(PDO::FETCH_OBJ)){
							$params2 = array(':id_sm' => $ViewSM->id_sm);
							$disposisi = $this->model->selectprepare("memo", $field=null, $params2, "id_sm=:id_sm", $order=null);
							if($disposisi->rowCount() >= 1){
								$labelStat = " <i class=\"ace-icon fa fa-share bigger-110 green\" title=\"Telah di disposisi\"></i>";
							}else{
								$labelStat = "";
							}
							
							$params1 = array(':id_sm' => $ViewSM->id_sm);
							$CekStatFinish = $this->model->selectprepare("status_surat a join user b on a.id_user=b.id_user", $field=null, $params1, "a.id_sm=:id_sm", "ORDER BY a.id_status DESC LIMIT 1");
							if($CekStatFinish->rowCount() >= 1){
								$dataCekStatFinish = $CekStatFinish->fetch(PDO::FETCH_OBJ);
								if($dataCekStatFinish->statsurat == 1){
									$ProgresStat = " <i class=\"ace-icon fa fa-history bigger-110 green\" title=\"Surat sedang ditindaklanjuti\"></i>";
								}elseif($dataCekStatFinish->statsurat == 2){
									$ProgresStat = " <i class=\"ace-icon fa fa-thumbs-o-up bigger-110 green\" title=\"Surat sudah selesai ditindaklanjuti\"></i>";
								}elseif($dataCekStatFinish->statsurat == 0){
									$ProgresStat = " <i class=\"ace-icon fa fa-times bigger-110 green\" title=\"Surat tidak dapat diproses\"></i>";
								}
							}else{
								$ProgresStat = " <i class=\"ace-icon fa fa-info bigger-110 green\" title=\"Surat belum diproses\"></i>";
							}

							$besok = date('Y-m-d',strtotime('+1 days'));
							$lusa = date('Y-m-d',strtotime('+2 days'));
							if(date('Y-m-d') > $ViewSM->tgl_retensi && $ViewSM->tgl_retensi != '0000-00-00'){
								$label_expired = '<span class="label label-danger arrowed-right arrowed-in">jatuh tempo</span>';
							}elseif(date('Y-m-d') == $ViewSM->tgl_retensi && $ViewSM->tgl_retensi != '0000-00-00'){
								$label_expired = '<span class="label label-warning arrowed-right arrowed-in">jatuh tempo hari ini</span>';
							}elseif($besok == $ViewSM->tgl_retensi && $ViewSM->tgl_retensi != '0000-00-00'){
								$label_expired = '<span class="label label-warning arrowed-right arrowed-in">jatuh tempo besok</span>';
							}elseif($lusa == $ViewSM->tgl_retensi && $ViewSM->tgl_retensi != '0000-00-00'){
								$label_expired = '<span class="label label-warning arrowed-right arrowed-in">jatuh tempo lusa</span>';
							}else{
								$label_expired = "";
							}
						
							$tglterima = substr($ViewSM->tgl_surat,0,10);
							$params2 = array(':id_sm' => $ViewSM->id_sm, ':id_user' => $_SESSION['id_user'], ':kode' => 'SM');
							$CekRead = $this->model->selectprepare("surat_read", $field=null, $params2, "id_sm=:id_sm AND id_user=:id_user AND kode=:kode", $order=null);
							if($CekRead->rowCount() <= 0){?>


								 <tr>
				                  <td><?php echo $no;?></td>
				                  <td><?php echo $ViewSM->pengirim;?></td>
				                  <td><?php echo tgl_indo($tglterima);?></td>
				                  <td><a href="./index.php?op=memo&memoid=<?php echo $ViewSM->id_sm;?>"><?php echo $ViewSM->perihal;?></a></td>
				                  <td><?php echo $labelStat;?><?php echo $ProgresStat;?><?php echo $label_expired; ?></td>
				                </tr>

                			<?php
							}else{?>						



			                <tr>
			                  <td><?php echo $no;?></td>
			                  <td><?php echo $ViewSM->pengirim;?></td>
			                  <td><?php echo tgl_indo($tglterima);?></td>
			                  <td><a href="./index.php?op=memo&memoid=<?php echo $ViewSM->id_sm;?>"><?php echo $ViewSM->perihal;?></a></td>
			                  <td><?php echo $labelStat;?><?php echo $ProgresStat;?><?php echo $label_expired; ?></td>
			                </tr>							

								<?php
							}
							$no++;

						}?>
						</div>
					</div>
				</div>
			</div>
		</div>


			        </table>
            	</div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
      </div><?php
	}else{?>
		<div class="alert alert-danger">
			<button type="button" class="close" data-dismiss="alert">
				<i class="ace-icon fa fa-times"></i>
			</button>
			<p>
				<strong><i class="ace-icon fa fa-check"></i>Perhatian!!!</strong>
				Belum ada data surat masuk untuk anda. Terimakasih.
			</p>
		</div><?php
	}
	/* PAGINATION */
	//hitung jumlah data
	if(isset($_GET['keyword'])){
		$jml_data = $SM2->rowCount();
		$link_order="&keyword=$_GET[keyword]";
	}else{
		$params = array(':tujuan_surat' => $userLike);
		$jlhdata = $this->model->selectprepare("arsip_sm a JOIN user b on a.id_user=b.id_user", $field=null, $params=null, $where=null, "WHERE a.tujuan_surat LIKE $userLike");
		$jml_data = $jlhdata->rowCount();
		$link_order="";
	}
	//Jumlah halaman
	$JmlHalaman = ceil($jml_data/$batas); 
	//Navigasi ke sebelumnya
	if($pg > 1){
		$link = $pg-1;
		$prev = "index.php?op=memo&halaman=$link$link_order";
		$prev_disable = " ";
	}else{
		$prev = "#";
		$prev_disable = "disabled";
	}
	//Navigasi ke selanjutnya
	if($pg < $JmlHalaman){
		$link = $pg + 1;
		$next = "index.php?op=memo&halaman=$link$link_order";
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