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
  <title>E-Office</title>
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
  <!-- Daterange picker -->
  <link rel="stylesheet" href="bower_components/bootstrap-daterangepicker/daterangepicker.css">
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">

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
      <span class="logo-mini"><b>E</b>OFFICE</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>E - </b>Office</span>
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
              <i class="fa fa-envelope-o"></i>
              <span class="label label-success">4</span><?php
                  }?>


            </a>
            <ul class="dropdown-menu">
              <li class="header">
                <?php
                    if($cekSM->rowCount() >= 1){?>
                      <?php echo $cekSM->rowCount();?> <?php echo $teks;
                    }else{
                      echo "Tidak ada ".$teks;
                    }?>
              </li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                  <?php
                      if($cekSM->rowCount() >= 1){
                        foreach($dumpSM as $key => $object){
                          $params = array(':id_user' => $object->id_user);
                          $cek_pengirim = $this->model->selectprepare("user", $field=null, $params, "id_user=:id_user", $order=null);
                          $data_cek_pengirim = $cek_pengirim->fetch(PDO::FETCH_OBJ);?>
                  <li><!-- start message -->
                    <a href="./index.php?op=memo&memoid=<?php echo $object->id_sm;?>" class="clearfix">
                      <div class="pull-left">
                        <img src="assets/images/avatars/<?php echo $data_cek_pengirim->picture;?>" class="img-circle" alt="User Image">
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
                      </div>
                      <h4>
                        Support Team
                        <small><i class="fa fa-clock-o"></i> 5 mins</small>
                      </h4>
                      <p>Why not buy a new awesome theme?</p>
                    </a>
                  </li><?php
                        }
                      }?>
                  <!-- end message -->
                  <li>
                    <a href="<?php echo $link;?>">
                      <div class="pull-left">
                      </div>
                      <h4>
                        <?php echo $teks1;?>
                      </h4>
                    </a>
                  </li>
                </ul>
              </li>
              <!-- <li class="footer"><a href="#">See All Messages</a></li> -->
            </ul>
          </li>

          <!-- Notifications: style can be found in dropdown.less -->
          <li class="dropdown notifications-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-bell-o"></i>
              <span class="label label-warning">10</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have 10 notifications</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                  <li>
                    <a href="#">
                      <i class="fa fa-users text-aqua"></i> 5 new members joined today
                    </a>
                  </li>
                  <li>
                    <a href="#">
                      <i class="fa fa-warning text-yellow"></i> Very long description here that may not fit into the
                      page and may cause design problems
                    </a>
                  </li>
                  <li>
                    <a href="#">
                      <i class="fa fa-users text-red"></i> 5 new members joined
                    </a>
                  </li>
                  <li>
                    <a href="#">
                      <i class="fa fa-shopping-cart text-green"></i> 25 sales made
                    </a>
                  </li>
                  <li>
                    <a href="#">
                      <i class="fa fa-user text-red"></i> You changed your username
                    </a>
                  </li>
                </ul>
              </li>
              <li class="footer"><a href="#">View all</a></li>
            </ul>
          </li>
          <!-- Tasks: style can be found in dropdown.less -->
          <li class="dropdown tasks-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-flag-o"></i>
              <span class="label label-danger">9</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have 9 tasks</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                  <li><!-- Task item -->
                    <a href="#">
                      <h3>
                        Design some buttons
                        <small class="pull-right">20%</small>
                      </h3>
                      <div class="progress xs">
                        <div class="progress-bar progress-bar-aqua" style="width: 20%" role="progressbar"
                             aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                          <span class="sr-only">20% Complete</span>
                        </div>
                      </div>
                    </a>
                  </li>
                  <!-- end task item -->
                  <li><!-- Task item -->
                    <a href="#">
                      <h3>
                        Create a nice theme
                        <small class="pull-right">40%</small>
                      </h3>
                      <div class="progress xs">
                        <div class="progress-bar progress-bar-green" style="width: 40%" role="progressbar"
                             aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                          <span class="sr-only">40% Complete</span>
                        </div>
                      </div>
                    </a>
                  </li>
                  <!-- end task item -->
                  <li><!-- Task item -->
                    <a href="#">
                      <h3>
                        Some task I need to do
                        <small class="pull-right">60%</small>
                      </h3>
                      <div class="progress xs">
                        <div class="progress-bar progress-bar-red" style="width: 60%" role="progressbar"
                             aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                          <span class="sr-only">60% Complete</span>
                        </div>
                      </div>
                    </a>
                  </li>
                  <!-- end task item -->
                  <li><!-- Task item -->
                    <a href="#">
                      <h3>
                        Make beautiful transitions
                        <small class="pull-right">80%</small>
                      </h3>
                      <div class="progress xs">
                        <div class="progress-bar progress-bar-yellow" style="width: 80%" role="progressbar"
                             aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                          <span class="sr-only">80% Complete</span>
                        </div>
                      </div>
                    </a>
                  </li>
                  <!-- end task item -->
                </ul>
              </li>
              <li class="footer">
                <a href="#">View all tasks</a>
              </li>
            </ul>
          </li>
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="assets/images/avatars/<?php echo $_SESSION['picture'];?>" class="user-image" alt="User Image">
              <span class="hidden-xs"><?php echo $_SESSION['nama'];?></span>
            </a>

            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">

                <p>
                  <?php echo $_SESSION['nama'];?>
                  <small>Member since Nov. 2012</small>
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
              <li class="user-footer">
                <?php
                  if($_SESSION['hakakses'] == "Admin"){?>
                <div class="pull-left">
                  <a href="./index.php?op=user" class="btn btn-default btn-flat">User</a>
                </div><?php
                  }?>
                <div class="pull-left">
                  <a href="./index.php?op=profil" class="btn btn-default btn-flat">Profile</a>
                </div>
                <div class="pull-right">
                  <a href="./keluar" class="btn btn-default btn-flat">Keluar</a>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
          <li>
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li>
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
          <img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
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
     <ul class="sidebar-menu" data-widget="tree">
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
            if(isset($_GET['op']) AND $_GET['op'] == "setting"){ $StatSetting = 'active open'; }else{ $StatSetting = ''; }
            if(isset($_GET['op']) AND $_GET['op'] == "klasifikasi_file"){ $StatKlasFile = 'active open'; }else{ $StatKlasFile = ''; }
            if(isset($_GET['op']) AND $_GET['op'] == "klasifikasi"){ $StatKlasSM = 'active open'; }else{ $StatKlasSM = ''; }
            if(isset($_GET['op']) AND $_GET['op'] == "klasifikasi_sk"){ $StatKlasSK = 'active open'; }else{ $StatKlasSK = ''; }
            if(isset($_GET['op']) AND $_GET['op'] == "memo"){ $StatMemo = 'active open'; }else{ $StatMemo = ''; }
            if(isset($_GET['op']) AND $_GET['op'] == "disposisi"){ $StatDisposisi = 'active open'; }else{ $StatDisposisi = ''; }
            if(isset($_GET['op']) AND $_GET['op'] == "tembusan"){ $StatTembusan = 'active open'; }else{ $StatTembusan = ''; }
            if(isset($_GET['op']) AND $_GET['op'] == "info"){ $StatInfo = 'active open'; }else{ $StatInfo = ''; }
            if(isset($_GET['op']) AND $_GET['op'] == "info"){ $StatInfo = 'active open'; }else{ $StatInfo = ''; }
            if(!isset($_GET['op'])){
              $StatBeranda = 'active';
            }else{
              $StatBeranda = '';
            }?>
            
        <li class="header">MAIN NAVIGATION</li>
        <li class="<?php echo $StatBeranda;?> active treeview">
          <a href="#">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
          </a>
        </li>

        <li class="<?php echo $StatMemo;?>">
          <a href="index.php?op=memo">
            <i class="fa fa-files-o"></i>
            <span>Surat Masuk</span>
            <?php
                if($cekSM->rowCount() >= 1){?>
                  <span class="badge badge-warning"><?php echo $cekSM->rowCount();?></span><?php
                }?>
          </a>
        </li>
        <li class="<?php echo $StatDisposisi;?>">
          <a href="index.php?op=disposisi">
            <i class="fa fa-external-link-square"></i>
            <span>Disposisi</span>
            <?php
                if($cekDispo->rowCount() >= 1){?>
                  <span class="badge badge-warning"><?php echo $cekDispo->rowCount();?></span><?php
                }?>
          </a>
        </li>
        <li class="<?php echo $StatTembusan;?>">
          <a href="index.php?op=tembusan">
            <i class="fa fa-files-o"></i>
            <span>Tembusan</span>
            <?php
                if($cekTembusan->rowCount() >= 1){?>
                  <span class="badge badge-warning"><?php echo $cekTembusan->rowCount();?></span><?php
                }?>
          </a>
        </li>
        <?php
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
          <a href="index.php?op=info">
            <i class="fa fa-files-o"></i>
            <span>Memo</span>
            <!--  -->
          </a>
        </li>
        ?php
            if($HakAkses->sm == "W"){?>
        <li class="<?php echo $StatSM;?>">
          <a href="#">
            <i class="fa fa-pie-chart"></i>
            <span>Arsip Surat Masuk</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="<?php echo $StatEntriSM;?>">
              <a href="./index.php?op=add_sm">
                <i class="fa fa-circle-o"></i>
                 Entri Baru
              </a>
            </li>
            <li class="<?php echo $StatDataSM;?>">
              <a href="./index.php?op=sm">
                <i class="fa fa-circle-o"></i>
                 Data Surat Masuk
              </a>
            </li>
            <li class="<?php echo $StatSMeXp;?>">
              <a href="pages/charts/flot.html">
                <i class="fa fa-circle-o"></i>
                 Surat Jatuh Tempo
              </a>
            </li>
          </ul>
        </li><?php
            }
        if($HakAkses->sm == "R"){?>
        <li class="<?php echo $StatArsipSM;?>">
          <a href="#">
            <i class="fa fa-pie-chart"></i>
            <span>Arsip Surat Masuk</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
        </li>
         <li class="<?php echo $StatSMeXp;?>">
          <a href="./index.php?op=sm_expired">
            <i class="fa fa-pie-chart"></i>
            <span>Surat Jatuh Tempo</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
         </li><?php
            }
        if($HakAkses->sk == "W"){?>
        <li class="<?php echo $StatSK;?>">
          <a href="#">
            <i class="fa  fa-paper-plane-o"></i>
            <span>Arsip Surat Keluar</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="<?php echo $StatEntriSK;?>">
              <a href="./index.php?op=add_sk">
                <i class="fa fa-circle-o"></i>
                 Entri Baru
              </a>
            </li>
            <li class="<?php echo $StatDataSK;?>">
              <a href="./index.php?op=sk">
                <i class="fa fa-circle-o"></i>
                 Data Surat Keluar
              </a>
           </li>
          </ul>
        </li><?php
            }
        if($HakAkses->sk == "R"){?>
        <li class="<?php echo $StatArsipSK;?>">
          <a href="./index.php?op=arsip_sk">
            <i class="fa  fa-paper-plane-o"></i>
            <span>Arsip Surat Keluar</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
        </li><?php
            }
        if($HakAkses->info == "Y"){?>
        <li  class="<?php echo $StatArsipMemo;?>">
          <a href="#">
            <i class="fa fa-edit"></i> <span>Arsip Memo</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="<?php echo $StatEntriMemo;?>">
              <a href="./index.php?op=add_memo">
                <i class="fa fa-circle-o"></i>
                 Entri Baru
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
        <li class="<?php echo $StatArsipFile;?>">
          <a href="#">
            <i class="fa fa-table"></i> <span>Arsip Digital</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="<?php echo $StatArsipFileEntri;?>">
              <a href="./index.php?op=add_arsip">
                <i class="fa fa-circle-o"></i>
                 Entri Baru
              </a>
            </li>
            <li class="<?php echo $StatArsipFileView;?>">
              <a href="./index.php?op=arsip_fil">
                <i class="fa fa-circle-o"></i>
                 Data File Arsip
              </a>
            </li>
            <li class="<?php echo $StatCariFile;?>">
              <a href="./index.php?op=cari_arsip">
                <i class="fa fa-circle-o">
                </i> Pencarian Arsip
              </a>
            </li>
          </ul>
        </li><?php
            }
        if($HakAkses->arsip == "R"){?> 
        <li class="<?php echo $StatArsipFileView;?>">
           <a href="./index.php?op=arsip_file">
            <i class="fa fa-table"></i> <span>Data Arsip Digital</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
        </li><?php
            }
        if($HakAkses->atur_layout == "Y" OR $HakAkses->atur_klasifikasi_sm == "Y" OR $HakAkses->atur_klasifikasi_sk == "Y" OR $HakAkses->atur_klasifikasi_arsip == "Y" OR $HakAkses->atur_user == "Y"){?>

        <li class="<?php echo $StatAtur;?>">
          <a href="#">
            <i class="fa fa-table"></i> <span>Pengaturan</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <?php
            if($HakAkses->atur_layout == "Y"){?>
            <li class="<?php echo $StatSetting;?>">
              <a href="./index.php?op=setting">
                <i class="fa fa-circle-o"></i>
                 Atur Layout
              </a>
            </li><?php
                }
            if($HakAkses->atur_klasifikasi_sm == "Y"){?>
            <li class="<?php echo $StatKlasSM;?>">
              <a href="./index.php?op=klasifikasi">
                <i class="fa fa-circle-o"></i>
                 Klasifikasi Surat Masuk
              </a>
            </li><?php
                }
            if($HakAkses->atur_klasifikasi_sk == "Y"){?>
            <li class="<?php echo $StatKlasSK;?>">
              <a href="./index.php?op=klasifikasi_sk">
                <i class="fa fa-circle-o"></i>
                 Klasifikasi Surat Keluar
              </a>
            </li><?php
                }
            if($HakAkses->atur_klasifikasi_arsip == "Y"){?>
            <li class="<?php echo $StatKlasFile;?>">
              <a href="./index.php?op=klasifikasi_file">
                <i class="fa fa-circle-o"></i>
                 Klasifikasi File Arsip
              </a>
             </li><?php
                }
            if($HakAkses->atur_user == "Y"){?>
            <li class="<?php echo $StatUser;?>">
              <a href="./index.php?op=user">
                <i class="fa fa-circle-o"></i>
                 Data User
              </a>
             </li><?php
                }?>
          </ul>
        </li><?php
            }
        if($HakAkses->report_sm == "Y" OR $HakAkses->report_sk == "Y" OR $HakAkses->report_arsip == "Y"){?> 
        <li class="<?php echo $StatReport;?>">
          <a href="#">
            <i class="fa fa-table"></i> <span>Laporan</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <?php
            if($HakAkses->report_dispo == "Y"){?>
            <li class="<?php echo $StatDIS;?>">
              <a href="./index.php?op=report_disposisi">
                <i class="fa fa-circle-o"></i>
                 Disposisi
               </a>
             </li>
             <?php
                  }
            if($HakAkses->report_progress == "Y"){?>
            <li class="<?php echo $StatReportProgress;?>">
              <a href="./index.php?op=report_progress">
                <i class="fa fa-circle-o"></i>
                 Progress Surat
               </a>
             </li><?php
                  }
             if($HakAkses->report_sm == "Y"){?>
            <li class="<?php echo $StatRSM;?>">
              <a href="./index.php?op=report_sm">
                <i class="fa fa-circle-o"></i>
                 Surat Masuk
               </a>
             </li><?php
                  }
            if($HakAkses->report_sk == "Y"){?>
            <li class="<?php echo $StatRSK;?>">
              <a href="./index.php?op=report_sk">
                <i class="fa fa-circle-o"></i>
                 Surat Keluar
               </a>
             </li><?php
                  }
            if($HakAkses->report_arsip == "Y"){?>
            <li  class="<?php echo $StatReportArsip;?>">
              <a href="./index.php?op=report_arsip">
                <i class="fa fa-circle-o"></i>
                 Data Arsip Digital
               </a>
            </li>
            <?php
                  }?>
          </ul>
        </li><?php
            }?>
        <li><a href="https://adminlte.io/docs"><i class="fa fa-book"></i> <span>Documentation</span></a></li>
        <li class="header">LABELS</li>
        <li><a href="#"><i class="fa fa-circle-o text-red"></i> <span>Important</span></a></li>
        <li><a href="#"><i class="fa fa-circle-o text-yellow"></i> <span>Warning</span></a></li>
        <li><a href="#"><i class="fa fa-circle-o text-aqua"></i> <span>Information</span></a></li>
      </ul>

    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Dashboard
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>

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
                  <form class="form-search" method="GET" action="<?php echo $_SESSION['url'];?>">
                    <span class="input-icon">
                      <input type="hidden" name="op" value="<?php echo $value;?>"/>
                      <input type="text" placeholder="<?php echo $titlePlace;?>" class="nav-search-input" name="keyword" autocomplete="off" />
                      <i class="ace-icon fa fa-search nav-search-icon"></i>
                    </span>
                  </form>
                </div><?php
              }?>

    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3>Surat Masuk</h3>

              <p>0 Arsip</p>
            </div>
            <div class="icon">
              <i class="ion ion-android-mail"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3>Surat Keluar</h3>
              <p>0 Arsip</p>
            </div>
            <div class="icon">
              <i class="ion ion-paper-airplane"></i>
            </div>
            <a href="#" class="small-box-footer">Selengkapnya <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3>Memo</h3>

              <p>0 Arsip</p>
            </div>
            <div class="icon">
              <i class="ion ion-compose"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3>File Digital</h3>

              <p>0 Arsip</p>
            </div>
            <div class="icon">
              <i class="ion ion-android-folder"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
      </div>
      <!-- /.row -->
      <!-- Main row -->
      <div class="row">
        <!-- Left col -->
        <section class="col-lg-7 connectedSortable">
          <!-- Custom tabs (Charts with tabs)-->
        
          <!-- /.nav-tabs-custom -->

          <!-- Chat box -->
         
          <!-- /.box (chat box) -->

          <!-- TO DO List -->
         
          <!-- /.box -->

          <!-- quick email widget -->
         

        </section>
        <!-- /.Left col -->
        <!-- right col (We are only adding the ID to make the widgets sortable)-->
        
        <!-- right col -->
      </div>
      <!-- /.row (main row) -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      
    </div>
    <strong>Copyright &copy; 2021 <a href="#">Sistem Informasi Arsip Surat - E-Office</a>.</strong> All rights
    reserved.
  </footer>

  <!-- Control Sidebar -->

  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
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
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>

    <script type="text/javascript">
        jQuery(function($) {
          
          //datepicker plugin
          //link
          $('.date-picker').datepicker({
            autoclose: true,
            todayHighlight: true
          })
          //show datepicker when clicking on the icon
          .next().on(ace.click_event, function(){
            $(this).prev().focus();
          });
        
          //or change it into a date range picker
          $('.input-daterange').datepicker({autoclose:true});
        
          
          //choosen
          if(!ace.vars['touch']) {
            $('.chosen-select').chosen({allow_single_deselect:true}); 
            //resize the chosen on window resize
        
            $(window)
            .off('resize.chosen')
            .on('resize.chosen', function() {
              $('.chosen-select').each(function() {
                 var $this = $(this);
                 $this.next().css({'width': $this.parent().width()});
              })
            }).trigger('resize.chosen');
            //resize chosen on sidebar collapse/expand
            $(document).on('settings.ace.chosen', function(e, event_name, event_val) {
              if(event_name != 'sidebar_collapsed') return;
              $('.chosen-select').each(function() {
                 var $this = $(this);
                 $this.next().css({'width': $this.parent().width()});
              })
            });
            
            
            //chosen plugin inside a modal will have a zero width because the select element is originally hidden
            //and its width cannot be determined.
            //so we set the width after modal is show
            $('#modal-form').on('shown.bs.modal', function () {
              if(!ace.vars['touch']) {
                $(this).find('.chosen-container').each(function(){
                  $(this).find('a:first-child').css('width' , '210px');
                  $(this).find('.chosen-drop').css('width' , '210px');
                  $(this).find('.chosen-search input').css('width' , '200px');
                });
              }
            })
            /**
            //or you can activate the chosen plugin after modal is shown
            //this way select element becomes visible with dimensions and chosen works as expected
            $('#modal-form').on('shown', function () {
              $(this).find('.modal-chosen').chosen();
            })
            */
            //choosen
            
        
            $('#chosen-multiple-style .btn').on('click', function(e){
              var target = $(this).find('input[type=radio]');
              var which = parseInt(target.val());
              if(which == 2) $('#form-field-select-4').addClass('tag-input-style');
               else $('#form-field-select-4').removeClass('tag-input-style');
            });
          }
          
          //to translate the daterange picker, please copy the "examples/daterange-fr.js" contents here before initialization
          $('input[name=rangetgl]').daterangepicker({
            'applyClass' : 'btn-sm btn-success',
            'cancelClass' : 'btn-sm btn-default',
            locale: {
              applyLabel: 'Apply',
              cancelLabel: 'Cancel',
            }
          })
          .prev().on(ace.click_event, function(){
            $(this).next().focus();
          });
          
          $('.sparkline').each(function(){
            var $box = $(this).closest('.infobox');
            var barColor = !$box.hasClass('infobox-dark') ? $box.css('color') : '#FFF';
            $(this).sparkline('html',
                     {
                      tagValuesAttribute:'data-values',
                      type: 'bar',
                      barColor: barColor ,
                      chartRangeMin:$(this).data('min') || 0
                     });
          });
        
          $('[data-rel=tooltip]').tooltip({container:'body'});
          $('[data-rel=popover]').popover({container:'body'});
          
          $.mask.definitions['~']='[+-]';
          $('.input-mask-date').mask('99/99/9999');
          $('.input-mask-phone').mask('(999) 999-9999');
          $('.input-mask-eyescript').mask('~9.99 ~9.99 999');
          $(".input-mask-product").mask("a*-999-a999",{placeholder:" ",completed:function(){alert("You typed the following: "+this.val());}});
        
          $('#id-input-file-1 , #id-input-file-2').ace_file_input({
            no_file:'No File ...',
            btn_choose:'Pilih file',
            btn_change:'Change',
            droppable:false,
            onchange:null,
            thumbnail:false //| true | large
            //whitelist:'gif|png|jpg|jpeg'
            //blacklist:'exe|php'
            //onchange:''
            //
          });
          //pre-show a file name, for example a previously selected file
          //$('#id-input-file-1').ace_file_input('show_file_list', ['myfile.txt'])
          
          //flot chart resize plugin, somehow manipulates default browser resize event to optimize it!
          //but sometimes it brings up errors with normal resize event handlers
          $.resize.throttleWindow = false;
        
          var placeholder = $('#piechart-placeholder').css({'width':'90%' , 'min-height':'150px'});
          var data = [
          { label: "social networks",  data: 38.7, color: "#68BC31"},
          { label: "search engines",  data: 24.5, color: "#2091CF"},
          { label: "ad campaigns",  data: 8.2, color: "#AF4E96"},
          { label: "direct traffic",  data: 18.6, color: "#DA5430"},
          { label: "other",  data: 10, color: "#FEE074"}
          ]
          function drawPieChart(placeholder, data, position) {
            $.plot(placeholder, data, {
            series: {
              pie: {
                show: true,
                tilt:0.8,
                highlight: {
                  opacity: 0.25
                },
                stroke: {
                  color: '#fff',
                  width: 2
                },
                startAngle: 2
              }
            },
            legend: {
              show: true,
              position: position || "ne", 
              labelBoxBorderColor: null,
              margin:[-30,15]
            }
            ,
            grid: {
              hoverable: true,
              clickable: true
            }
           })
         }
         drawPieChart(placeholder, data);
        
         /**
         we saved the drawing function and the data to redraw with different position later when switching to RTL mode dynamically
         so that's not needed actually.
         */
         placeholder.data('chart', data);
         placeholder.data('draw', drawPieChart);
        
        
          //pie chart tooltip example
          var $tooltip = $("<div class='tooltip top in'><div class='tooltip-inner'></div></div>").hide().appendTo('body');
          var previousPoint = null;
        
          placeholder.on('plothover', function (event, pos, item) {
          if(item) {
            if (previousPoint != item.seriesIndex) {
              previousPoint = item.seriesIndex;
              var tip = item.series['label'] + " : " + item.series['percent']+'%';
              $tooltip.show().children(0).text(tip);
            }
            $tooltip.css({top:pos.pageY + 10, left:pos.pageX + 10});
          } else {
            $tooltip.hide();
            previousPoint = null;
          }
          
         });
        
          /////////////////////////////////////
          $(document).one('ajaxloadstart.page', function(e) {
            $tooltip.remove();
          });
        
        
        
        
          var d1 = [];
          for (var i = 0; i < Math.PI * 2; i += 0.5) {
            d1.push([i, Math.sin(i)]);
          }
        
          var d2 = [];
          for (var i = 0; i < Math.PI * 2; i += 0.5) {
            d2.push([i, Math.cos(i)]);
          }
        
          var d3 = [];
          for (var i = 0; i < Math.PI * 2; i += 0.2) {
            d3.push([i, Math.tan(i)]);
          }
          
        
          var sales_charts = $('#sales-charts').css({'width':'100%' , 'height':'220px'});
          $.plot("#sales-charts", [
            { label: "Domains", data: d1 },
            { label: "Hosting", data: d2 },
            { label: "Services", data: d3 }
          ], {
            hoverable: true,
            shadowSize: 0,
            series: {
              lines: { show: true },
              points: { show: true }
            },
            xaxis: {
              tickLength: 0
            },
            yaxis: {
              ticks: 10,
              min: -2,
              max: 2,
              tickDecimals: 3
            },
            grid: {
              backgroundColor: { colors: [ "#fff", "#fff" ] },
              borderWidth: 1,
              borderColor:'#555'
            }
          });
        
        
          $('#recent-box [data-rel="tooltip"]').tooltip({placement: tooltip_placement});
          function tooltip_placement(context, source) {
            var $source = $(source);
            var $parent = $source.closest('.tab-content')
            var off1 = $parent.offset();
            var w1 = $parent.width();
        
            var off2 = $source.offset();
            //var w2 = $source.width();
        
            if( parseInt(off2.left) < parseInt(off1.left) + parseInt(w1 / 2) ) return 'right';
            return 'left';
          }
        
        
          $('.dialogs,.comments').ace_scroll({
            size: 300
          });
          
          
          //Android's default browser somehow is confused when tapping on label which will lead to dragging the task
          //so disable dragging when clicking on label
          var agent = navigator.userAgent.toLowerCase();
          if(ace.vars['touch'] && ace.vars['android']) {
            $('#tasks').on('touchstart', function(e){
            var li = $(e.target).closest('#tasks li');
            if(li.length == 0)return;
            var label = li.find('label.inline').get(0);
            if(label == e.target || $.contains(label, e.target)) e.stopImmediatePropagation() ;
            });
          }
        
          $('#tasks').sortable({
            opacity:0.8,
            revert:true,
            forceHelperSize:true,
            placeholder: 'draggable-placeholder',
            forcePlaceholderSize:true,
            tolerance:'pointer',
            stop: function( event, ui ) {
              //just for Chrome!!!! so that dropdowns on items don't appear below other items after being moved
              $(ui.item).css('z-index', 'auto');
            }
            }
          );
          $('#tasks').disableSelection();
          $('#tasks input:checkbox').removeAttr('checked').on('click', function(){
            if(this.checked) $(this).closest('li').addClass('selected');
            else $(this).closest('li').removeClass('selected');
          });
        
        
          //show the dropdowns on top or bottom depending on window height and menu position
          $('#task-tab .dropdown-hover').on('mouseenter', function(e) {
            var offset = $(this).offset();
        
            var $w = $(window)
            if (offset.top > $w.scrollTop() + $w.innerHeight() - 100) 
              $(this).addClass('dropup');
            else $(this).removeClass('dropup');
          });
        
        })
      </script>
    
</body>
</html><?php
}else{
  /* echo "Sesssion belum ada<br/>";
  echo $_SESSION['atra_id']."  pass ".$_SESSION['hakakses']; */
  echo "<script type=\"text/javascript\">window.location.href=\"./login\";</script>";
}?>

