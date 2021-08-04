<?php
include "../file_panduan/koneksi.php";
?>
<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
		<div class="row">
			<div class="col-xs-12">
				<table id="simple-table" class="table  table-bordered table-hover">
					<thead>
						<tr>
							<th>Judul</th>
							<th>Lihat File</th>
							<th>Nama File</th>
							<th >Added on</th>
							<th width="100">Aksi</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$query = mysqli_query($koneksi,"SELECT * FROM tb_panduan ORDER BY id DESC");
						while($data=mysqli_fetch_array($query))
						{
							?>
							<tr>
								<td><?php echo $data['judul'];?></td>
								<td><a href="view.php?id=<?php echo $data['id'];?>">Lihat File</a></td>
								<td><?php echo $data['nama_file'];?></td>
								<td><?php echo $data['added_on'];?></td>
								<td>

									<div class="hidden-md hidden-lg">
										<div class="inline pos-rel">
											<button class="btn btn-minier btn-primary dropdown-toggle" data-toggle="dropdown" data-position="auto">
												<i class="ace-icon fa fa-cog icon-only bigger-110"></i>
											</button>
											<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
												<li>
													<a href="">		
														<button class="btn btn-minier btn-info">
															<i class="ace-icon fa fa-pencil"></i>
														</button>
													</a>
												</li>
												<li>
													<a href="" onclick="return confirm('Anda yakin akan menghapus data ini??')">
														<button class="btn btn-minier btn-danger">
															<i class="ace-icon fa fa-trash-o"></i>
														</button>
													</a>
												</li>
											</ul>
										</div>
									</div>
								</td>
							</tr>
							<?php
						}
						?> 
					</tbody>
				</table>

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