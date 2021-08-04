<?php
include 'Etc.php';
class Services extends Etc
{

    public function getDisposisi($con, $userid)
    {
        $q = "SELECT * FROM arsip_sm 
        INNER JOIN surat_read 
        ON arsip_sm.id_sm=surat_read.id_sm
        WHERE surat_read.id_user IN ('$userid') AND kode IN ('DIS')";
        $query = mysqli_query($con, $q);
        $data['data__surat_masuk'] =  [];
        $masuk = mysqli_fetch_all($query, MYSQLI_ASSOC);
        
        foreach ($masuk as $i) {
            $tmp = [];
            $tmp['id_sm'] = $i['id_sm'];
            $tmp['id_user'] = $i['id_user'];
            $tmp['no_sm'] = $i['no_sm'];
            $tmp['tgl_terima'] = $i['tgl_terima'];
            $tmp['no_agenda'] = $i['no_agenda'];
            $tmp['custom_noagenda'] = $i['custom_noagenda'];
            $tmp['klasifikasi'] = $i['klasifikasi'];
            $tmp['tgl_surat'] = $this->indonesiaDate(date_format(date_create($i['tgl_surat']), 'Y-m-d'));
            $tmp['tgl_retensi'] = $this->indonesiaDate(date_format(date_create($i['tgl_retensi']), 'Y-m-d'));
            $tmp['pengirim'] = $i['pengirim'];
            $tmp['tujuan_surat'] = $i['tujuan_surat'];
            $tmp['perihal'] = $i['perihal'];
            $tmp['ket'] = $i['ket'];
            $tmp['file'] = $i['file'];
            $tmp['view'] = $i['view'];
            $tmp['created'] = $this->indonesiaDate(date_format(date_create($i['created']), 'Y-m-d'));

            array_push($data['data__surat_masuk'], $tmp);
        }
        if (!empty($data['data__surat_masuk'])) {
            $data['message'] = "Data Ditemukan";
            $data['code'] = 200;
        } else {
            $data['message'] = "Data Tidak DItemukan";
            $data['code'] = 404;
        }
        echo json_encode($data);
    }

    // public function detailDisposisi($con, $)
    // {
    //     # code...
    // }

    public function getSuratMasuk($con, $userid)
    {
        $userLike = "'%\"$userid\"%'";
        $q = "SELECT * FROM arsip_sm WHERE tujuan_surat LIKE $userLike";
        $query = mysqli_query($con, $q);
        $data['data__surat_masuk'] =  [];
        $masuk = mysqli_fetch_all($query, MYSQLI_ASSOC);
        
        foreach ($masuk as $i) {
            $tmp = [];
            $tmp['id_sm'] = $i['id_sm'];
            $tmp['id_user'] = $i['id_user'];
            $tmp['no_sm'] = $i['no_sm'];
            $tmp['tgl_terima'] = $i['tgl_terima'];
            $tmp['no_agenda'] = $i['no_agenda'];
            $tmp['custom_noagenda'] = $i['custom_noagenda'];
            $tmp['klasifikasi'] = $i['klasifikasi'];
            $tmp['tgl_surat'] = $this->indonesiaDate(date_format(date_create($i['tgl_surat']), 'Y-m-d'));
            $tmp['tgl_retensi'] = $this->indonesiaDate(date_format(date_create($i['tgl_retensi']), 'Y-m-d'));
            $tmp['pengirim'] = $i['pengirim'];
            $tmp['tujuan_surat'] = $i['tujuan_surat'];
            $tmp['perihal'] = $i['perihal'];
            $tmp['ket'] = $i['ket'];
            $tmp['file'] = $i['file'];
            $tmp['view'] = $i['view'];
            $tmp['created'] = $this->indonesiaDate(date_format(date_create($i['created']), 'Y-m-d'));

            array_push($data['data__surat_masuk'], $tmp);
        }
        if (!empty($data['data__surat_masuk'])) {
            $data['message'] = "Data Ditemukan";
            $data['code'] = 200;
        } else {
            $data['message'] = "Data Tidak DItemukan";
            $data['code'] = 404;
        }
        echo json_encode($data);
    }

    public function getDetailSuratMasuk($con, $idsm)
    {
        $q = "SELECT * FROM arsip_sm WHERE id_sm IN ('$idsm')";
        $query = mysqli_query($con, $q);
        // $data = [];
        $i = mysqli_fetch_array($query);
        // foreach ($detail as $i) {
        $tmp = [];
        $tmp['id_sm'] = $i['id_sm'];
        $tmp['id_user'] = $i['id_user'];
        $tmp['pengirim_info'] = [];
        $tmp['no_agenda'] = $i['no_agenda'];
        $tmp['tgl_terima'] = $i['tgl_terima'];
        $tmp['custom_noagenda'] = $i['custom_noagenda'];
        $tmp['tgl_surat'] =  $this->indonesiaDate(date_format(date_create($i['tgl_surat']), 'Y-m-d'));
        $tmp['tgl_retensi'] = $this->indonesiaDate(date_format(date_create($i['tgl_retensi']), 'Y-m-d'));
        $tmp['pengirim'] = $i['pengirim'];
        $tmp['perihal'] = $i['perihal'];
        $tmp['ket'] = $i['ket'];
        $tmp['file'] = $i['file'];
        $tmp['created'] = $i['created'];
        $tmp['tujuan'] = [];
        $str = $i['tujuan_surat'];
        $result = strtolower(preg_replace('/["\]\[]/', "", $str));
        $xp = explode(",", $result);

        $pengirim = mysqli_query($con, "SELECT id_user,nik,nama,uname,`level`,email, jabatan as jabatan_id,
            user_jabatan.nama_jabatan as jabatan FROM user 
            LEFT JOIN user_jabatan ON user.jabatan=user_jabatan.id_jab
            WHERE id_user IN ('" . $i['id_user'] . "')");

        $tmp['pengirim_info'] =  (object) mysqli_fetch_assoc($pengirim);

        foreach ($xp as $s) {
            $qu = mysqli_query($con, "SELECT uname, nama, id_user FROM user WHERE id_user IN ('$s')");
            $info = mysqli_fetch_assoc($qu);
            array_push($tmp['tujuan'], $info);
        }

        $klasifikasi = mysqli_query($con, "SELECT * FROM klasifikasi WHERE id_klas IN ('" . $i['klasifikasi'] . "')");

        $tmp['klasifikasi'] =  (object) mysqli_fetch_assoc($klasifikasi);

        // }
        if (!empty($tmp)) {
            $tmp['message'] = "Data Ditemukan";
            $tmp['code'] = 200;
        } else {
            $tmp['message'] = "Data Tidak Ditemukan";
            $tmp['code'] = 500;
        }
        echo json_encode($tmp);
    }

    public function getArsipSuratMasuk($con)
    {
        $q = "SELECT * FROM arsip_sm";
        $query = mysqli_query($con, $q);
        $data['data__surat_masuk'] =  [];
        $masuk = mysqli_fetch_all($query, MYSQLI_ASSOC);
        
        foreach ($masuk as $i) {
            $tmp = [];
            $tmp['id_sm'] = $i['id_sm'];
            $tmp['id_user'] = $i['id_user'];
            $tmp['no_sm'] = $i['no_sm'];
            $tmp['tgl_terima'] = $i['tgl_terima'];
            $tmp['no_agenda'] = $i['no_agenda'];
            $tmp['custom_noagenda'] = $i['custom_noagenda'];
            $tmp['klasifikasi'] = $i['klasifikasi'];
            $tmp['tgl_surat'] = $this->indonesiaDate(date_format(date_create($i['tgl_surat']), 'Y-m-d'));
            $tmp['tgl_retensi'] = $this->indonesiaDate(date_format(date_create($i['tgl_retensi']), 'Y-m-d'));
            $tmp['pengirim'] = $i['pengirim'];
            $tmp['tujuan_surat'] = $i['tujuan_surat'];
            $tmp['perihal'] = $i['perihal'];
            $tmp['ket'] = $i['ket'];
            $tmp['file'] = $i['file'];
            $tmp['view'] = $i['view'];
            $tmp['created'] = $this->indonesiaDate(date_format(date_create($i['created']), 'Y-m-d'));

            array_push($data['data__surat_masuk'], $tmp);
        }
        if (!empty($data['data__surat_masuk'])) {
            $data['message'] = "Data Ditemukan";
            $data['code'] = 200;
        } else {
            $data['message'] = "Data Tidak DItemukan";
            $data['code'] = 404;
        }
        echo json_encode($data);
    }

    public function getArsipDigital($con)
    {
        $q = "SELECT * FROM arsip_file";
        $query = mysqli_query($con, $q);
        $data['data_arsip'] =  [];
        $arsip = mysqli_fetch_all($query, MYSQLI_ASSOC);
        foreach ($arsip as $ars) {
            $tmp = [];
            $tmp['id_arsip'] = $ars['id_arsip'];
            $tmp['no_arsip'] = $ars['no_arsip'];
            $tmp['tgl_arsip'] = $this->indonesiaDate(date_format(date_create($ars['tgl_arsip']), 'Y-m-d'));
            $tmp['keamanan'] = $ars['keamanan'];
            $tmp['ket'] = $ars['ket'];
            $tmp['file_arsip'] = $ars['file_arsip'];
            $tmp['tgl_upload'] = $this->indonesiaDate(date_format(date_create($ars['tgl_upload']), 'Y-m-d'));
            $tmp['created'] = $ars['created'];

            $pengirim = mysqli_query($con, "SELECT id_user,nik,nama,uname,`level`,email, jabatan as jabatan_id,
            user_jabatan.nama_jabatan as jabatan FROM user 
            LEFT JOIN user_jabatan ON user.jabatan=user_jabatan.id_jab
            WHERE id_user IN ('" . $ars['id_user'] . "')");

            $tmp['user_info'] =  (object) mysqli_fetch_assoc($pengirim);

            $klasifikasi = mysqli_query($con, "SELECT * FROM klasifikasi_arsip WHERE id_klasifikasi IN ('" . $ars['id_klasifikasi'] . "')");

            $tmp['klasifikasi'] =  (object) mysqli_fetch_assoc($klasifikasi);

            array_push($data['data_arsip'], $tmp);
        }

        echo json_encode($data);
    }

    public function getDetailArsipDigital($con, $idArs)
    {
        $q = "SELECT * FROM arsip_file WHERE id_arsip IN ('$idArs')";
        $query = mysqli_query($con, $q);
        $data['data_arsip'] =  [];
        $arsip = mysqli_fetch_all($query, MYSQLI_ASSOC);
        foreach ($arsip as $ars) {
            $tmp = [];
            $tmp['id_arsip'] = $ars['id_arsip'];
            $tmp['no_arsip'] = str_pad($ars['no_arsip'], 4, "0", STR_PAD_LEFT);
            $tmp['tgl_arsip'] = $this->indonesiaDate(date_format(date_create($ars['tgl_arsip']), 'Y-m-d'));
            $tmp['keamanan'] = $ars['keamanan'];
            $tmp['ket'] = $ars['ket'];
            $tmp['file_arsip'] = $ars['file_arsip'];
            $tmp['tgl_upload'] = $this->indonesiaDate(date_format(date_create($ars['tgl_upload']), 'Y-m-d'));
            $tmp['created'] = $ars['created'];

            $pengirim = mysqli_query($con, "SELECT id_user,nik,nama,uname,`level`,email, jabatan as jabatan_id,
            user_jabatan.nama_jabatan as jabatan FROM user 
            LEFT JOIN user_jabatan ON user.jabatan=user_jabatan.id_jab
            WHERE id_user IN ('" . $ars['id_user'] . "')");

            $tmp['user_info'] =  (object) mysqli_fetch_assoc($pengirim);

            $klasifikasi = mysqli_query($con, "SELECT * FROM klasifikasi_arsip WHERE id_klasifikasi IN ('" . $ars['id_klasifikasi'] . "')");

            $tmp['klasifikasi'] =  (object) mysqli_fetch_assoc($klasifikasi);

            array_push($data['data_arsip'], $tmp);
        }

        echo json_encode($data);
    }

    public function getArsipSuratKeluar($con)
    {
        $q = "SELECT * FROM arsip_sk";
        $query = mysqli_query($con, $q);
        $data['data'] =  mysqli_fetch_all($query, MYSQLI_ASSOC);
        echo json_encode($data);
    }

    public function getSuratKeluar($con)
    {
        $q = "SELECT * FROM `arsip_sk`";
        $query = mysqli_query($con, $q);
        $data['data'] =  mysqli_fetch_all($query, MYSQLI_ASSOC);
        echo json_encode($data);
    }

    public function getDetailMemo($con, $infoId)
    {
        $q = "SELECT * FROM `info` WHERE id_info IN ('$infoId')";
        $query = mysqli_query($con, $q);
        $info = mysqli_fetch_all($query, MYSQLI_ASSOC);
        $data['data_memo'] =  [];

        foreach ($info as $i) {
            $tmp = [];
            $tmp['id_info'] = $i['id_info'];
            $tmp['pengirim_info'] = [];
            $tmp['judul_info'] = $i['judul_info'];
            $tmp['ket_info'] = $i['ket_info'];
            $tmp['tgl_info'] = $this->indonesiaDate(date_format(date_create($i['tgl_info']), 'Y-m-d'), date_format(date_create($i['tgl_info']), 'H:i:s'));
            $tmp['tujuan'] = [];
            $str = $i['tujuan_info'];
            $result = strtolower(preg_replace('/["\]\[]/', "", $str));
            $xp = explode(",", $result);

            $pengirim = mysqli_query($con, "SELECT id_user,nik,nama,uname,`level`,email, jabatan as jabatan_id,
            user_jabatan.nama_jabatan as jabatan FROM user 
            LEFT JOIN user_jabatan ON user.jabatan=user_jabatan.id_jab
            WHERE id_user IN ('" . $i['pengirim_info'] . "')");

            $tmp['pengirim_info'] =  (object) mysqli_fetch_assoc($pengirim);

            foreach ($xp as $s) {
                $qu = mysqli_query($con, "SELECT uname, nama, id_user FROM user WHERE id_user IN ('$s')");
                $info = mysqli_fetch_all($qu, MYSQLI_ASSOC);
                $tmp['tujuan'] = $info;
            }

            array_push($data['data_memo'], $tmp);
        }
        echo json_encode($data);
    }

    public function getMemo($con, $username)
    {
        $like = '"%\"' . $username . '\"%"';
        $q = "SELECT * FROM `info` WHERE tujuan_info LIKE $like";
        $query = mysqli_query($con, $q);
        $info = mysqli_fetch_all($query, MYSQLI_ASSOC);
        $data['data_memo'] =  [];

        foreach ($info as $i) {
            $tmp = [];
            $tmp['id_info'] = $i['id_info'];
            $tmp['pengirim_info'] = [];
            $tmp['judul_info'] = $i['judul_info'];
            $tmp['ket_info'] =$i['ket_info'];
            $tmp['tgl_info'] =  $this->indonesiaDate(date_format(date_create($i['tgl_info']), 'Y-m-d'));
            $tmp['tujuan'] = [];
            $str = $i['tujuan_info'];
            $result = strtolower(preg_replace('/["\]\[]/', "", $str));
            $xp = explode(",", $result);

            $pengirim = mysqli_query($con, "SELECT id_user,nik,nama,uname,`level`,email, jabatan as jabatan_id,
            user_jabatan.nama_jabatan as jabatan FROM user 
            LEFT JOIN user_jabatan ON user.jabatan=user_jabatan.id_jab
            WHERE id_user IN ('" . $i['pengirim_info'] . "')");

            $tmp['pengirim_info'] =  (object) mysqli_fetch_assoc($pengirim);

            foreach ($xp as $s) {
                $qu = mysqli_query($con, "SELECT uname, nama, id_user FROM user WHERE id_user IN ('$s')");
                $info = mysqli_fetch_all($qu, MYSQLI_ASSOC);
                $tmp['tujuan'] = $info;
            }

            array_push($data['data_memo'], $tmp);
        }
        echo json_encode($data);
    }

    public function getArsipMemo($con)
    {
        $q = "SELECT * FROM `info`";
        $query = mysqli_query($con, $q);
        $info = mysqli_fetch_all($query, MYSQLI_ASSOC);
        $data['data_memo'] =  [];

        foreach ($info as $i) {
            $tmp = [];
            $tmp['id_info'] = $i['id_info'];
            $tmp['pengirim_info'] = [];
            $tmp['judul_info'] = $i['judul_info'];
            $tmp['ket_info'] = $i['ket_info'];
            $tmp['tgl_info'] =  $this->indonesiaDate(date_format(date_create($i['tgl_info']), 'Y-m-d'));
            $tmp['tujuan'] = [];
            $str = $i['tujuan_info'];
            $result = strtolower(preg_replace('/["\]\[]/', "", $str));
            $xp = explode(",", $result);

            $pengirim = mysqli_query($con, "SELECT id_user,nik,nama,uname,`level`,email, jabatan as jabatan_id,
            user_jabatan.nama_jabatan as jabatan FROM user 
            LEFT JOIN user_jabatan ON user.jabatan=user_jabatan.id_jab
            WHERE id_user IN ('" . $i['pengirim_info'] . "')");

            $tmp['pengirim_info'] =  (object) mysqli_fetch_assoc($pengirim);

            foreach ($xp as $s) {
                $qu = mysqli_query($con, "SELECT uname, nama, id_user FROM user WHERE id_user IN ('$s')");
                $info = mysqli_fetch_all($qu, MYSQLI_ASSOC);
                $tmp['tujuan'] = $info;
            }

            array_push($data['data_memo'], $tmp);
        }
        echo json_encode($data);
    }

    public function login($con, $username, $pass)
    {
        $q = "SELECT id_user,nik,nama,uname,`level`,email, jabatan as jabatan_id,
         user_jabatan.nama_jabatan as jabatan, token, token_expired  FROM `user` 
        LEFT JOIN user_jabatan ON user.jabatan=user_jabatan.id_jab 
        WHERE uname IN ('$username') and upass IN (md5('$pass'))";
        $query = mysqli_query($con, $q);
        $data = mysqli_fetch_assoc($query);

        if (!empty($data)) {
            $data['message'] = "Data Ditemukan";
            $data['code'] = 200;
            $idUser = $data['id_user'];
            $token = $this->gen_uuid();

            $date = date_create(date('Y-m-d H:i:s'));
            date_add($date, date_interval_create_from_date_string("2 days"));
            $expired = date_format($date, "Y-m-d H:i:s");
            $up = "UPDATE `user` SET  token='$token', token_expired='$expired' WHERE id_user='$idUser'";
            mysqli_query($con, $up);
            $data['token'] = $token;
            echo json_encode((object) $data);
        } else {
            echo json_encode(['message' => 'Data Tidak Ditemukan', 'code' => 404]);
        }
    }

    public function getDetailAccount($con, $username)
    {
        $q = "SELECT id_user,nik,nama,uname,`level`,email, jabatan as jabatan_id,
         user_jabatan.nama_jabatan as jabatan, token, token_expired  FROM `user` 
        LEFT JOIN user_jabatan ON user.jabatan=user_jabatan.id_jab 
        WHERE uname IN ('$username')";
        $query = mysqli_query($con, $q);
        $data = mysqli_fetch_assoc($query);

        if (!empty($data)) {
            $data['message'] = "Data Ditemukan";
            $data['code'] = 200;
            echo json_encode((object) $data);
        } else {
            echo json_encode(['message' => 'Data Tidak Ditemukan', 'code' => 404]);
        }
    }
}
