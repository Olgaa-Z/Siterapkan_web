<?php
date_default_timezone_set("asia/jakarta");

class Etc
{
    public function __construct()
    {
        
    }

    function replaceAll($charToReplace, $string, $change = "-")
    {
        $result = strtolower(preg_replace("/[$charToReplace]/", $change, $string));
        return $result;
    }
    function getBulanIndonesia($idBulan)
    {
        $bulan = array(
            'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        );
        return $bulan[$idBulan];
    }

    function gen_uuid()
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            // 16 bits for "time_mid"
            mt_rand(0, 0xffff),
            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand(0, 0x0fff) | 0x4000,
            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand(0, 0x3fff) | 0x8000,
            // 48 bits for "node"
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );
    }

    function greetings()
    {
        date_default_timezone_set("Asia/Jakarta");
        $hours = date('H');
        if ($hours >= 5 && $hours < 10) {
            return "Selamat Pagi,";
        } else if ($hours >= 10 && $hours < 15) {
            return "Selamat Siang,";
        } else if ($hours >= 15 && $hours < 19) {
            return "Selamat Sore,";
        } else if ($hours >= 19 && $hours <= 24) {
            return "Selamat Malam,";
        }
    }
    public function indonesiaDate($tgl, $jam = null, $delimiter = " ", $isCut = false)
    {
        $hari = substr($tgl, 8, 2);
        // if($hari < 10){
        // 	$hari = "0".$hari;
        // }
        $tahun = ($isCut) ? substr(substr($tgl, 0, 4), -2) : substr($tgl, 0, 4);
        $nama_bulan = $this->bulan($tgl, $isCut);
        $tgl_oke = $hari . ' ' . $nama_bulan . ' ' . $tahun . "$delimiter " . $jam;
        return $tgl_oke;
    }
    function bulan($tgl, $isCut)
    {
        $bulan = substr($tgl, 5, 2);
        switch ($bulan) {
            case 1:
                $bulan = ($isCut) ? "Jan" : "Januari";
                break;
            case 2:
                $bulan = ($isCut) ? "Feb" : "Februari";
                break;
            case 3:
                $bulan = ($isCut) ? "Mar" : "Maret";
                break;
            case 4:
                $bulan = ($isCut) ? "Apr" : "April";
                break;
            case 5:
                $bulan = ($isCut) ? "Mei" : "Mei";
                break;
            case 6:
                $bulan = ($isCut) ? "Jun" : "Juni";
                break;
            case 7:
                $bulan = ($isCut) ? "Jul" : "Juli";
                break;
            case 8:
                $bulan = ($isCut) ? "Agus" : "Agustus";
                break;
            case 9:
                $bulan = ($isCut) ? "Sep" : "September";
                break;
            case 10:
                $bulan = ($isCut) ? "Okt" : "Oktober";
                break;
            case 11:
                $bulan = ($isCut) ? "Nov" : "November";
                break;
            case 12:
                $bulan = ($isCut) ? "Des" : "Desember";
                break;
        }
        return $bulan;
    }
}
