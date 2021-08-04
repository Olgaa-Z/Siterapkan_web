<?php
if(isset($_GET['infoid'])){
	require_once "view_info_detail.php";
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
		//$field = array("a.id_user as iduser_dis","a.*","b.*");
		if(empty($_GET['keyword'])){
			$CekInfo = $this->model->selectprepare("info a join user c on a.pengirim_info=c.id_user", $field=null, $params=null, $where=null, "WHERE a.tujuan_info LIKE '%\"$_SESSION[id_user]\"%' order by a.tgl_info DESC LIMIT $posisi, $batas");
			
			$CekInfo2 = $this->model->selectprepare("info a join user c on a.pengirim_info=c.id_user", $field=null, $params=null, $where=null, "WHERE a.tujuan_info LIKE '%\"$_SESSION[id_user]\"%'");
		}else{
			$params = array(':judul_info' => $keyword, ':ket_info' => $keyword);
			$CekInfo = $this->model->selectprepare("info a join user c on a.pengirim_info=c.id_user", $field=null, $params, "(a.judul_info LIKE :judul_info OR a.ket_info LIKE :ket_info)", "AND a.tujuan_info LIKE '%\"$_SESSION[id_user]\"%' order by a.tgl_info DESC LIMIT $posisi, $batas");
			
			$CekInfo2 = $this->model->selectprepare("info a join user c on a.pengirim_info=c.id_user", $field=null, $params, "(a.judul_info LIKE :judul_info OR a.ket_info LIKE :ket_info)", "AND a.tujuan_info LIKE '%\"$_SESSION[id_user]\"%'");
		}
	}else{
		$CekInfo = $this->model->selectprepare("info a join user c on a.pengirim_info=c.id_user", $field=null, $params=null, $where=null, "WHERE a.tujuan_info LIKE '%\"$_SESSION[id_user]\"%' order by a.tgl_info DESC LIMIT $posisi, $batas");
	}
	if($CekInfo->rowCount() >= 1){
		if($HakAkses->info == "Y"){?>
			<?php /*<li>
				<a href="./index.php?op=add_memo" title="Edit" class="tooltips">
					<i class="sa-list-add"></i>
				</a>
				</li><?php */
			}?>
			<div class="row">
				<div class="col-xs-12">
					<div class="box">
						<div class="box-header">
							<h3 class="box-title">Memo</h3>

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
    		</tr> 

    		<div class="widget-box">
    			<div id="inbox" class="tab-pane in active">
    				<div class="message-container">
    					<div class="message-list-container">
    						<div class="message-list" id="message-list"><?php
    						$no=1+$posisi;
    						while($DataInfo = $CekInfo->fetch(PDO::FETCH_OBJ)){
    							$tgl_info = substr($DataInfo->tgl_info,0,10);
    							$params2 = array(':id_sm' => $DataInfo->id_info, ':id_user' => $_SESSION['id_user'], ':kode' => 'INFO');
    							$CekRead = $this->model->selectprepare("surat_read", $field=null, $params2, "id_sm=:id_sm AND id_user=:id_user AND kode=:kode", $order=null);
    							if($CekRead->rowCount() <= 0){?>
    								<tr>
    									<td><?php echo $no;?></td>
    									<td><?php echo $DataInfo->nama;?></td>
    									<td><small><?php echo tgl_indo($tgl_info);?></td>
    										<td><a href="./index.php?op=info&infoid=<?php echo $DataInfo->id_info;?>"><?php echo $DataInfo->judul_info;?></a></td>
    									</tr>
    									<?php
    								}else{?>
    									<tr>
    										<td><?php echo $no;?></td>
    										<td><?php echo $DataInfo->nama;?></td>
    										<td><?php echo tgl_indo($tgl_info);?></td>
    										<td><a href="./index.php?op=info&infoid=<?php echo $DataInfo->id_info;?>"><?php echo $DataInfo->judul_info;?></a></td>
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
			<strong><i class="ace-icon fa fa-check"></i>Perhatian!</strong>
			Belum ada data memo masuk untuk anda/Data memo tidak ditemukan. Terimakasih.
		</p>
	</div>


	<?php
}
/* PAGINATION */
	//hitung jumlah data
if(isset($_GET['keyword'])){
	$jml_data = $CekInfo2->rowCount();
	$link_order="&keyword=$_GET[keyword]";
}else{
	/* $params = array(':disposisi_user' => $_SESSION['id_user']); */
	$jlhdata = $this->model->selectprepare("info a join user c on a.pengirim_info=c.id_user", $field=null, $params=null, $where=null, "WHERE a.tujuan_info LIKE '%\"$_SESSION[id_user]\"%'");
	$jml_data = $jlhdata->rowCount();
	$link_order="";
}
	//Jumlah halaman
$JmlHalaman = ceil($jml_data/$batas); 
	//Navigasi ke sebelumnya
if($pg > 1){
	$link = $pg-1;
	$prev = "index.php?op=info&halaman=$link$link_order";
	$prev_disable = " ";
}else{
	$prev = "#";
	$prev_disable = "disabled";
}
	//Navigasi ke selanjutnya
if($pg < $JmlHalaman){
	$link = $pg + 1;
	$next = "index.php?op=info&halaman=$link$link_order";
	$next_disable = " ";
}else{
	$next = "#";
	$next_disable = "disabled";
}
if($batas < $jml_data){?>
	<ul class="pager">
		<li class="previous <?php echo $prev_disable;?>"><a href="<?php echo $prev;?>"><font color="black"><b>&larr; Sebelumnya </font></b></a></li>
		<li class="next <?php echo $next_disable;?>"><a href="<?php echo $next;?>"><font color="black"><b>Selanjutnya &rarr;</b></font></a></li>
	</ul>
	<span class="text-muted color"><font color="white">Halaman <?php echo $pg;?> dari <?php echo $JmlHalaman;?> (Total : <?php echo $jml_data;?> records)</font></span><?php
}
/* END PAGINATION */
}?>