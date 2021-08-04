<?php
error_reporting(0);
ini_set('date.timezone', 'Asia/Jakarta');
require_once "view/indo_tgl.php";
require_once "htmlpurifier/library/HTMLPurifier.auto.php";
$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);
require 'PHPMailer-master/PHPMailerAutoload.php';
function slugify($text){
  // replace non letter or digits by -
  $text = preg_replace('~[^\pL\d]+~u', '-', $text);
  // transliterate
  $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
  // remove unwanted characters
  $text = preg_replace('~[^-\w]+~', '', $text);
  // trim
  $text = trim($text, '-');
  // remove duplicate -
  $text = preg_replace('~-+~', '-', $text);
  // lowercase
  $text = strtolower($text);
  if (empty($text)) {
    return 'n-a';
  }
  return $text;
}
if(isset($_SESSION['atra_id']) AND isset($_SESSION['atra_pass'])){
  $CekAkses = $this->model->selectprepare("user_level", $field=null, $params=null, $where=null, "WHERE id_user='$_SESSION[id_user]'");
  $HakAkses = $CekAkses->fetch(PDO::FETCH_OBJ);?>
  <!DOCTYPE html>
  <html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>SITERAPKAN</title>
    <style type="text/css">
      .garis_tepi1 {
       border: 4px solid #D8D2D2;
     }
     .garis_tepi2 {
       border: 4px solid #8AF76F;
     }
   </style>
   <!-- Tell the browser to be responsive to screen width -->
   <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
   <!-- Bootstrap 3.3.7 -->
   <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
   <!-- Font Awesome -->
   <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
   <!-- Ionicons -->
   <link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">
   <!-- Theme style -->
   <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
   folder instead of downloading all of them to reduce the load. -->
   <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
   <!-- Morris chart -->
   <link rel="stylesheet" href="bower_components/morris.js/morris.css">
   <!-- jvectormap -->
   <link rel="stylesheet" href="bower_components/jvectormap/jquery-jvectormap.css">
   <!-- Date Picker -->
   <link rel="stylesheet" href="bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
   <!-- Select2 -->
   <link rel="stylesheet" href="bower_components/select2/dist/css/select2.min.css">
   <link rel="stylesheet" href="bower_components/select2/dist/css/select3.min.css">
   <!-- Daterange picker -->
   <link rel="stylesheet" href="bower_components/bootstrap-daterangepicker/daterangepicker.css">
   <!-- bootstrap wysihtml5 - text editor -->
   <link rel="stylesheet" href="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
   <!-- iCheck for checkboxes and radio inputs -->
   <link rel="stylesheet" href="plugins/iCheck/all.css">
   <!-- Bootstrap Color Picker -->
   <link rel="stylesheet" href="bower_components/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css">
   <!-- Bootstrap time Picker -->
   <link rel="stylesheet" href="plugins/timepicker/bootstrap-timepicker.min.css">
   <!-- Select2 -->

   <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
   <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->

<!-- Google Font -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>

<body class="hold-transition skin-blue sidebar-mini">
  <div class="wrapper">

    <header class="main-header">
      <!-- Logo -->
      <a href="index2.html" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b></b>SITERAPKAN</span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b>SITERAPKAN</b></span>
      </a>
      <!-- Header Navbar: style can be found in header.less -->
      <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
          <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">
         <ul class="nav navbar-nav">
          <!-- Messages: style can be found in dropdown.less-->
          <li class="dropdown messages-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <?php
              $cekSM = $this->model->selectprepare("arsip_sm a", $field=null, $params=null, $where=null, "WHERE a.tujuan_surat LIKE '%\"$_SESSION[id_user]\"%' AND a.id_sm NOT IN (SELECT b.id_sm FROM surat_read b WHERE b.id_user='$_SESSION[id_user]' AND b.kode='SM')");

              $cekSM2 = $this->model->selectprepare("arsip_sm a", $field=null, $params=null, $where=null, "WHERE a.tujuan_surat LIKE '%\"$_SESSION[id_user]\"%' AND a.id_sm NOT IN (SELECT b.id_sm FROM surat_read b WHERE b.id_user='$_SESSION[id_user]' AND b.kode='SM')", "ORDER BY a.tgl_terima DESC LIMIT 3");
              $teks = "Surat masuk baru";
              $teks1 = "Lihat semua surat";
              $link="./index.php?op=memo";
              while($dataSM= $cekSM2->fetch(PDO::FETCH_OBJ)){
                $dumpSM[]=$dataSM;
              }
              if($cekSM->rowCount() >= 1){?>
                <i class="ace-icon fa fa-envelope-o icon-animated-bell"></i>
                <span class="badge badge-important"><?php echo $cekSM->rowCount();?></span><?php
              }else{?>
                <i class="ace-icon fa fa-envelope-o"></i><?php
              }?>


            </a>
            <ul class="dropdown-menu">
              <li class="dropdown-header">
                <i class="ace-icon fa fa-exclamation-triangle"></i><?php
                if($cekSM->rowCount() >= 1){?>
                  <?php echo $cekSM->rowCount();?> <?php echo $teks;
                }else{
                  echo "Tidak ada ".$teks;
                }?>
              </li>
              <li class="dropdown-content">
                <ul class="dropdown-menu dropdown-navbar navbar-pink"><?php
                if($cekSM->rowCount() >= 1){
                  foreach($dumpSM as $key => $object){
                    $params = array(':id_user' => $object->id_user);
                    $cek_pengirim = $this->model->selectprepare("user", $field=null, $params, "id_user=:id_user", $order=null);
                    $data_cek_pengirim = $cek_pengirim->fetch(PDO::FETCH_OBJ);?>
                    <li>
                      <a href="./index.php?op=memo&memoid=<?php echo $object->id_sm;?>" class="clearfix">
                        <img src="assets/images/avatars/<?php echo $data_cek_pengirim->picture;?>" class="msg-photo" alt="User" />
                        <span class="msg-body">
                          <span class="msg-title">
                            <span class="blue"><?php echo $data_cek_pengirim->nama;?></span>
                            <?php echo $object->perihal;?>
                          </span>
                          <span class="msg-time">
                            <i class="ace-icon fa fa-clock-o"></i>
                            <span><?php echo tgl_indo($object->tgl_terima);?></span>
                          </span>
                        </span>
                      </a>
                      </li><?php
                    }
                  }?>
                </ul>
              </li>
              <li class="dropdown-footer">
                <a href="<?php echo $link;?>">
                  <?php echo $teks1;?>
                  <i class="ace-icon fa fa-arrow-right"></i>
                </a>
              </li>
              <!-- <li class="footer"><a href="#">See All Messages</a></li> -->
            </ul>
          </li>

          <!-- Notifications: style can be found in dropdown.less -->
          <li class="dropdown notifications-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <!-- <i class="fa fa-bell-o"></i>
                <span class="label label-warning">10</span> -->
                <?php
                $field = array("a.id_user as userDis","a.*","b.*");
                $cekDispo = $this->model->selectprepare("memo a join arsip_sm b on a.id_sm=b.id_sm", $field, $params=null, $where=null, "WHERE a.disposisi LIKE '%\"$_SESSION[id_user]\"%' AND a.id_sm NOT IN (SELECT c.id_sm FROM surat_read c WHERE c.id_user='$_SESSION[id_user]' AND c.kode='DIS')");

                $cekDispo2 = $this->model->selectprepare("memo a join arsip_sm b on a.id_sm=b.id_sm", $field, $params=null, $where=null, "WHERE a.disposisi LIKE '%\"$_SESSION[id_user]\"%' AND a.id_sm NOT IN (SELECT c.id_sm FROM surat_read c WHERE c.id_user='$_SESSION[id_user]' AND c.kode='DIS') ORDER BY b.tgl_terima DESC LIMIT 3");

                $teks = "Disposisi baru";
                $teks1 = "Lihat semua dispposisi";
                $link="./index.php?op=disposisi";
                while($dataDispo= $cekDispo2->fetch(PDO::FETCH_OBJ)){
                  $dumpDispo[]=$dataDispo;
                }
                if($cekDispo->rowCount() >= 1){?>
                  <i class="ace-icon fa fa-external-link-square icon-animated-vertical"></i>
                  <span class="badge badge-important"><?php echo $cekDispo->rowCount();?></span><?php
                }else{?>
                  <i class="ace-icon fa fa-external-link-square"></i><?php
                }?>
              </a>
              <ul class="dropdown-menu">
                <li class="dropdown-header">
                  <i class="ace-icon fa fa-check"></i><?php
                  if($cekDispo->rowCount() >= 1){?>
                    <?php echo $cekDispo->rowCount();?> <?php echo $teks;
                  }else{
                    echo "Tidak ada ".$teks;
                  }?>
                </li>

                <li class="dropdown-content">
                  <ul class="dropdown-menu dropdown-navbar"><?php
                  if($cekDispo->rowCount() >= 1){
                    foreach($dumpDispo as $key => $object){
                      $params = array(':id_user' => $object->userDis);
                      $cek_pengirim = $this->model->selectprepare("user", $field=null, $params, "id_user=:id_user", $order=null);
                      $data_cek_pengirim = $cek_pengirim->fetch(PDO::FETCH_OBJ);
                      $tgl_disposisi= substr($object->tgl,0,10); ?>
                      <li>
                        <a href="./index.php?op=disposisi&smid=<?php echo $object->id_sm;?>&id_user=<?php echo $data_cek_pengirim->id_user;?>" class="clearfix">
                          <img src="assets/images/avatars/<?php echo $data_cek_pengirim->picture;?>" class="msg-photo" alt="User" />
                          <span class="msg-body">
                            <span class="msg-title">
                              <span class="blue"><?php echo $data_cek_pengirim->nama;?></span>
                              <?php echo $object->perihal;?>
                            </span>
                            <span class="msg-time">
                              <i class="ace-icon fa fa-clock-o"></i>
                              <span><?php echo tgl_indo($tgl_disposisi);?></span>
                            </span>
                          </span>
                        </a>
                        </li><?php
                      }
                    }?>
                  </ul>
                </li>

                <li class="dropdown-footer">
                  <a href="<?php echo $link;?>">
                    <?php echo $teks1;?>
                    <i class="ace-icon fa fa-arrow-right"></i>
                  </a>
                </li>
                <!-- inner menu: contains the actual data -->
                
              </ul>
            </li>
            <!-- Tasks: style can be found in dropdown.less -->
            <li class="dropdown tasks-menu">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <!-- <i class="fa fa-flag-o"></i>
                <span class="label label-danger">9</span> -->
                <?php
                $field = array("a.id_user as userDis","a.*","b.*");
                $cekTembusan = $this->model->selectprepare("memo a join arsip_sm b on a.id_sm=b.id_sm", $field, $params=null, $where=null, "WHERE a.tembusan LIKE '%\"$_SESSION[id_user]\"%' AND a.id_sm NOT IN (SELECT c.id_sm FROM surat_read c WHERE c.id_user='$_SESSION[id_user]' AND c.kode='CC')");

                $cekTembusan2 = $this->model->selectprepare("memo a join arsip_sm b on a.id_sm=b.id_sm", $field, $params=null, $where=null, "WHERE a.tembusan LIKE '%\"$_SESSION[id_user]\"%' AND a.id_sm NOT IN (SELECT c.id_sm FROM surat_read c WHERE c.id_user='$_SESSION[id_user]' AND c.kode='CC') ORDER BY a.tgl DESC LIMIT 3");

                $teks = "Tembusan surat baru";
                $teks1 = "Lihat tembusan surat";
                $link="./index.php?op=sm";
                while($dataTembusan = $cekTembusan2->fetch(PDO::FETCH_OBJ)){
                  $dumpTembusan[]=$dataTembusan;
                }
                if($cekTembusan->rowCount() >= 1){?>
                  <i class="ace-icon fa fa-repeat icon-animated-bell"></i>
                  <span class="badge badge-success"><?php echo $cekTembusan->rowCount();?></span><?php
                }else{?>
                  <i class="ace-icon fa fa-repeat"></i><?php
                }?>
              </a>
              <ul class="dropdown-menu">
                <li class="dropdown-header">
                  <i class="ace-icon fa fa-envelope-o"></i><?php
                  if($cekTembusan->rowCount() >= 1){?>
                    <?php echo $cekTembusan->rowCount();?> <?php echo $teks;
                  }else{
                    echo "Tidak ada ".$teks;
                  }?>
                </li>
                <li class="dropdown-content">
                  <ul class="dropdown-menu dropdown-navbar"><?php
                  if($cekTembusan->rowCount() >= 1){
                    foreach($dumpTembusan as $key => $object){
                      $params = array(':id_user' => $object->userDis);
                      $cek_pengirim = $this->model->selectprepare("user", $field=null, $params, "id_user=:id_user", $order=null);
                      $data_cek_pengirim = $cek_pengirim->fetch(PDO::FETCH_OBJ);
                      $tgl_tembusan= substr($object->tgl,0,10);?>
                      <li>
                        <a href="./index.php?op=sm&smid=<?php echo $object->id_sm;?>" class="clearfix">
                          <img src="assets/images/avatars/<?php echo $data_cek_pengirim->picture;?>" class="msg-photo" alt="User" />
                          <span class="msg-body">
                            <span class="msg-title">
                              <span class="blue"><?php echo $data_cek_pengirim->nama;?></span>
                              <?php echo $object->perihal;?>
                            </span>
                            <span class="msg-time">
                              <i class="ace-icon fa fa-clock-o"></i>
                              <span><?php echo tgl_indo($tgl_tembusan);?></span>
                            </span>
                          </span>
                        </a>
                        </li><?php
                      }
                    }?>
                  </ul>
                </li>

                <li class="dropdown-footer">
                  <a href="<?php echo $link;?>">
                    <?php echo $teks1;?>
                    <i class="ace-icon fa fa-arrow-right"></i>
                  </a>
                </li>

              </ul>
            </li>

            <?php 
            if($cekSM->rowCount() >= 1 OR $cekDispo->rowCount() >= 1 OR $cekTembusan->rowCount() >= 1){ ?>
              <audio autoplay src="./view/notif.mp3"></audio> <?php
            } ?>
            <!-- User Account: style can be found in dropdown.less -->
            <li class="dropdown user user-menu">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <img src="assets/images/avatars/<?php echo $_SESSION['picture'];?>" class="user-image" alt="User Image">
                <span class="hidden-xs"><?php echo $_SESSION['nama'];?>
                
              </span>
            </a>

            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="dist/img/user2-160x160.png" class="img-circle" alt="User Image">

                <p>
                  <?php echo $_SESSION['nama'];?>
                </p>
              </li>
              <!-- Menu Body -->
             <!--  <li class="user-body">
                <div class="row">
                  <div class="col-xs-4 text-center">
                    <a href="./index.php?op=user">User</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="#">Profile</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="#">Keluar</a>
                  </div>
                </div> -->
                <!-- /.row -->
                <!--   </li> -->
                <!-- Menu Footer-->
                <?php
                if($_SESSION['hakakses'] == "Admin"){?>
                  <!--<li>
                    <a href="#">
                      <i class="ace-icon fa fa-cog"></i>
                      Settings
                    </a>
                  </li>-->
                  <li>
                    <a href="./index.php?op=user">
                      <i class="ace-icon fa fa-user"></i>
                      User
                    </a>
                    </li><?php
                  }?>

                  <li class="divider"></li>
                  <li>
                    <a href="./index.php?op=profil">
                      <i class="ace-icon fa fa-user"></i>
                      Profile
                    </a>
                  </li>
                  <li>
                    <a href="./index.php?op=login">
                      <i class="ace-icon fa fa-power-off"></i>
                      Logout
                    </a>
                  </li>
                </ul>
              </li>
              <!-- Control Sidebar Toggle Button -->
         <!--  <li>
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li> -->
        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="dist/img/user2-160x160.png" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p><?php echo $_SESSION['nama'];?></p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      <!-- search form -->
      <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
          <span class="input-group-btn">
            <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
            </button>
          </span>
        </div>
      </form>
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu nav nav-list " data-widget="tree">
        <?php
        if(isset($_GET['op']) AND ($_GET['op'] == "sm" OR $_GET['op'] == "add_sm" OR $_GET['op'] == "sm_expired")){
          $StatSM = 'active open';
          if($_GET['op'] == "sm"){ $StatDataSM = 'active'; }else{ $StatDataSM = ''; }
          if($_GET['op'] == "add_sm"){ $StatEntriSM = 'active'; }else{ $StatEntriSM = ''; }
          if($_GET['op'] == "sm_expired"){ $StatSMeXp = 'active'; }else{ $StatSMeXp = ''; }
        }else{
          $StatSM = '';
        }
        if(isset($_GET['op']) AND ($_GET['op'] == "sk" OR $_GET['op'] == "add_sk")){
          $StatSK = 'active open';
          if($_GET['op'] == "sk"){ $StatDataSK = 'active'; }else{ $StatDataSK = ''; }
          if($_GET['op'] == "add_sk"){ $StatEntriSK = 'active'; }else{ $StatEntriSK = ''; }
        }else{
          $StatSK = '';
        }


        // else if(isset($_GET['op']) AND $_GET['op'] == "add_panduan"){
        //   if($HakAkses->panduan == "W"){
        //     require_once "file_panduan/input_panduan.php";
        //   }else{
        //     require_once "invalid_akses.php";
        //   }

        if(isset($_GET['op']) AND ($_GET['op'] == "data_memo" OR $_GET['op'] == "add_memo")){
          $StatArsipMemo = 'active open';
          if($_GET['op'] == "data_memo"){ $StatDataMemo = 'active'; }else{ $StatDataMemo = ''; }
          if($_GET['op'] == "add_memo"){ $StatEntriMemo = 'active'; }else{ $StatEntriMemo = ''; }
        }else{
          $StatArsipMemo = '';
        }
        if(isset($_GET['op']) AND ($_GET['op'] == "report_sm" OR $_GET['op'] == "report_sk" OR $_GET['op'] == "report_disposisi" OR $_GET['op'] == "report_arsip" OR $_GET['op'] == "report_progress")){
          $StatReport = 'active open';
          if($_GET['op'] == "report_sm"){ $StatRSM = 'active'; }else{ $StatRSM = ''; }
          if($_GET['op'] == "report_sk"){ $StatRSK = 'active'; }else{ $StatRSK = ''; }
          if($_GET['op'] == "report_disposisi"){ $StatDIS = 'active'; }else{ $StatDIS = ''; }
          if($_GET['op'] == "cari_arsip"){ $StatCariArsip = 'active'; }else{ $StatCariArsip = ''; }
          if($_GET['op'] == "report_arsip"){ $StatReportArsip = 'active'; }else{ $StatReportArsip = ''; }
          if($_GET['op'] == "report_progress"){ $StatReportProgress = 'active'; }else{ $StatReportProgress = ''; }
        }else{
          $StatReport = $StatCariArsip = '';
        }
        if(isset($_GET['op']) AND ($_GET['op'] == "arsip_file")){
          $StatArsipLeader = 'active open';
          if($_GET['op'] == "arsip_file"){ $StatArsipFileView = 'active'; }else{ $StatArsipFileView = ''; }
        }else{
          $StatArsipLeader = '';
        }

        if(isset($_GET['op']) AND ($_GET['op'] == "arsip_file" OR $_GET['op'] == "add_arsip" OR $_GET['op'] == "cari_arsip")){
          $StatArsipFile = 'active open';

          if($_GET['op'] == "add_arsip"){ $StatArsipFileEntri = 'active'; }else{ $StatArsipFileEntri = ''; }
          if($_GET['op'] == "arsip_file"){ $StatArsipFileView = 'active'; }else{ $StatArsipFileView = ''; }

          if($_GET['op'] == "cari_arsip"){ $StatCariFile = 'active'; }else{ $StatCariFile = ''; }
        }else{
          $StatArsipFile = $StatArsipFileEntri = $StatArsipFileView = $StatCariFile = '';
        }

        if(isset($_GET['op']) AND ($_GET['op'] == "klasifikasi" OR $_GET['op'] == "klasifikasi_sk" OR $_GET['op'] == "user" OR $_GET['op'] == "setting" OR $_GET['op'] == "klasifikasi_file")){
          $StatAtur = 'active open';
        }else{
          $StatAtur = '';
        }

        if(isset($_GET['op']) AND $_GET['op'] == "arsip_sk"){ $StatArsipSK = 'active open'; }else{ $StatArsipSK = ''; }
        if(isset($_GET['op']) AND $_GET['op'] == "arsip_sm"){ $StatArsipSM = 'active open'; }else{ $StatArsipSM = ''; }
        if(isset($_GET['op']) AND $_GET['op'] == "user"){ $StatUser = 'active open'; }else{ $StatUser = ''; }
        if(isset($_GET['op']) AND $_GET['op'] == "panduan"){ $StatPanduan = 'active open'; }else{ $StatPanduan = ''; }
        if(isset($_GET['op']) AND $_GET['op'] == "editpanduan"){ $StatEditPanduan = 'active open'; }else{ $StatEditPanduan = ''; }
        if(isset($_GET['op']) AND $_GET['op'] == "setting"){ $StatSetting = 'active open'; }else{ $StatSetting = ''; }
        if(isset($_GET['op']) AND $_GET['op'] == "klasifikasi_file"){ $StatKlasFile = 'active open'; }else{ $StatKlasFile = ''; }
        if(isset($_GET['op']) AND $_GET['op'] == "klasifikasi"){ $StatKlasSM = 'active open'; }else{ $StatKlasSM = ''; }
        if(isset($_GET['op']) AND $_GET['op'] == "klasifikasi_sk"){ $StatKlasSK = 'active open'; }else{ $StatKlasSK = ''; }
        if(isset($_GET['op']) AND $_GET['op'] == "memo"){ $StatMemo = 'active open'; }else{ $StatMemo = ''; }
        if(isset($_GET['op']) AND $_GET['op'] == "disposisi"){ $StatDisposisi = 'active open'; }else{ $StatDisposisi = ''; }
        if(isset($_GET['op']) AND $_GET['op'] == "tembusan"){ $StatTembusan = 'active open'; }else{ $StatTembusan = ''; }
        if(isset($_GET['op']) AND $_GET['op'] == "info"){ $StatInfo = 'active open'; }else{ $StatInfo = ''; }


        // if(isset($_GET['op']) AND $_GET['op'] == "panduan"){ $StatPanduan = 'active open'; }else{ $StatPanduan = ''; }

        if(!isset($_GET['op'])){
          $StatBeranda = 'active';
        }else{
          $StatBeranda = '';
        }?>


        <li class="<?php echo $StatBeranda;?>">
          <a href="./">
            <i class="menu-icon fa fa-tachometer"></i>
            <span class="menu-text"> Dashboard </span>
          </a>

          <b class="arrow"></b>
        </li>

        <li class="<?php echo $StatMemo;?>">
          <a href="index.php?op=memo">
            <i class="menu-icon fa fa-envelope-o"></i>
            <span class="menu-text"> Surat Masuk </span><?php
            if($cekSM->rowCount() >= 1){?>
              <span class="badge badge-warning"><?php echo $cekSM->rowCount();?></span><?php
            }?>
          </a>
          <b class="arrow"></b>
        </li>
        <li class="<?php echo $StatDisposisi;?>">
          <a href="index.php?op=disposisi">
            <i class="menu-icon fa fa-external-link-square"></i>
            <span class="menu-text"> Disposisi </span><?php
            if($cekDispo->rowCount() >= 1){?>
              <span class="badge badge-warning"><?php echo $cekDispo->rowCount();?></span><?php
            }?>
          </a>
          <b class="arrow"></b>
        </li>
        <li class="<?php echo $StatTembusan;?>">
          <a href="index.php?op=tembusan">
            <i class="menu-icon fa fa-repeat"></i>
            <span class="menu-text"> Tembusan </span><?php
            if($cekTembusan->rowCount() >= 1){?>
              <span class="badge badge-warning"><?php echo $cekTembusan->rowCount();?></span><?php
            }?>
          </a>
          <b class="arrow"></b>
          </li><?php
          /*Cek Info Memo*/
          $cekInfo = $this->model->selectprepare("info a", $field=null, $params=null, $where=null, "WHERE a.tujuan_info LIKE '%\"$_SESSION[id_user]\"%' AND a.id_info NOT IN (SELECT b.id_sm FROM surat_read b WHERE b.id_user='$_SESSION[id_user]' AND b.kode='INFO')");
          $cekInfo2 = $this->model->selectprepare("info a", $field=null, $params=null, $where=null, "WHERE a.tujuan_info LIKE '%\"$_SESSION[id_user]\"%' AND a.id_info NOT IN (SELECT b.id_sm FROM surat_read b WHERE b.id_user='$_SESSION[id_user]' AND b.kode='INFO')", "ORDER BY a.tgl_info DESC LIMIT 6");
          $teks = "Memo masuk baru";
          $teks1 = "Lihat semua memo";
          $link="./index.php?op=memo";
          while($DataInfo= $cekInfo2->fetch(PDO::FETCH_OBJ)){
            $dumpInfo[]=$DataInfo;
          }?>
          <li class="<?php echo $StatInfo;?>">
            <a href="index.php?op=info"><i class="menu-icon fa fa-weixin"></i>
              <span class="menu-text"> Memo </span><?php
              if($cekInfo->rowCount() >= 1){?>
                <span class="badge badge-warning"><?php echo $cekInfo->rowCount();?></span><?php
              }?>
            </a>
            <b class="arrow"></b>
            </li><?php
            if($HakAkses->sm == "W"){?>
              <li class="<?php echo $StatSM;?> treeview">
                <a href="#" >
                  <i class="menu-icon fa fa-inbox"></i>
                  <span >
                    Arsip Surat Masuk
                  </span>
                  <b class="arrow fa fa-angle-down"></b>
                </a>
                <ul class="treeview-menu">
                  <li class="<?php echo $StatEntriSM;?>">
                    <a href="./index.php?op=add_sm">
                      <i class="fa fa-circle-o"></i>
                      Entri baru
                    </a>
                  </li>
                  <li class="<?php echo $StatDataSM;?>">
                    <a href="./index.php?op=sm">
                      <i class="fa fa-circle-o"></i>
                      Data surat masuk
                    </a>
                    <b class="arrow"></b>
                  </li>
                  <li class="<?php echo $StatSMeXp;?>">
                    <a href="./index.php?op=sm_expired">
                      <i class="fa fa-circle-o"></i>
                      Surat jatuh tempo
                    </a>
                    <b class="arrow"></b>
                  </li>
                </ul>
                </li><?php
              }
              if($HakAkses->sm == "R"){?>
                <li class="<?php echo $StatArsipSM;?>">
                  <a href="./index.php?op=arsip_sm">
                    <i class="menu-icon fa fa-inbox"></i>
                    <span class="menu-text"> Arsip Surat Masuk</span>
                  </a>
                  <b class="arrow"></b>
                </li>
                <li class="<?php echo $StatSMeXp;?>">
                  <a href="./index.php?op=sm_expired">
                    <i class="menu-icon fa fa-caret-right"></i>
                    Surat jatuh tempo
                  </a>
                  <b class="arrow"></b>
                  </li><?php
                }
                if($HakAkses->sk == "W"){?>
                  <li class="<?php echo $StatSK;?> treeview">
                    <a href="#">
                      <i class="menu-icon fa fa-send-o"></i>
                      <span >
                        Arsip Surat Keluar
                      </span>
                      <b class="arrow fa fa-angle-down"></b>
                    </a>
                    <ul class="treeview-menu">
                      <li class="<?php echo $StatEntriSK;?>">
                        <a href="./index.php?op=add_sk">
                          <i class="fa fa-circle-o"></i>
                          Entri baru
                        </a>
                        <b class="arrow"></b>
                      </li>
                      <li class="<?php echo $StatDataSK;?>">
                        <a href="./index.php?op=sk">
                          <i class="fa fa-circle-o"></i>
                          Data surat keluar
                        </a>
                        <b class="arrow"></b>
                      </li>
                    </ul>
                    </li><?php
                  }
                  if($HakAkses->sk == "R"){?>
                    <li class="<?php echo $StatArsipSK;?>">
                      <a href="./index.php?op=arsip_sk">
                        <i class="menu-icon fa fa-tags"></i>
                        <span class="menu-text"> Arsip Surat Keluar</span>
                      </a>
                      <b class="arrow"></b>
                      </li><?php
                    }
                    if($HakAkses->info == "Y"){?>
                      <li class="treeview <?php echo $StatArsipMemo;?>">
                        <a href="#" >
                          <i class="menu-icon fa fa-flickr"></i>
                          <span class="menu-text">
                            Arsip Memo
                          </span>
                          <b class="arrow fa fa-angle-down"></b>
                        </a>
                        <ul class="treeview-menu">
                          <li class="<?php echo $StatEntriMemo;?>">
                            <a href="./index.php?op=add_memo">
                              <i class="fa fa-circle-o"></i>
                              Entri baru
                            </a>
                          </li>
                          <li class="<?php echo $StatDataMemo;?>">
                            <a href="./index.php?op=data_memo">
                              <i class="fa fa-circle-o"></i>
                              Data Memo
                            </a>
                          </li>
                        </ul>
                        </li><?php
                      }
                      if($HakAkses->arsip == "W"){?>            
                        <li class="<?php echo $StatArsipFile;?> treeview">
                          <a href="#" >
                            <i class="menu-icon fa fa-th-large"></i>
                            <span class="menu-text">
                              Arsip Digital
                            </span>
                            <b class="arrow fa fa-angle-down"></b>
                          </a>
                          <ul class="treeview-menu">
                            <li class="<?php echo $StatArsipFileEntri;?>">
                              <a href="./index.php?op=add_arsip">
                                <i class="fa fa-circle-o"></i>
                                Entri baru
                              </a>
                            </li>
                            <li class="<?php echo $StatArsipFileView;?>">
                              <a href="./index.php?op=arsip_file">
                                <i class="fa fa-circle-o"></i>
                                Data file arsip
                              </a>
                            </li>
                            <li class="<?php echo $StatCariFile;?>">
                              <a href="./index.php?op=cari_arsip">
                                <i class="fa fa-circle-o"></i>
                                Pencarian Arsip
                              </a>
                              <b class="arrow"></b>
                            </li>
                          </ul>
                          </li><?php
                        }
                        if($HakAkses->arsip == "R"){?>
                          <li class="<?php echo $StatArsipFileView;?>">
                            <a href="./index.php?op=arsip_file">
                              <i class="menu-icon fa fa-th-large"></i>
                              Data Arsip Digital
                            </a>
                            <b class="arrow"></b>
                            </li><?php
                          }
                          if($HakAkses->atur_layout == "Y" OR $HakAkses->atur_klasifikasi_sm == "Y" OR $HakAkses->atur_klasifikasi_sk == "Y" OR $HakAkses->atur_klasifikasi_arsip == "Y" OR $HakAkses->atur_user == "Y"){?>
                            <li class="<?php echo $StatAtur;?> treeview">
                              <a href="#">
                                <i class="menu-icon fa fa-cogs"></i>
                                <span class="menu-text">
                                  Pengaturan
                                </span>
                                <b class="arrow fa fa-angle-down"></b>
                              </a>
                              <ul class="treeview-menu">
                <?php /*
                <li class="<?php echo $StatAsalSurat;?>">
                  <a href="./index.php?op=asalsurat">
                    <i class="menu-icon glyphicon glyphicon-repeat"></i>
                    <span class="menu-text"> Asal Surat </span>
                  </a>
                  <b class="arrow"></b>
                  </li>*/
                  if($HakAkses->atur_layout == "Y"){?>
                    <li class="<?php echo $StatSetting;?>">
                      <a href="./index.php?op=setting">
                        <i class="menu-icon fa fa-cog"></i>
                        <span class="menu-text"> Atur Layout </span>
                      </a>
                      </li><?php
                    }
                    if($HakAkses->panduan == "Y"){?>
                      <li class="<?php echo $StatPanduan;?>">
                        <a href="./index.php?op=panduan">
                          <i class="menu-icon fa fa-cog"></i>
                          <span class="menu-text"> Atur Panduan</span>
                        </a>
                        </li><?php
                      }
                      if($HakAkses->atur_klasifikasi_sm == "Y"){?>
                        <li class="<?php echo $StatKlasSM;?>">
                          <a href="./index.php?op=klasifikasi">
                            <i class="menu-icon fa fa-tags"></i>
                            <span class="menu-text"> Klasifikasi Surat Masuk </span>
                          </a>
                          </li><?php
                        }
                        if($HakAkses->atur_klasifikasi_sk == "Y"){?>
                          <li class="<?php echo $StatKlasSK;?>">
                            <a href="./index.php?op=klasifikasi_sk">
                              <i class="menu-icon fa fa-tags"></i>
                              <span class="menu-text"> Klasifikasi Surat Keluar </span>
                            </a>
                            </li><?php
                          }
                /* if($HakAkses->atur_klasifikasi_sk == "Y"){?>
                  <li class="<?php echo $StatKlasifikasiSK;?>">
                    <a href="./index.php?op=klasifikasi_sk">
                      <i class="menu-icon fa fa-tags"></i>
                      <span class="menu-text"> Klasifikasi Surat Keluar </span>
                    </a>
                    <b class="arrow"></b>
                  </li><?php
                } */
                if($HakAkses->atur_klasifikasi_arsip == "Y"){?>
                  <li class="<?php echo $StatKlasFile;?>">
                    <a href="./index.php?op=klasifikasi_file">
                      <i class="menu-icon fa fa-file-text"></i>
                      <span class="menu-text"> Klasifikasi File Arsip </span>
                    </a>
                    </li><?php
                  }
                  if($HakAkses->atur_user == "Y"){?>
                    <li class="<?php echo $StatUser;?>">
                      <a href="./index.php?op=user">
                        <i class="menu-icon fa fa-user"></i>
                        <span class="menu-text"> Data User </span>
                      </a>
                      </li><?php
                    }?>
                  </ul>
                  </li><?php
                }



                


                if($HakAkses->report_sm == "Y" OR $HakAkses->report_sk == "Y" OR $HakAkses->report_arsip == "Y"){?> 
                  <li class="<?php echo $StatReport;?> treeview ">
                    <a href="#" class="dropdown-toggle">
                      <i class="menu-icon fa fa-bar-chart"></i>
                      <span class="menu-text">
                        Laporan
                      </span>
                      <b class="arrow fa fa-angle-down"></b>
                    </a>
                    <ul class="treeview-menu"><?php
                    if($HakAkses->report_dispo == "Y"){?>
                      <li class="<?php echo $StatDIS;?>">
                        <a href="./index.php?op=report_disposisi">
                          <i class="fa fa-circle-o"></i>
                          Disposisi
                        </a>
                        <b class="arrow"></b>
                        </li><?php
                      }
                      if($HakAkses->report_progress == "Y"){?>
                        <li class="<?php echo $StatReportProgress;?>">
                          <a href="./index.php?op=report_progress">
                            <i class="fa fa-circle-o"></i>
                            Progress Surat
                          </a>
                          <b class="arrow"></b>
                          </li><?php
                        }
                        if($HakAkses->report_sm == "Y"){?>
                          <li class="<?php echo $StatRSM;?>">
                            <a href="./index.php?op=report_sm">
                              <i class="fa fa-circle-o"></i>
                              Surat Masuk
                            </a>
                            <b class="arrow"></b>
                            </li><?php
                          }
                          if($HakAkses->report_sk == "Y"){?>
                            <li class="<?php echo $StatRSK;?>">
                              <a href="./index.php?op=report_sk">
                                <i class="fa fa-circle-o"></i>
                                Surat Keluar
                              </a>
                              <b class="arrow"></b>
                              </li><?php
                            }
                            if($HakAkses->report_arsip == "Y"){?>
                              <li class="<?php echo $StatReportArsip;?>">
                                <a href="./index.php?op=report_arsip">
                                  <i class="fa fa-circle-o"></i>
                                  Data Arsip Digital
                                </a>
                                <b class="arrow"></b>
                                </li><?php
                              }?>
                            </ul>
                            </li><?php
                          }

                         /* if($HakAkses-> == "Y"){?>
                            <li class="<?php echo $StatPanduan;?>">
                              <a href="#">
                                <i class="fa fa-circle-o"></i>
                                Panduan
                              </a>
                              <b class="arrow"></b>
                              </li><?php
                            }?>
                          </ul>
                          </li><?php
                        } */


                        ?>

                        <li class="">
                          <a href="./file_panduan/file_panduan_1.pdf" target="blank">
                            <i class="menu-icon fa fa-th-large"></i>
                            Panduan Penggunaan Web 
                          </a>
                          <b class="arrow"></b>
                        </li>
                      </ul>


                    </section>
                    <!-- /.sidebar -->
                  </aside>

                  <!-- Content Wrapper. Contains page content -->
                  <div class="content-wrapper">
                    <!-- Content Header (Page header) -->
    <!-- <section class="content-header">
      <h1>
        Dashboard
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>


    </section> -->

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      

      <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
          <ul class="breadcrumb">
            <li>
              <i class="ace-icon fa fa-home home-icon"></i>
              <a href="./">Home</a>
            </li>
            <li class="active">Dashboard</li>
          </ul><!-- /.breadcrumb -->

          <?php
          if(isset($_GET['op']) AND $_GET['op'] == "sm"){
            $titlePlace = "Cari Surat Masuk ...";
            $value = "sm";
          }elseif(isset($_GET['op']) AND $_GET['op'] == "arsip_sm"){
            $titlePlace = "Cari Surat Masuk ...";
            $value = "arsip_sm";
          }elseif(isset($_GET['op']) AND $_GET['op'] == "sm_expired"){
            $titlePlace = "Cari Surat Masuk ...";
            $value = "sm_expired";
          }elseif(isset($_GET['op']) AND $_GET['op'] == "arsip_sk"){
            $titlePlace = "Cari Surat Keluar ...";
            $value = "arsip_sk";
          }elseif(isset($_GET['op']) AND $_GET['op'] == "sk"){
            $titlePlace = "Cari Surat Keluar ...";
            $value = "sk";
          }elseif(isset($_GET['op']) AND $_GET['op'] == "memo"){
            $titlePlace = "Cari Surat Masuk ...";
            $value = "memo";
          }elseif(isset($_GET['op']) AND $_GET['op'] == "disposisi"){
            $titlePlace = "Cari Disposisi ...";
            $value = "disposisi";
          }elseif(isset($_GET['op']) AND $_GET['op'] == "tembusan"){
            $titlePlace = "Cari Tembusan ...";
            $value = "tembusan";
          }elseif(isset($_GET['op']) AND $_GET['op'] == "arsip_file"){
            $titlePlace = "Cari Arsip ...";
            $value = "arsip_file";
          }elseif(isset($_GET['op']) AND $_GET['op'] == "info"){
            $titlePlace = "Cari Memo ...";
            $value = "info";
          }elseif(isset($_GET['op']) AND $_GET['op'] == "data_memo"){
            $titlePlace = "Cari Arsip Memo ...";
            $value = "data_memo";
          }else{
            $titlePlace = null;
          }
          if($titlePlace != null){?>
            <div class="nav-search" id="nav-search">
                 <!--  <form class="form-search" method="GET" action="<?php echo $_SESSION['url'];?>">
                    <span class="input-icon">
                      <input type="hidden" name="op" value="<?php echo $value;?>"/>
                      <input type="text" placeholder="<?php echo $titlePlace;?>" class="nav-search-input" name="keyword" autocomplete="off" />
                      <i class="ace-icon fa fa-search nav-search-icon"></i>
                    </span>
                    <div class="box-tools">
                 
                  </div>
                </form> -->
                <form class="search-form" method="GET" action="<?php echo $_SESSION['url'];?>" style="width: 40%; margin-left: 25px;">
                  <div class="input-group">
                    <input type="hidden" name="op" value="<?php echo $value;?>"/>
                    <input type="text" placeholder="<?php echo $titlePlace;?>" class="form-control" name="keyword" autocomplete="off" >

                    <div class="input-group-btn">
                      <button type="submit" name="submit" class="btn btn-danger btn-flat"><i class="fa fa-search"></i>
                      </button>
                    </div>
                  </div>
                  <!-- /.input-group -->
                </form>
                </div><?php
              }?>
            </div>

            <section class="invoice">
              <div class="page-content">
              <!-- <div class="ace-settings-container" id="ace-settings-container">
                <div class="btn btn-app btn-xs btn-warning ace-settings-btn" id="ace-settings-btn">
                  <i class="ace-icon fa fa-cog bigger-130"></i>
                </div>

                <div class="ace-settings-box clearfix" id="ace-settings-box">
                  <div class="pull-left width-50">
                    <div class="ace-settings-item">
                      <div class="pull-left">
                        <select id="skin-colorpicker" class="hide">
                          <option data-skin="no-skin" value="#438EB9">#438EB9</option>
                          <option data-skin="skin-1" value="#222A2D">#222A2D</option>
                          <option data-skin="skin-2" value="#C6487E">#C6487E</option>
                          <option data-skin="skin-3" value="#D0D0D0">#D0D0D0</option>
                        </select>
                      </div>
                      <span>&nbsp; Choose Skin</span>
                    </div>

                    <div class="ace-settings-item">
                      <input type="checkbox" class="ace ace-checkbox-2 ace-save-state" id="ace-settings-navbar" autocomplete="off" />
                      <label class="lbl" for="ace-settings-navbar"> Fixed Navbar</label>
                    </div>

                    <div class="ace-settings-item">
                      <input type="checkbox" class="ace ace-checkbox-2 ace-save-state" id="ace-settings-sidebar" autocomplete="off" />
                      <label class="lbl" for="ace-settings-sidebar"> Fixed Sidebar</label>
                    </div>

                    <div class="ace-settings-item">
                      <input type="checkbox" class="ace ace-checkbox-2 ace-save-state" id="ace-settings-breadcrumbs" autocomplete="off" />
                      <label class="lbl" for="ace-settings-breadcrumbs"> Fixed Breadcrumbs</label>
                    </div>

                    <div class="ace-settings-item">
                      <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-rtl" autocomplete="off" />
                      <label class="lbl" for="ace-settings-rtl"> Right To Left (rtl)</label>
                    </div>

                    <div class="ace-settings-item">
                      <input type="checkbox" class="ace ace-checkbox-2 ace-save-state" id="ace-settings-add-container" autocomplete="off" />
                      <label class="lbl" for="ace-settings-add-container">
                        Inside
                        <b>.container</b>
                      </label>
                    </div>
                  </div>

                  <div class="pull-left width-50">
                    <div class="ace-settings-item">
                      <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-hover" autocomplete="off" />
                      <label class="lbl" for="ace-settings-hover"> Submenu on Hover</label>
                    </div>

                    <div class="ace-settings-item">
                      <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-compact" autocomplete="off" />
                      <label class="lbl" for="ace-settings-compact"> Compact Sidebar</label>
                    </div>

                    <div class="ace-settings-item">
                      <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-highlight" autocomplete="off" />
                      <label class="lbl" for="ace-settings-highlight"> Alt. Active Item</label>
                    </div>
                  </div>
                </div>
              </div> -->

              <div class="page-header">
                <h1><?php
                if(isset($_GET['op'])){
                  if(isset($_GET['op']) AND $_GET['op'] == "sm"){
                    $field = array("id_sm","DATE_FORMAT(tgl_terima, '%Y') as thn");
                    $arsip_sm = $this->model->selectprepare("arsip_sm", $field, $params=null, $where=null, "GROUP BY DATE_FORMAT(tgl_terima, '%Y') order by DATE_FORMAT(tgl_terima, '%Y') DESC");
                    if($arsip_sm->rowCount() >= 1){?>
                      <div class="col-xs-2">
                        <form method="GET">
                          <input type="hidden" name="op" value="sm" />
                          <select class="form-control" name="yearsm" id="form-field-select-1" onchange='this.form.submit()'><?php
                          while($dataArsip_sm = $arsip_sm->fetch(PDO::FETCH_OBJ)){
                            if(isset($_GET['yearsm'])){
                              if($_GET['yearsm'] == $dataArsip_sm->thn){?>
                                <option value="<?php echo $dataArsip_sm->thn;?>" selected>Surat Masuk <?php echo $dataArsip_sm->thn;?></option><?php
                              }else{?>
                                <option value="<?php echo $dataArsip_sm->thn;?>">Surat Masuk <?php echo $dataArsip_sm->thn;?></option><?php
                              }
                            }else{?>
                              <option value="<?php echo $dataArsip_sm->thn;?>">Surat Masuk <?php echo $dataArsip_sm->thn;?></option><?php
                            }
                          }?>
                        </select>
                      </form>
                      </div><br/><?php
                    }
                  }elseif(isset($_GET['op']) AND $_GET['op'] == "sk"){
                    $field = array("id_sk","DATE_FORMAT(tgl_surat, '%Y') as thn");
                    $arsip_sk = $this->model->selectprepare("arsip_sk", $field, $params=null, $where=null, "GROUP BY DATE_FORMAT(tgl_surat, '%Y') order by DATE_FORMAT(tgl_surat, '%Y') DESC");
                    if($arsip_sk->rowCount() >= 1){?>
                      <div class="col-xs-2">
                        <form method="GET">
                          <input type="hidden" name="op" value="sk" />
                          <select class="form-control" name="yearsk" id="form-field-select-1" onchange='this.form.submit()'><?php
                          while($dataArsip_sk = $arsip_sk->fetch(PDO::FETCH_OBJ)){
                            if(isset($_GET['yearsk'])){
                              if($_GET['yearsk'] == $dataArsip_sk->thn){?>
                                <option value="<?php echo $dataArsip_sk->thn;?>" selected>Surat Keluar <?php echo $dataArsip_sk->thn;?></option><?php
                              }else{?>
                                <option value="<?php echo $dataArsip_sk->thn;?>">Surat Keluar <?php echo $dataArsip_sk->thn;?></option><?php
                              }
                            }else{?>
                              <option value="<?php echo $dataArsip_sk->thn;?>">Surat Keluar <?php echo $dataArsip_sk->thn;?></option><?php
                            }
                          }?>
                        </select>
                      </form>
                    </div>
                    <br/><?php
                  }?><?php
                }elseif(isset($_GET['op']) AND $_GET['op'] == "arsip_file"){
                  $field = array("id_arsip","DATE_FORMAT(tgl_arsip, '%Y') as thn");
                  $arsip_file = $this->model->selectprepare("arsip_file", $field, $params=null, $where=null, "GROUP BY DATE_FORMAT(tgl_arsip, '%Y') order by DATE_FORMAT(tgl_arsip, '%Y') DESC");
                  if($arsip_file->rowCount() >= 1){?>
                    <div class="col-xs-2">
                      <form method="GET">
                        <input type="hidden" name="op" value="arsip_file" />
                        <select class="form-control" name="yearfile" id="form-field-select-1" onchange='this.form.submit()'><?php
                        while($dataArsip_file = $arsip_file->fetch(PDO::FETCH_OBJ)){
                          if(isset($_GET['yearfile'])){
                            if($_GET['yearfile'] == $dataArsip_file->thn){?>
                              <option value="<?php echo $dataArsip_file->thn;?>" selected>Arsip Tahun<?php echo $dataArsip_file->thn;?></option><?php
                            }else{?>
                              <option value="<?php echo $dataArsip_file->thn;?>">Arsip Tahun<?php echo $dataArsip_file->thn;?></option><?php
                            }
                          }else{?>
                            <option value="<?php echo $dataArsip_file->thn;?>">Arsip Tahun<?php echo $dataArsip_file->thn;?></option><?php
                          }
                        }?>
                      </select>
                    </form>
                  </div>
                  <br/><?php
                }?><?php
              }elseif(isset($_GET['op']) AND $_GET['op'] == "data_memo"){
                $field = array("id_info","DATE_FORMAT(tgl_info, '%Y') as thn");
                $arsip_info = $this->model->selectprepare("info", $field, $params=null, $where=null, "GROUP BY DATE_FORMAT(tgl_info, '%Y') order by DATE_FORMAT(tgl_info, '%Y') DESC");
                if($arsip_info->rowCount() >= 1){?>
                  <div class="col-xs-3">
                    <form method="GET">
                      <input type="hidden" name="op" value="data_memo" />
                      <select class="form-control" name="yearinfo" id="form-field-select-1" onchange='this.form.submit()'><?php
                      while($dataArsip_Info = $arsip_info->fetch(PDO::FETCH_OBJ)){
                        if(isset($_GET['yearinfo'])){
                          if($_GET['yearinfo'] == $dataArsip_Info->thn){?>
                            <option value="<?php echo $dataArsip_Info->thn;?>" selected>Arsip Tahun <?php echo $dataArsip_Info->thn;?></option><?php
                          }else{?>
                            <option value="<?php echo $dataArsip_Info->thn;?>">Arsip Tahun <?php echo $dataArsip_Info->thn;?></option><?php
                          }
                        }else{?>
                          <option value="<?php echo $dataArsip_Info->thn;?>">Arsip Tahun <?php echo $dataArsip_Info->thn;?></option><?php
                        }
                      }?>
                    </select>
                  </form>
                </div>
                <br/><?php
              }?><?php
            }else{
              echo "Dashboard";
            }
          }else{
            echo "Welcome";
          }
                  /* if(isset($_GET['op']) AND $_GET['op'] == "add_sm"){
                    echo "Entri surat masuk";
                  }elseif(isset($_GET['op']) AND $_GET['op'] == "sm"){
                    echo "Data surat masuk";
                  }elseif(isset($_GET['op']) AND $_GET['op'] == "add_sk"){
                    echo "Entri surat keluar";
                  }elseif(isset($_GET['op']) AND $_GET['op'] == "sk"){
                    echo "Data surat keluar";
                  }elseif(isset($_GET['op']) AND $_GET['op'] == "memo"){
                    echo "Inbox Memo";
                  }else{ */

                  //}?>
                </h1>
              </div><!-- /.page-header -->

              <div class="row">
                <div class="col-xs-12">
                  <!-- PAGE CONTENT BEGINS --><?php
                  if(isset($_GET['op']) AND $_GET['op'] == "add_sm"){
                    if($HakAkses->sm == "W"){
                      require_once "entry_sm.php";
                    }else{
                      require_once "invalid_akses.php";
                    }
                  }elseif(isset($_GET['op']) AND $_GET['op'] == "sm"){
                    if($HakAkses->sm == "W" OR $HakAkses->sm == "R"){
                      require_once "view_sm.php";
                    }else{
                      require_once "invalid_akses.php";
                    }
                  }elseif(isset($_GET['op']) AND $_GET['op'] == "sm"){
                    if($HakAkses->sm == "W" OR $HakAkses->sm == "R"){
                      require_once "view_sm.php";
                    }else{
                      require_once "invalid_akses.php";
                    }
                  }elseif(isset($_GET['op']) AND $_GET['op'] == "sm_expired"){
                    if($HakAkses->sm == "W" OR $HakAkses->sm == "R"){
                      require_once "view_sm_expired.php";
                    }else{
                      require_once "invalid_akses.php";
                    }
                  }else if(isset($_GET['op']) AND $_GET['op'] == "add_sk"){
                    if($HakAkses->sm == "W"){
                      require_once "entry_sk.php";
                    }else{
                      require_once "invalid_akses.php";
                    }
                  }else if(isset($_GET['op']) AND $_GET['op'] == "panduan"){
                    if($HakAkses->panduan == "Y"){
                      require_once "view_data_panduan.php";
                    }else{
                      require_once "invalid_akses.php";
                    }
                  }else if(isset($_GET['op']) AND $_GET['op'] == "editpanduan"){
                    if($HakAkses->editpanduan == "Y"){
                      require_once "edit_panduan.php";
                    }else{
                      require_once "invalid_akses.php";
                    }
                  }elseif(isset($_GET['op']) AND $_GET['op'] == "sk"){
                    if($HakAkses->sk == "W" OR $HakAkses->sk == "R"){
                      require_once "view_sk.php";
                    }else{
                      require_once "invalid_akses.php";
                    }
                  }elseif(isset($_GET['op']) AND $_GET['op'] == "klasifikasi"){
                    if($HakAkses->atur_klasifikasi_sm == "Y"){
                      require_once "klasifikasi.php";
                    }else{
                      require_once "invalid_akses.php";
                    }
                  }elseif(isset($_GET['op']) AND $_GET['op'] == "klasifikasi_sk"){
                    if($HakAkses->atur_klasifikasi_sk == "Y"){
                      require_once "klasifikasi_sk.php";
                    }else{
                      require_once "invalid_akses.php";
                    }
                  }elseif(isset($_GET['op']) AND $_GET['op'] == "arsip_sm"){
                    if($HakAkses->sm == "W" OR $HakAkses->sm == "R"){
                      require_once "view_arsip_sm.php";
                    }else{
                      require_once "invalid_akses.php";
                    }
                  }elseif(isset($_GET['op']) AND $_GET['op'] == "arsip_sk"){
                    if($HakAkses->sk == "W" OR $HakAkses->sk == "R"){
                      require_once "view_arsip_sk.php";
                    }else{
                      require_once "invalid_akses.php";
                    }
                  }elseif(isset($_GET['op']) AND $_GET['op'] == "user"){
                    if($HakAkses->atur_user == "Y"){
                      require_once "user.php";
                    }else{
                      require_once "invalid_akses.php";
                    }

                  }elseif(isset($_GET['op']) AND $_GET['op'] == "report_sm"){
                    if($HakAkses->report_sm == "Y"){
                      require_once "sm_report.php";
                    }else{
                      require_once "invalid_akses.php";
                    }
                  }elseif(isset($_GET['op']) AND $_GET['op'] == "report_sk"){
                    if($HakAkses->report_sk == "Y"){
                      require_once "sk_report.php";
                    }else{
                      require_once "invalid_akses.php";
                    }
                  }elseif(isset($_GET['op']) AND $_GET['op'] == "setting"){  
                    if($HakAkses->atur_layout == "Y"){
                      require_once "setting.php";
                    }else{
                      require_once "invalid_akses.php";
                    }
                  }elseif(isset($_GET['op']) AND $_GET['op'] == "panduan"){  
                    if($HakAkses->panduan == "Y"){
                      require_once "setting.php";
                    }else{
                      require_once "invalid_akses.php";
                    }
                  }elseif(isset($_GET['op']) AND $_GET['op'] == "memo"){
                    require_once "view_memo.php";
                  }elseif(isset($_GET['op']) AND $_GET['op'] == "disposisi"){
                    require_once "view_disposisi.php";
                  }elseif(isset($_GET['op']) AND $_GET['op'] == "tembusan"){
                    require_once "view_tembusan.php";
                  }elseif(isset($_GET['op']) AND $_GET['op'] == "profil"){
                    require_once "profile.php";
                  }elseif(isset($_GET['op']) AND $_GET['op'] == "cari_sm"){
                    if($HakAkses->cari_surat_masuk == "Y"){
                      require_once "cari_surat.php";
                    }else{
                      require_once "invalid_akses.php";
                    }
                  }elseif(isset($_GET['op']) AND $_GET['op'] == "cari_sk"){
                    if($HakAkses->cari_surat_keluar == "Y"){
                      require_once "cari_surat.php";
                    }else{
                      require_once "invalid_akses.php";
                    }
                  }elseif(isset($_GET['op']) AND $_GET['op'] == "cari_arsip" OR $_GET['op'] == "report_arsip"){
                    if($HakAkses->report_arsip == "Y" OR $HakAkses->arsip == "W"){
                      require_once "cari_arsip.php";
                    }else{
                      require_once "invalid_akses.php";
                    }
                  }/* elseif(isset($_GET['op']) AND $_GET['op'] == "template"){
                    if($HakAkses->atur_template == "Y"){
                      require_once "template_surat.php";
                    }else{
                      require_once "invalid_akses.php";
                    }
                  } */
                  elseif(isset($_GET['op']) AND $_GET['op'] == "view_surat"){
                    require_once "cari_surat_view.php";
                  }/* elseif(isset($_GET['op']) AND $_GET['op'] == "sk_temp_tgs"){
                    if($HakAkses->template_surat == "W"){
                      require_once "entry_sk_temp.php";
                    }else{
                      require_once "invalid_akses.php";
                    }
                  }elseif(isset($_GET['op']) AND $_GET['op'] == "view_sk_temp"){
                    if($HakAkses->template_surat == "W" OR $HakAkses->template_surat == "R"){
                      require_once "view_arsip_sk_temp.php";
                    }else{
                      require_once "invalid_akses.php";
                    }
                  } */
                  elseif(isset($_GET['op']) AND $_GET['op'] == "arsip_file"){
                    if($HakAkses->arsip == "W" OR $HakAkses->arsip == "R"){
                      require_once "view_arsip_file.php";
                    }else{
                      require_once "invalid_akses.php";
                    }
                  }elseif(isset($_GET['op']) AND $_GET['op'] == "add_arsip"){
                    if($HakAkses->arsip == "W"){
                      require_once "entry_filearsip.php";
                    }else{
                      require_once "invalid_akses.php";
                    }
                  }elseif(isset($_GET['op']) AND $_GET['op'] == "klasifikasi_file"){
                    if($HakAkses->atur_klasifikasi_arsip == "Y"){
                      require_once "klasifikasi_arsip_file.php";
                    }else{
                      require_once "invalid_akses.php";
                    }
                  }elseif(isset($_GET['op']) AND $_GET['op'] == "report_disposisi"){
                    if($HakAkses->report_dispo == "Y"){
                      require_once "disposisi_report.php";
                    }else{
                      require_once "invalid_akses.php";
                    }
                  }elseif(isset($_GET['op']) AND $_GET['op'] == "report_progress"){
                    if($HakAkses->report_dispo == "Y"){
                      require_once "progress_report.php";
                    }else{
                      require_once "invalid_akses.php";
                    }
                  }elseif(isset($_GET['op']) AND $_GET['op'] == "info"){
                    require_once "view_info.php";
                  }elseif(isset($_GET['op']) AND $_GET['op'] == "add_memo" OR $_GET['op'] == "data_memo"){
                    if($HakAkses->info == "Y" AND $_GET['op'] == "add_memo"){
                      require_once "entry_info.php";
                    }elseif($HakAkses->info == "Y" AND $_GET['op'] == "data_memo"){
                      require_once "view_data_memo.php";
                    }else{
                      require_once "invalid_akses.php";
                    }
                  }else{?>
                    <?php
                    //if(($_SESSION['hakakses'] == "Sekretaris") || ($_SESSION['hakakses'] == "Manager")){?>
                    </div>

                    <div class="row">
                      <?php
                      if($HakAkses->sm == "W" OR $HakAkses->sm == "R"){?>
                        <div class="col-lg-3 col-xs-6">
                          <!-- small box -->
                          <div class="small-box bg-aqua">
                            <div class="inner">
                              <h3>Surat Masuk</h3>
                              <?php
                              //statistik
                              $JlhArsipSM = $this->model->selectprepare("arsip_sm", $field=null, $params=null, $where=null, $order=null);?>
                              <p><?php echo $JlhArsipSM->rowCount();?> Arsip</p>
                            </div>
                            <div class="icon">
                              <i class="ion ion-android-mail"></i>
                            </div>
                            <a href="./index.php?op=sm" class="small-box-footer">Selengkapnya <i class="fa fa-arrow-circle-right"></i></a>
                          </div>
                        </div>

                        <?php
                      }
                      if($HakAkses->sk == "W" OR $HakAkses->sk == "R"){?>
                        <!-- ./col -->
                        <div class="col-lg-3 col-xs-6">
                          <!-- small box -->
                          <div class="small-box bg-green">
                            <div class="inner">
                              <h3>Surat Keluar</h3>
                              <?php
                              //statistik
                              $JlhArsipSK = $this->model->selectprepare("arsip_sk", $field=null, $params=null, $where=null, $order=null);?>
                              <p><?php echo $JlhArsipSK->rowCount();?> Arsip</p>
                            </div>
                            <div class="icon">
                              <i class="ion ion-paper-airplane"></i>
                            </div>
                            <a href="./index.php?op=sk" class="small-box-footer">Selengkapnya <i class="fa fa-arrow-circle-right"></i></a>
                          </div>
                          </div><?php
                        }
                        if($HakAkses->info == "Y"){
                          $HitInfo = $this->model->selectprepare("info", $field=null, $params=null, $where=null, $order=null);?>
                          <!-- ./col -->
                          <div class="col-lg-3 col-xs-6">
                            <!-- small box -->
                            <div class="small-box bg-yellow">
                              <div class="inner">
                                <h3>Memo</h3>

                                <p><?php echo $HitInfo->rowCount();?> Arsip</p>
                              </div>
                              <div class="icon">
                                <i class="ion ion-compose"></i>
                              </div>
                              <a href="./index.php?op=data_memo" class="small-box-footer">Selengkapnya<i class="fa fa-arrow-circle-right"></i></a>
                            </div>
                            </div><?php
                          }
                          if($HakAkses->arsip == "W" OR $HakAkses->arsip == "R"){
                            $arsip_file = $this->model->selectprepare("arsip_file", $field=null, $params=null, $where=null, $order=null);?>
                            <!-- ./col -->
                            <div class="col-lg-3 col-xs-6">
                              <!-- small box -->
                              <div class="small-box bg-red">
                                <div class="inner">
                                  <h3>File Digital</h3>

                                  <p><?php echo $arsip_file->rowCount();?> Arsip</p>
                                </div>
                                <div class="icon">
                                 <i class="ion ion-android-folder"></i>
                               </div>
                               <a href="./index.php?op=arsip_file" class="small-box-footer">Selengkapnya <i class="fa fa-arrow-circle-right"></i></a>
                             </div>
                             </div><?php
                           }
                           ?>

                           <!-- icon 6 fitur utama  -->
                           <div class="col container">
                            <div class=""><h2 style="margin-top: 200px;"> Fitur Utama</h2></div>

                            <div class="responsive"  >
                              <a href="index.php?op=disposisi" class="btn btn-app bg-white"  style="width:150px;height: 150px; box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);">
                                <img src="dist/img/disposisi.png" style="width:100px;height: 100px;">
                                <h4 style="font-weight: bold">Disposisi</h4>
                              </a>
                              <a href="index.php?op=info" class="btn btn-app bg-white"  style="width:150px;height: 150px; box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);">
                                <img src="dist/img/memo.png" style="width:100px;height: 100px;">
                                <h4 style="font-weight: bold">Memo</h4>
                              </a>
                              <a href="index.php?op=sm" class="btn btn-app bg-white"  style="width:150px;height: 150px; box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);">
                                <img src="dist/img/laporan.png" style="width:100px;height: 100px;">
                                <h4 style="font-weight: bold"> Arsip Surat Masuk</h4>
                              </a>
                              <a href="index.php?op=sk" class="btn btn-app bg-white"  style="width:150px;height: 150px; box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);" >
                                <img src="dist/img/iconsuratkeluar.png" style="width:100px;height: 100px;">
                                <h4 style="font-weight: bold">Arsip Surat Keluar</h4>
                              </a>
                              <a href="index.php?op=arsip_file" class="btn btn-app bg-white"  style="width:150px;height: 150px; box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);">
                                <img src="dist/img/iconarsip.png" style="width:100px;height: 100px;">
                                <h4 style="font-weight: bold">Arsip Digital</h4>
                              </a>
                              <a href="./berkas/panduan-web.pdf" target="blank" class="btn btn-app bg-white"  style="width:150px;height: 150px; box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);">
                                <img src="dist/img/laporan.png" style="width:100px;height: 100px;">
                                <h4 style="font-weight: bold"> Panduan Web</h4>
                              </a>
                            </div>
                          </div>
                        </div>
                      </section>
                      <?php
                    //}
                    }?>
                    <!--<div class="hr hr32 hr-dotted"></div>-->
                    <p></p>

                    <!-- PAGE CONTENT ENDS -->
                  </div><!-- /.col -->
                </div><!-- /.row -->
              </div><!-- /.page-content -->
            </div>

            <!-- /.row -->
            <!-- Main row -->
            <!-- <div class="row"> -->
              <!-- Left col -->
              <!-- <section class="col-lg-7 connectedSortable"> -->
                <!-- Custom tabs (Charts with tabs)-->

                <!-- /.nav-tabs-custom -->

                <!-- Chat box -->

                <!-- /.box (chat box) -->

                <!-- TO DO List -->

                <!-- /.box -->

                <!-- quick email widget -->


                <!-- </section> -->
                <!-- /.Left col -->
                <!-- right col (We are only adding the ID to make the widgets sortable)-->

                <!-- right col -->
                <!-- </div> -->
                <!-- /.row (main row) -->

              </section>
              <!-- /.content -->
            </div>
            <!-- /.content-wrapper -->


            <!-- Control Sidebar -->

            <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
   immediately after the control sidebar -->
   <div class="control-sidebar-bg"></div>

 </div>

 <footer class="main-footer">
  <div class="pull-right hidden-xs">

  </div>
  <strong><span class="bigger-120">
    <span class="blue bolder">Sistem Tertib Administrasi Persuratan dan Kearsipan - SITERAPKAN</span>
    &copy; <?php echo date('Y');?> 
  </span></strong> 
</footer>
<!-- ./wrapper -->

<!-- jQuery 3 -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="bower_components/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.7 -->
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- Select2 -->
<script src="bower_components/select2/dist/js/select2.full.min.js"></script>
<script src="bower_components/select2/dist/js/select3.full.min.js"></script>
<!-- InputMask -->
<script src="plugins/input-mask/jquery.inputmask.js"></script>
<script src="plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="plugins/input-mask/jquery.inputmask.extensions.js"></script>
<!-- Morris.js charts -->
<script src="bower_components/raphael/raphael.min.js"></script>
<script src="bower_components/morris.js/morris.min.js"></script>
<!-- Sparkline -->
<script src="bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
<!-- jvectormap -->
<script src="plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- jQuery Knob Chart -->
<script src="bower_components/jquery-knob/dist/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="bower_components/moment/min/moment.min.js"></script>
<script src="bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- datepicker -->
<script src="bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- bootstrap time picker -->
<script src="plugins/timepicker/bootstrap-timepicker.min.js"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<!-- Slimscroll -->
<script src="bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="dist/js/pages/dashboard.js"></script>
<!-- SlimScroll -->
<script src="bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- iCheck 1.0.1 -->
<script src="plugins/iCheck/icheck.min.js"></script>
<!-- FastClick -->
<script src="bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
<script>
  $(function () {

    $('[data-toggle="tooltip"]').tooltip()
    //Initialize Select2 Elements
    $('.select2').select2()

    

    //Datemask dd/mm/yyyy
    $('#datemask').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' })
    //Datemask2 mm/dd/yyyy
    $('#datemask2').inputmask('mm/dd/yyyy', { 'placeholder': 'mm/dd/yyyy' })
    //Money Euro
    $('[data-mask]').inputmask()

    //Date range picker
    $('#reservation').daterangepicker()
    //Date range picker with time picker
    $('#reservationtime').daterangepicker({ timePicker: true, timePickerIncrement: 30, locale: { format: 'MM/DD/YYYY hh:mm A' }})
    //Date range as a button
    $('#daterange-btn').daterangepicker(
    {
      ranges   : {
        'Today'       : [moment(), moment()],
        'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
        'This Month'  : [moment().startOf('month'), moment().endOf('month')],
        'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
      },
      startDate: moment().subtract(29, 'days'),
      endDate  : moment()
    },
    function (start, end) {
      $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
    }
    )

    //Date picker
    $('#datepicker1').datepicker({
      autoclose: true
    })

    //Date picker
    $('#datepicker2').datepicker({
      autoclose: true
    })

     //Date picker
     $('#datepicker3').datepicker({
      autoclose: true
    })


    //iCheck for checkbox and radio inputs
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass   : 'iradio_minimal-blue'
    })
    //Red color scheme for iCheck
    $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
      checkboxClass: 'icheckbox_minimal-red',
      radioClass   : 'iradio_minimal-red'
    })
    //Flat red color scheme for iCheck
    $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass   : 'iradio_flat-green'
    })

    //Colorpicker
    $('.my-colorpicker1').colorpicker()
    //color picker with addon
    $('.my-colorpicker2').colorpicker()

    //Timepicker
    $('.timepicker').timepicker({
      showInputs: false
    })
  })
</script>


</body>
</html><?php
}else{
  /* echo "Sesssion belum ada<br/>";
  echo $_SESSION['atra_id']."  pass ".$_SESSION['hakakses']; */
  echo "<script type=\"text/javascript\">window.location.href=\"./login\";</script>";
}?>

