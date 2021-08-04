<?php
$params = array(':id_user' => $_SESSION['id_user']);
$user = $this->model->selectprepare("user", $field=null, $params, "id_user=:id_user");
if($user->rowCount() >= 1){
	$data_user = $user->fetch(PDO::FETCH_OBJ);
	$nama = 'value="'.$data_user->nama .'"';
	$uname = 'value="'.$data_user->uname .'"';
}
if($_SERVER["REQUEST_METHOD"] == "POST"){
	//print_r($_POST);
	$nama = htmlspecialchars($purifier->purify(trim($_POST['nama'])), ENT_QUOTES);
	$uname = htmlspecialchars($purifier->purify(trim($_POST['uname'])), ENT_QUOTES);
	$upass = htmlspecialchars($purifier->purify(trim($_POST['upass'])), ENT_QUOTES);
	if(empty($upass)){
		$field = array('nama' => $nama, 'uname' => $uname);
	}else{
		$upass = md5($upass);
		$field = array('nama' => $nama, 'uname' => $uname, 'upass' => $upass);
	}
	$params = array(':id_user' => $_SESSION['id_user']);
	$update = $this->model->updateprepare("user", $field, $params, "id_user=:id_user");
	if($update){?>
		<div class="alert alert-success">
			<button type="button" class="close" data-dismiss="alert">
				<i class="ace-icon fa fa-times"></i>
			</button>
			<p>
				<strong><i class="ace-icon fa fa-check"></i>Sukses!</strong>
				Data Sukses di Perbaharui. Silahkan Logout dan Login kembali. Terimakasih.
			</p>
			<p>
				<a href="./keluar"><button class="btn btn-minier btn-primary">Logout</button></a>
			</p>
		</div><?php
	}
}else{?>
	<!-- PAGE CONTENT BEGINS -->
	<form class="form-horizontal" role="form" method="POST" name="formku" action="<?php echo $_SESSION['url'];?>">
		<div class="box-body">
        <div class="row">

         <div class="col-md-12">
          <div class="box box-primary">
            <div class="box-header">
              <h3 class="box-title">Edit Profil</h3>
            </div>
            <div class="box-body">

            	<div class="widget-body">
					<div class="widget-main">

						<div class="widget-body">
						<div class="widget-main">
							<label for="id-date-range-picker-1" style="margin-bottom: 10px; margin-top: 10px;">Nama Lengkap *</label>
							<span style="padding-right: auto;"  data-placement="left" class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Di isi sesuai dengan Nama Lengkap">
					  		<i class="fa fa-question-circle"></i>
				  			</span>
							<div class="row">
								<div class="col-xs-8 col-sm-5">
									<div class="input-group">
										<span class="input-group-addon">
											<i class="fa fa-user bigger-110"></i>
										</span>
										<input class="form-control" placeholder="Nama Lengkap" type="text" name="nama" <?php if(isset($nama)){ echo $nama; }?> id="form-field-mask-1" required/>
									</div>
								</div>
							</div>
							<div class="space-6"></div>
							<label for="id-date-range-picker-1" style="margin-bottom: 10px; margin-top: 10px;">Username *</label>
							<span style="padding-right: auto;"  data-placement="left" class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Username untuk login, dapat diganti sesuai kebutuhan.">
								<i class="fa fa-question-circle"></i>
				  			</span>
							<div class="row">
								<div class="col-xs-8 col-sm-3">
									<div class="input-group">
										<span class="input-group-addon">
											<i class="fa fa-user bigger-110"></i>
										</span>
										<input class="form-control" placeholder="Username" type="text" name="uname" <?php if(isset($uname)){ echo $uname; }?> id="form-field-mask-1" required/>
									</div>
								</div>
							</div>
							<div class="space-6"></div>
							<label for="id-date-range-picker-1" style="margin-bottom: 10px; margin-top: 10px;">Password</label>
							<span style="padding-right: auto;"  data-placement="left" class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Password untuk login, dapat diganti sesuai kebutuhan (kosongkan isian ini jika password tidak ingin di ubah.">
								<i class="fa fa-question-circle"></i>
				  			</span>
							<div class="row">
								<div class="col-xs-8 col-sm-3">
									<div class="input-group">
										<span class="input-group-addon">
											<i class="fa fa-user bigger-110"></i>
										</span>
										<input class="form-control" placeholder="Password" type="text" name="upass"  id="form-field-mask-1"/>
									</div>
								</div>
							</div>
							<div class="space-6"></div>
							<div class="row" style="margin-top: 30px;">
								<div class="col-xs-8 col-sm-2">
									<div class="input-group">
										<button type="submit" class="btn btn-info" type="button">
											<i class="ace-icon fa fa-check bigger-110"></i>
											Submit
										</button>
									</div>
								</div>
							</div>
						</div>
					</div>

						
						

						
					</div>
				</div>
         


            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

       
          <!-- /.box -->
        </div>
        </div>

     </div>
	</form><?php
}?>