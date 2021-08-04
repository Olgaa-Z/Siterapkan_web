
<?php
include "./file_panduan/koneksi.php";
?>
<div class="widget-box">


	<div class="box-body">
		<div class="row">

			<div class="col-md-12">
				<div class="box box-primary">
					<div class="box-header">
						<h3 class="box-title">Edit Data Panduan</h3>
					</div>
					<div class="box-body">

						<div class="widget-body">
							<div class="widget-main">

								<?php
								$id = $_GET['id'];
								$query = mysqli_query($koneksi,"SELECT * FROM tb_panduan WHERE id=$id");
								while($data=mysqli_fetch_array($query))
								{
									?>

									<form class="form-horizontal" role="form" enctype="multipart/form-data" method="POST" name="formku" action="./file_panduan/edit_proses.php">

										<input type="hidden" name="id" placeholder="ID" value="<?php echo $data['id'];?>"></td>
										
										<div class="space-4"></div>
										<div class="form-group">
											<label class="col-sm-2 control-label no-padding-right" for="form-field-mask-1"> Judul Panduan *</label>
											<div class="col-sm-9">
												<input class="form-control" data-rel="tooltip" value="<?php echo $data['judul']; ?>" placeholder="Judul Panduan" type="text" name="judul_edit" data-placement="bottom" id="form-field-mask-1" required/>
											</div>
										</div>
										<div class="space-4"></div>	
										<div class="form-group">
											<label class="col-sm-2 control-label no-padding-right" for="form-field-mask-1"> File Pdf *</label>
											<div class="col-sm-9">
												<input class="form-control" data-rel="tooltip"  type="file" name="nama_file"  data-placement="bottom" id="form-field-mask-1"/>
											</div>
										</div>
										<!-- button submit -->
										<div class="space-4"></div>
										<div class="row" style="margin-top: 30px;">
											<div class="col-md-offset-2 col-md-10">
												<div class="input-group">
													<button type="submit" class="btn btn-info" type="button">
														<i class="ace-icon fa fa-check bigger-110"></i>
														Submit
													</button>
												</div>
											</div>
										</div>
								<!-- <div class="space-4"></div>
								<div class="form-group">
									<div class="col-md-offset-2 col-md-10">
										<button type="submit" class="btn btn-primary">Submit</button>
									</div>
								</div> -->
							</form>

							<?php
						}
						?> 


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
<!-- end end end div 1 -->


</div>