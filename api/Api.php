<?php
// error_reporting(0);

include "dbs.php";
include "Services.php";
$params = $_REQUEST['params'];
$api = new Services;


switch ($params) {
    case "surat-masuk":
        $userid = $_GET['id_user'];
        $api->getSuratMasuk($con, $userid);
        break;
    case "disposisi":
        $userid = $_GET['id_user'];
        $api->getDisposisi($con, $userid);
        break;
    case "detail-surat-masuk":
        $idsm = $_GET['id_sm'];
        $api->getDetailSuratMasuk($con, $idsm);
        break;
    case "surat-keluar":
        $api->getSuratKeluar($con);
        break;
    case "detail-memo":
        $idInfor = $_REQUEST['id_info'];
        $api->getDetailMemo($con, $idInfor);
        break;
    case "memo":
        $userid = $_REQUEST['id_user'];
        $api->getMemo($con, $userid);
        break;
    case "login":
        $username = $_POST['uname'];
        $pass = $_POST['upass'];
        $api->login($con, $username, $pass);
        break;
    case "profile":
        $username = $_POST['uname'];
        $api->getDetailAccount($con, $username);
        break;
    case "arsip-memo":
        $api->getArsipMemo($con);
        break;
    case "arsip-digital":
        $api->getArsipDigital($con);
        break;
    case "detail-arsip-digital":
        $idArs = $_GET['id_arsip'];
        $api->getDetailArsipDigital($con, $idArs);
        break;
    case "arsip-surat-masuk":
        $api->getArsipSuratMasuk($con);
        break;
    case "arsip-surat-keluar":
        $api->getArsipSuratKeluar($con);
        break;
}
