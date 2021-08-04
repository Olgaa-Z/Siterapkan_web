<?php
if(isset($_GET['memoid'])){
	require_once "view_memo_exp_detail.php";
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
			$SM = $this->model->selectprepare("arsip_sm a JOIN user b on a.id_user=b.id_user", $field=null, $params=null, $where=null, "WHERE DATE(NOW()) > a.tgl_retensi && a.tgl_retensi != '0000-00-00' order by a.tgl_terima DESC LIMIT $posisi, $batas");
			
			$SM2 = $this->model->selectprepare("arsip_sm a JOIN user b on a.id_user=b.id_user", $field=null, $params=null, $where=null, "WHERE DATE(NOW()) > a.tgl_retensi && a.tgl_retensi != '0000-00-00'", $other=null);
		}else{
			$params = array(':pengirim' => $keyword, ':perihal' => $keyword);
			$SM = $this->model->selectprepare("arsip_sm a JOIN user b on a.id_user=b.id_user", $field=null, $params, "(a.pengirim LIKE :pengirim OR a.perihal LIKE :perihal)", "AND DATE(NOW()) > a.tgl_retensi && a.tgl_retensi != '0000-00-00' order by a.tgl_terima DESC LIMIT $posisi, $batas");
			
			$SM2 = $this->model->selectprepare("arsip_sm a JOIN user b on a.id_user=b.id_user", $field=null, $params=null, "(a.pengirim LIKE :pengirim OR a.perihal LIKE :perihal)", "AND DATE(NOW()) > a.tgl_retensi && a.tgl_retensi != '0000-00-00'");
		}
	}else{
		$SM = $this->model->selectprepare("arsip_sm a JOIN user b on a.id_user=b.id_user", $field=null, $params=null, $where=null, "WHERE DATE(NOW()) > a.tgl_retensi && a.tgl_retensi != '0000-00-00' order by a.tgl_terima DESC LIMIT $posisi, $batas");
	}
	if($SM->rowCount() >= 1){?>

		<div class="row">
        <div class="col-xs-12">
          <div class="box">

              <table class="table table-hover">
                <tr>
                  <th>No</th>
                  <th>Pengirim</th>
                  <th>Tanggal diterima</th>
                  <th>Perihal</th>
                  <th>Jatuh Tempo</th>
                </tr>

		<div class="widget-box">
			<div id="inbox" class="tab-pane in active">
				<div class="message-container">
					<div class="message-list-container"> 
						<div class="message-list" id="message-list"><?php
						$no=1+$posisi;
						while($ViewSM = $SM->fetch(PDO::FETCH_OBJ)){						
							$tglterima = substr($ViewSM->tgl_retensi,0,10); ?>						
							 <tr>
				                  <td><?php echo $no;?></td>
				                  <td><?php echo $ViewSM->pengirim;?></td>
				                  <td><?php echo tgl_indo($tglterima);?></td>
				                  <td><a href="./index.php?op=memo&memoid=<?php echo $ViewSM->id_sm;?>"><?php echo $ViewSM->perihal;?></a>
				                  </td>
				                  <td><span class="label label-danger arrowed-right arrowed-in">jatuh tempo</span><?php echo $labelStat;?>
									<?php echo $ProgresStat;?>
									</span>
								  </td>
				             </tr>
							 <?php
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
				Data surat jatuh tempo belum ada. Terimakasih.
			</p>
		</div>
				
          <?php
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