<?php
ini_set('date.timezone', 'Asia/Jakarta');
require_once "view/indo_tgl.php";
require_once "htmlpurifier/library/HTMLPurifier.auto.php";
$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);
if($_SERVER["REQUEST_METHOD"] == "POST"){
  /* print_r($_POST);
  $pass = md5(htmlspecialchars($purifier->purify($_POST['password']), ENT_QUOTES));
  echo $pass; */
  $user = htmlspecialchars($purifier->purify(trim($_POST['username'])), ENT_QUOTES);
  $pass = md5(htmlspecialchars($purifier->purify(trim($_POST['password'])), ENT_QUOTES));
  $params = array(':user' => $user, ':pass' => $pass);
  $cek = $this->model->selectprepare("user", $field=null, $params, "uname=:user AND upass=:pass");
  if($cek->rowCount() >= 1){
    $data = $cek->fetch(PDO::FETCH_OBJ);
    $_SESSION['id_user'] = $data->id_user;
    $_SESSION['nama'] = $data->nama;
    $_SESSION['atra_id'] = $data->uname;
    $_SESSION['atra_pass'] = $data->upass;
    $_SESSION['hakakses'] = $data->level;
    //$_SESSION['nsalt'] = $data->salt;
    $_SESSION['picture'] = $data->picture;
    echo "<script type=\"text/javascript\">window.location.href=\"./\";</script>";
  }else{
    echo "<script type=\"text/javascript\">alert('Username / Password Anda Salah. Silahkan Ulangi Kembali..!!');window.history.go(-1);</script>";
  }
}
//session_destroy();?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login</title>
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
  </head>
  <body>
    <div class="container-scroller">
      <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth auth-bg-1 theme-one">
          <div class="row w-100">
            <div class="col-lg-4 mx-auto">
              <div class="auto-form-wrapper"> 
              <img src="dist/img/user2-160x160.png" style="height: 100px;margin-left: auto; margin-right: auto; margin-bottom: 20px; display: block; align-items: center;"> 
                  <h3 style="text-align: center;"> <b>SITERAPKAN</b> </h3>
                  <h6 style="text-align: center; margin-bottom: 30px;">Sistem Tertib Administrasi Persuratan dan Kearsipan</h6>

                <form method="POST" autocomplete="off" action="<?php echo $_SESSION['url'];?>">
                  <div class="form-group">
                    <label class="label">Username</label>
                    <div class="input-group">
                      <input type="text" name="username" class="form-control" placeholder="Username">
                      <div class="input-group-append">
                        <span class="input-group-text">
                          <i class="mdi mdi-check-circle-outline"></i>
                        </span>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="label">Password</label>
                    <div class="input-group">
                      <input type="password" name="password" class="form-control" placeholder="Password">
                      <div class="input-group-append">
                        <span class="input-group-text">
                          <i class="mdi mdi-check-circle-outline"></i>
                        </span>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <button type="submit" class="btn btn-primary submit-btn btn-block">Login</button>
                  </div>

                  <div class="form-group">
                    <a href=".?op=tracking" class="btn btn-block g-login"> Tracking Surat 
                    </a>
                  </div>
       <!--          
                <div class="row">
                  <div col-md-10 >

                    <div class="form-group btn g-login">

                    <a href=".?op=tracking">
                     Tracking Surat  <img src="dist/img/user2-160x160.png" style="height: 40px;margin-left: auto; margin-right: auto; margin-bottom: 20px; display: block; align-items: center;">
                    </a>
                  </div>
                    
                  </div>
                  
                </div> -->

                  

                   

                  
               
              
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
    <script src="../login/assets/js/shared/jquery.cookie.js" type="text/javascript"></script>

    <script type="text/javascript">
      if('ontouchstart' in document.documentElement) document.write("<script src='assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
    </script>

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