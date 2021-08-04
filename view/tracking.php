<?php
error_reporting(0);
ini_set('date.timezone', 'Asia/Jakarta');
require_once "view/indo_tgl.php";
require_once "htmlpurifier/library/HTMLPurifier.auto.php";
$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);
if($_SERVER["REQUEST_METHOD"] == "POST"){
  $cari = htmlspecialchars($purifier->purify(trim($_POST['cari'])), ENT_QUOTES);
  $params = array(':custom_noagenda' => $cari, ':no_sm' => $cari);
  $cek = $this->model->selectprepare("arsip_sm", $field=null, $params, "custom_noagenda =:custom_noagenda OR no_sm=:no_sm");
  if($cek->rowCount() >= 1){
    $dataSurat = $cek->fetch(PDO::FETCH_OBJ);
  }else{
    $dataSurat = 0;
  }
  $layout = "error-container";
  $title = "Hasil Tracking Status Surat";
}else{
  $layout = "login-container";
  $title = "Tracking Status Surat";
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Tracking</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="login/assets/vendors/iconfonts/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="login/assets/vendors/iconfonts/ionicons/dist/css/ionicons.css">
    <link rel="stylesheet" href="login/assets/vendors/iconfonts/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="login/assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="login/assets/vendors/css/vendor.bundle.addons.css">
    <!-- endinject -->
    <!-- plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="login/assets/css/shared/style.css">
    <!-- endinject -->
    <link rel="shortcut icon" href="dist/img/user2-160x160.png" />

     <!-- Favicon -->
  <link rel="icon" href="login/assets/img/brand/favicon.png" type="image/png">
  <!-- Fonts -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
  <!-- Icons -->
  <link rel="stylesheet" href="login/assets/vendor/nucleo/css/nucleo.css" type="text/css">
  <link rel="stylesheet" href="login/assets/vendor/@fortawesome/fontawesome-free/css/all.min.css" type="text/css">
  <!-- Page plugins -->
  <!-- Argon CSS -->
  <link rel="stylesheet" href="login/assets/css/argon.css?v=1.2.0" type="text/css">

  </head>
  <body>
    <div class="container-scroller">
      <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth auth-bg-1 theme-one">
          <div class="row w-100">
            <div class="col-lg-4 mx-auto">
              <div class="auto-form-wrapper">
                
                <h3 style="text-align: center;"><b>SITERAPKAN</b> </h3>
                <h6 style="text-align: center; margin-bottom: 30px;">  Sistem Tertib Administrasi Persuratan dan Kearsipan</h6>

                 <!-- Card body -->
                  <hr size="10px">
                    <div class="row" style="margin-bottom: 20px;">
                      <div class="col">
                         <i class="email-83"></i>
                        <span class="h3 font-weight-bold mb-0 text-primary mr-2"><?php echo $title;?></span>
                      </div>
                    </div>
                <?php
                      if(isset($dataSurat) && !is_numeric($dataSurat)){
                        $ListUser = $this->model->selectprepare("user a join user_jabatan b on a.jabatan=b.id_jab", $field=null, $params=null, $where=null, "ORDER BY a.nama ASC");
                        $TujuanSurat = "";
                        $TargetDisposisi = "";
                        while($dataListUser = $ListUser->fetch(PDO::FETCH_OBJ)){
                          if(false !== array_search($dataListUser->id_user, json_decode($dataSurat->tujuan_surat, true))){
                            $TujuanSurat .= '- '.$dataListUser->nama .' ('.$dataListUser->nama_jabatan .')<br/>';
                          }
                          
                        }
                        $tgl_diteruskan = substr($dataSurat->created,0,10);?>
                        <p><center>NoAgenda / NoSurat : <b><?php echo $cari;?></b>, Pengirim: <b><?php echo $dataSurat->pengirim;?></b></center></p>
                        <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                          <thead>
                            <tr>
                              <td width="250">Status surat</td>
                              <td width="140">update</td>
                            </tr>
                          </thead>
                          <tbody> 
                            <tr>
                              <td colspan="2"><b><center>SURAT DITERUSKAN KE</center></b></td>
                            </tr>
                            <tr>
                              <td><?php if($TujuanSurat != ''){ echo "$TujuanSurat"; }?></td>
                              <td><?php echo tgl_indo1($tgl_diteruskan);?>, <?php echo substr($dataSurat->created,-9,-3);?> WIB</td>
                            </tr>
                            <tr>
                              <td colspan="2"><b><center>RIWAYAT DISPOSISI</center></b></td>
                            </tr><?php
                            $params = array(':id_sm' => $dataSurat->id_sm);
                            $cekDisposisi = $this->model->selectprepare("memo a join user b on a.id_user=b.id_user", $field=null, $params, "a.id_sm=:id_sm", "ORDER BY a.tgl ASC");
                            if($cekDisposisi->rowCount() >= 1){
                              while($dataDisposisi= $cekDisposisi->fetch(PDO::FETCH_OBJ)){
                                $ListDisposisi2 = json_decode($dataDisposisi->disposisi, true);
                                $tgl_dispolevel = substr($dataDisposisi->tgl,0,10);?>
                                <tr>
                                  <td><?php
                                  echo "Disposisi dari <b>".$dataDisposisi->nama."</b> ke <br/>";
                                    foreach($ListDisposisi2 as $listdispo){
                                      $TampilUser = $this->model->selectprepare("user a join user_jabatan b on a.jabatan=b.id_jab", $field=null, $params=null, $where=null, "WHERE a.id_user='$listdispo'")->fetch(PDO::FETCH_OBJ);
                                      echo "- ".$TampilUser->nama." ($TampilUser->nama_jabatan)<br/>";?><?php
                                    }?>
                                  </td>
                                  <td><?php echo tgl_indo1($tgl_dispolevel);?>, <?php echo substr($dataDisposisi->tgl,-9,-3);?> WIB</td>
                                </tr><?php
                              }
                            }
                            $CekProgress = $this->model->selectprepare("status_surat", $field=null, $params=null, $where=null, "WHERE id_sm = '$dataSurat->id_sm' order by statsurat ASC");
                            if($CekProgress->rowCount() >= 1){?>
                              <tr>
                                <td colspan="2"><b><center>PROGRESS SURAT</center></b></td>
                              </tr><?php
                              while($dataCekProgress = $CekProgress->fetch(PDO::FETCH_OBJ)){
                                $CekUser = $this->model->selectprepare("user", $field=null, $params=null, $where=null, "WHERE id_user = '$dataCekProgress->id_user'")->fetch(PDO::FETCH_OBJ);
                                if($dataCekProgress->statsurat == 1){
                                  $statusSirat = "Sedang diproses";
                                }elseif($dataCekProgress->statsurat == 2){
                                  $statusSirat = "Selesai";
                                }elseif($dataCekProgress->statsurat == 0){
                                  $statusSirat = "Dibatalkan";
                                }?>
                                <tr>
                                  <td>
                                    <b><?php echo $CekUser->nama;?></b> status : <b><?php echo $statusSirat;?></b> <br/><?php echo $dataCekProgress->ket;?>
                                  </td>
                                  <td>
                                    <?php echo tgl_indo1($dataCekProgress->created);?>, <?php echo substr($dataCekProgress->created,-9,-3);?> WIB
                                  </td>
                                </tr><?php
                              }
                            }?>
                          </tbody>
                        </table><?php
                      }else{
                        if(isset($dataSurat) && $dataSurat == 0){?>
                          <div class="alert alert-danger">
                            <p>
                              <strong><i class="ace-icon fa fa-check"></i>Perhatian!</strong>
                              Data status surat tidak ditemukan. Cek kembali nomor surat yang anda masukkan. Terimakasih.
                            </p>
                          </div><?php
                        }else{?>

                <form method="POST" autocomplete="off" action="<?php echo $_SESSION['url'];?>" >
                  <div class="form-group">
                    <label class="label">Masukkan Nomor Surat</label>
                    <div class="input-group">
                      <input  class="form-control" type="text" name="cari" class="form-control" placeholder="Nomor Surat"/>
                      <div class="input-group-append">
                        <span class="input-group-text">
                          <i class="mdi mdi-check-circle-outline"></i>
                        </span>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <button  type="submit"  class="btn btn-primary submit-btn btn-block">Cari</button>
                  </div>
                 
              <!--     <div class="form-group">
                    <button class="btn btn-block g-login">
                     
                      <ul class="auth-footer">
                        <li>
                          <a href="#" style="color: black;">Login</a>
                        </li>
                        <li>
                          <a href="#" style="color: black;"></a>
                        </li>
                        <li>
                          <a href="#" style="color: black;">Kembali</a>
                        </li>
                      </ul>
                    </button>
                  </div> -->
                 
                 
                </form>
              <?php
                }
            }?>

                <div class="text-block text-center my-3">

                    <a href="?op=tracking" class="btn btn-outline-dark">Kembali</a>
                    <a href=".?op=login" class="btn btn-outline-primary">Login</a>
                  
                </div>

              </div>

               
             
           <!--    <p class="footer-text text-center">copyright © 2020 Bootstrapdash. All rights reserved.</p> -->
              
            </div>
          </div>
        </div>
        <!-- content-wrapper ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="login/assets/vendors/js/vendor.bundle.base.js"></script>
    <script src="login/assets/vendors/js/vendor.bundle.addons.js"></script>
    <!-- endinject -->
    <!-- inject:js -->
    <script src="login/assets/js/shared/off-canvas.js"></script>
    <script src="login/assets/js/shared/misc.js"></script>
    <!-- endinject -->
    <script src="login/assets/js/shared/jquery.cookie.js" type="text/javascript"></script>

  <script src="login/assets/vendor/jquery/dist/jquery.min.js"></script>
  <script src="login/assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="login/assets/vendor/js-cookie/js.cookie.js"></script>
  <script src="login/assets/vendor/jquery.scrollbar/jquery.scrollbar.min.js"></script>
  <script src="login/assets/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js"></script>
  <!-- Optional JS -->
  <script src="login/assets/vendor/chart.js/dist/Chart.min.js"></script>
  <script src="login/assets/vendor/chart.js/dist/Chart.extension.js"></script>
  <!-- Argon JS -->
  <script src="login/assets/js/argon.js?v=1.2.0"></script>

    <!-- inline scripts related to this page -->
    <script type="text/javascript">
      jQuery(function($) {
       $(document).on('click', '.toolbar a[data-target]', function(e) {
        e.preventDefault();
        var target = $(this).data('target');
        $('.widget-box.visible').removeClass('visible');//hide others
        $(target).addClass('visible');//show target
       });
      });
      
      
      
      //you don't need this, just used for changing background
      jQuery(function($) {
       $('#btn-login-dark').on('click', function(e) {
        $('body').attr('class', 'login-layout');
        $('#id-text2').attr('class', 'white');
        $('#id-company-text').attr('class', 'blue');
        
        e.preventDefault();
       });
       $('#btn-login-light').on('click', function(e) {
        $('body').attr('class', 'login-layout light-login');
        $('#id-text2').attr('class', 'grey');
        $('#id-company-text').attr('class', 'blue');
        
        e.preventDefault();
       });
       $('#btn-login-blur').on('click', function(e) {
        $('body').attr('class', 'login-layout blur-login');
        $('#id-text2').attr('class', 'white');
        $('#id-company-text').attr('class', 'light-blue');
        
        e.preventDefault();
       });
       
      });
    </script>

  </body>
</html><?php
//}?>