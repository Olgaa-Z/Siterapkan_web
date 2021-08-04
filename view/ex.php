	//print_r($cekDiteruskan);?>
	<div class="widget-box">
		<div class="widget-header">
			<h4 class="widget-title"><?php echo $title;?></h4>
			<div class="widget-toolbar">
				<a href="#" data-action="collapse">
					<i class="ace-icon fa fa-chevron-up"></i>
				</a>
				<a href="#" data-action="close">
					<i class="ace-icon fa fa-times"></i>
				</a>
			</div>
		</div>
		<div class="widget-body">
			<div class="widget-main">
				<form class="form-horizontal" role="form" enctype="multipart/form-data" method="POST" name="formku" action="<?php echo $_SESSION['url'];?>">
					<div class="form-group">
						<label class="col-sm-2 control-label no-padding-right" for="form-field-1"> No. Agenda *</label>
						<span class="help-button" data-rel="popover" data-trigger="hover" data-placement="left" data-content="Di isi sesuai dengan nomor agenda surat masuk." title="Nomor Agenda">?</span>
						<div class="col-sm-3"> <?php 
							$params = array(':id' => 1);
							$cek_noagenda_custom = $this->model->selectprepare("pengaturan", $field=null, $params, "id=:id")->fetch(PDO::FETCH_OBJ);
							if($cek_noagenda_custom->no_agenda_sm != ''){
								//$noAgendaDb = $noAgenda;
								$noAgenda = $noAgenda."/$cek_noagenda_custom->no_agenda_sm";								
							} ?>
							<input class="form-control" placeholder="Nomor Agenda Surat" type="text" name="noagenda" <?php if(isset($noAgenda)){ echo 'value="'.$noAgenda .'"'; } ?> id="form-field-mask-1" required disabled />
							<input type="hidden" name="noagenda" value="<?php echo $nonoAgendaShow;?>"/>
							<input type="hidden" name="noagenda_custom" value="<?php echo $noAgenda;?>"/>
							<?php /*
							<input class="form-control" data-rel="tooltip" type="text" name="noagenda" <?php if(isset($noagenda)){ echo $noagenda; }?> id="form-field-1" placeholder="Nomor Agenda Surat" title="Diisi dengan nomor agenda surat masuk!" data-placement="bottom" required/>*/?>
						</div>
					</div>
					<div class="space-4"></div>
					<div class="form-group">
						<label class="col-sm-2 control-label no-padding-right" for="form-field-mask-1"> Nomor Surat *</label>
						<span class="help-button" data-rel="popover" data-trigger="hover" data-placement="left" data-content="Di isi sesuai dengan nomor surat masuk yang diterima." title="Nomor Surat Masuk">?</span>
						<div class="col-sm-4">
							<input class="form-control" placeholder="Nomor surat masuk" type="text" name="nosm" <?php if(isset($nosm)){ echo $nosm; }?> id="form-field-mask-1" required/>
						</div>
					</div>
					<div class="space-4"></div>
					<div class="form-group">
						<label class="col-sm-2 control-label no-padding-right" for="form-field-mask-1">Klasifikasi Surat *</label>
						<span class="help-button" data-rel="popover" data-trigger="hover" data-placement="left" data-content="Pilih klasifikasi arsip." title="Klasifikasi">?</span>
						<div class="col-sm-3">
							<select class="form-control" id="form-field-select-3" name="id_klasifikasi" data-placeholder="Pilih Klasifikasi..." required>
								<option value="">Pilih Klasifikasi</option><?php
								$KlasArsip= $this->model->selectprepare("klasifikasi", $field=null, $params=null, $where=null, "ORDER BY nama ASC");
								if($KlasArsip->rowCount() >= 1){
									while($dataKlasArsip = $KlasArsip->fetch(PDO::FETCH_OBJ)){
										if(isset($id_klasifikasi) && $id_klasifikasi == $dataKlasArsip->id_klas){ ?>
											<option value="<?php echo $dataKlasArsip->id_klas;?>" selected><?php echo $dataKlasArsip->nama;?></option><?php
										}else{?>
											<option value="<?php echo $dataKlasArsip->id_klas;?>"><?php echo $dataKlasArsip->nama;?></option><?php
										}
									}
								}else{?>
									<option value="">Data klasifikasi belum ada</option><?php
								}?>
							</select>
						</div>
					</div>
					<div class="space-4"></div>
					<div class="form-group">
						<label class="col-sm-2 control-label no-padding-right" for="form-field-mask-1"> Tanggal Surat *</label>
						<span class="help-button" data-rel="popover" data-trigger="hover" data-placement="left" data-content="Di isi sesuai dengan tanggal pada surat masuk. ex. 01-12-2015" title="Tanggal Surat">?</span>
						<div class="col-sm-3">
							<input class="form-control date-picker" id="id-date-picker-1" data-date-format="dd-mm-yyyy" placeholder="Tanggal pada surat masuk" type="text" name="tglsm" <?php if(isset($tgl_surat)){ echo $tgl_surat; }?> id="form-field-mask-1" required/>
						</div>
					</div>
					<div class="space-4"></div>
					<div class="form-group">
						<label class="col-sm-2 control-label no-padding-right" for="form-field-mask-1"> Tanggal Terima *</label>
						<span class="help-button" data-rel="popover" data-trigger="hover" data-placement="left" data-content="Di isi sesuai dengan tanggal surat masuk diterima. ex. 01-12-2015" title="Tanggal Terima">?</span>
						<div class="col-sm-3">
							<input class="form-control date-picker" id="id-date-picker-1" data-date-format="dd-mm-yyyy" placeholder="Tanggal terima surat masuk" type="text" name="tgl_terima" <?php if(isset($tgl_terima)){ echo $tgl_terima; }?> id="form-field-mask-1" required/>
						</div>
					</div>
					<div class="space-4"></div>
					<div class="form-group">
						<label class="col-sm-2 control-label no-padding-right" for="form-field-mask-1"> Retensi Arsip</label>
						<span class="help-button" data-rel="popover" data-trigger="hover" data-placement="left" data-content="Tanggal retensi (masa berlaku) arsip surat masuk" title="Tanggal Terima">?</span>
						<div class="col-sm-3">
							<input class="form-control date-picker" id="id-date-picker-1" data-date-format="dd-mm-yyyy" placeholder="Tanggal retensi arsip surat" type="text" name="tgl_retensi" <?php if(isset($tgl_retensi)){ echo $tgl_retensi; }?> id="form-field-mask-1"/>
						</div>
					</div>
					<div class="space-4"></div>
					<div class="form-group">
						<label class="col-sm-2 control-label no-padding-right" for="form-field-mask-1"> Surat Dari *</label>
						<span class="help-button" data-rel="popover" data-trigger="hover" data-placement="left" data-content="Di isi sesuai sumber pengirim surat (nama lembaga/peroranngan)." title="Sumber Surat">?</span>
						<div class="col-sm-8">
							<input class="form-control" data-rel="tooltip" placeholder="Sumber surat/pengirim" type="text" name="pengirim" <?php if(isset($pengirim)){ echo $pengirim; }?> title="Di isi sesuai sumber pengirim surat (nama lembaga atau peroranngan)" data-placement="bottom" id="form-field-mask-1" required/>
						</div>
					</div>
					<div class="space-4"></div>
					<div class="form-group">
						<label class="col-sm-2 control-label no-padding-right" for="form-field-mask-1"> Diteruskan ke *</label>
						<span class="help-button" data-rel="popover" data-trigger="hover" data-placement="left" data-content="Pilih tujuan Surat dieruskan (support multiple choise)." title="Diteruskan ke *">?</span>
						<div class="col-sm-8">
							<div class="space-2"></div>
							<select multiple="" class="chosen-select form-control" name="tujuan[]" id="form-field-select-3" data-placeholder="Pilih user..." required><?php
								$Diteruskan = $this->model->selectprepare("user a join user_jabatan b on a.jabatan=b.id_jab", $field=null, $params=null, $where=null, "ORDER BY a.nama ASC");
								if($Diteruskan->rowCount() >= 1){
									while($dataDiteruskan = $Diteruskan->fetch(PDO::FETCH_OBJ)){
										$DiteruskanSurat = $dataDiteruskan->nama ." (".$dataDiteruskan->nama_jabatan .")";
										if(false !== array_search($dataDiteruskan->id_user, $cekDiteruskan)){?>
											<option value="<?php echo $dataDiteruskan->id_user;?>" selected><?php echo $DiteruskanSurat;?></option><?php
										}else{?>
											<option value="<?php echo $dataDiteruskan->id_user;?>"><?php echo $DiteruskanSurat;?></option><?php
										}
									}								
								}else{?>
									<option value="">Not Found</option><?php
								}?>
							</select>
						</div>
					</div>
					<div class="space-4"></div>
					<div class="form-group">
						<label class="col-sm-2 control-label no-padding-right" for="form-field-mask-1"> Perihal *</label>
						<span class="help-button" data-rel="popover" data-trigger="hover" data-placement="left" data-content="Di isi sesuai perihal atau subjek surat masuk." title="Perihal">?</span>
						<div class="col-sm-9">
							<textarea class="form-control limited" placeholder="Perihal/subjek surat" name="perihal" id="form-field-9" maxlength="150" required><?php if(isset($perihal)){ echo $perihal; }?></textarea>
						</div>
					</div>
					<div class="space-4"></div>
					<div class="form-group">
						<label class="col-sm-2 control-label no-padding-right" for="form-field-mask-1"> Keterangan </label>
						<span class="help-button" data-rel="popover" data-trigger="hover" data-placement="left" data-content="Di isi dengan keterangan tambahan jika ada (seperti jadwal undangan, tempat, tanggal penting dsb)." title="Keterangan">?</span>
						<div class="col-sm-9">
							<textarea class="form-control limited" placeholder="Keterangan tambahan (jika ada)" name="ket" id="form-field-9" maxlength="150"><?php if(isset($ket)){ echo $ket; }?></textarea>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label no-padding-right" for="form-field-mask-1"> <?php echo $ketfile;?></label>
						<span class="help-button" data-rel="popover" data-trigger="hover" data-placement="left" data-content="Pilih File surat masuk yang ingin di upload. Caranya klik menu Pilih File. Tipe file : .pdf, .jpg, .png" title="File surat masuk">?</span>
						<div class="col-sm-4">
							<input type="file" class="form-control" name="filesm" id="id-input-file-1"/>
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