
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

									<form class="form-horizontal" role="form" enctype="multipart/form-data" method="POST" name="formku" action="<?php echo $_SESSION['url'];?>">
										<div class="space-4"></div>
										<div class="space-4"></div>
										<div class="form-group">
											<label class="col-sm-2 control-label no-padding-right" for="form-field-mask-1"> Judul Memo *</label>
											<div class="col-sm-9">
												<input class="form-control" data-rel="tooltip" placeholder="Perihal / judul memo" type="text" name="judul" <?php if(isset($judul)){ echo $judul; }?> data-placement="bottom" id="form-field-mask-1" required/>
											</div>
										</div>
										<div class="space-4"></div>	
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