<?php

namespace App\Controllers\Mahasiswa;

use App\Controllers\BaseController;

use App\Libraries\Access_API; // Import library

class Informasi_nilai extends BaseController
{
  public function __construct()
  {
    $this->api = new Access_API();
    $this->db = \Config\Database::connect();
  }
  public function index()
  {
    if (session()->get('ses_id') == '' || session()->get('ses_login') != 'mahasiswa') {
      return redirect()->to('/');
    }
    $id = session()->get('ses_id');
    // $id = '160431100078';
    $data_judul = $this->db->query("SELECT judul_topik FROM `tb_pengajuan_topik` WHERE `judul_topik` IS NOT NULL AND nim = '" . $id . "'")->getResult();
    $nilai_pem1 = $this->db->query("SELECT * FROM `tb_nilai` a  WHERE a.nim = '" . $id . "' AND sebagai = 'pembimbing 1'")->getResult();
    $nilai_pem2 = $this->db->query("SELECT * FROM `tb_nilai` a  WHERE a.nim = '" . $id . "' AND sebagai = 'pembimbing 2'")->getResult();

    // penguji 
    $penguji1  = $this->db->query("SELECT * FROM tb_penguji a WHERE a.nim='" . session()->get('ses_id')  . "' AND a.status='aktif' AND a.jenis_sidang = 'sidang skripsi' AND a.sebagai=1")->getResult();
    if (empty($penguji1)) {
      $penguji1  = $this->db->query("SELECT * FROM tb_penguji a WHERE a.nim='" . session()->get('ses_id')  . "' AND a.status='aktif' AND a.jenis_sidang = '' AND a.sebagai=1")->getResult();
    }
    $penguji2  = $this->db->query("SELECT * FROM tb_penguji a WHERE a.nim='" . session()->get('ses_id')  . "' AND a.status='aktif' AND a.jenis_sidang = 'sidang skripsi' AND a.sebagai=2")->getResult();
    if (empty($penguji2)) {
      $penguji2  = $this->db->query("SELECT * FROM tb_penguji a WHERE a.nim='" . session()->get('ses_id')  . "' AND a.status='aktif' AND a.jenis_sidang = '' AND a.sebagai=2")->getResult();
    }
    $penguji3  = $this->db->query("SELECT * FROM tb_penguji a WHERE a.nim='" . session()->get('ses_id')  . "' AND a.status='aktif' AND a.jenis_sidang = 'sidang skripsi' AND a.sebagai=3")->getResult();
    if (empty($penguji3)) {
      $penguji3  = $this->db->query("SELECT * FROM tb_penguji a WHERE a.nim='" . session()->get('ses_id')  . "' AND a.status='aktif' AND a.jenis_sidang = '' AND a.sebagai=3")->getResult();
    }

    $nilai_penguji1 = $this->db->query("SELECT * FROM `tb_nilai` a  WHERE a.nim = '" . $id . "' AND sebagai = 'penguji 1'AND nip ='" . $penguji1[0]->nip . "'")->getResult();
    $nilai_penguji2 = $this->db->query("SELECT * FROM `tb_nilai` a  WHERE a.nim = '" . $id . "' AND sebagai = 'penguji 2'AND nip ='" . $penguji2[0]->nip . "'")->getResult();
    $nilai_penguji3 = $this->db->query("SELECT * FROM `tb_nilai` a  WHERE a.nim = '" . $id . "' AND sebagai = 'penguji 3'AND nip ='" . $penguji3[0]->nip . "'")->getResult();
    $db = $this->db;
    $pembimbing1 = $db->query("SELECT * FROM tb_nilai WHERE nim = '" . $id . "' AND sebagai='pembimbing 1'")->getResult();
    $pembimbing2 = $db->query("SELECT * FROM tb_nilai WHERE nim = '" . $id . "' AND sebagai='pembimbing 2'")->getResult();
    // $penguji1 = $db->query("SELECT * FROM tb_nilai WHERE nim = '" . $id . "' AND sebagai='penguji 1'")->getResult();
    // $penguji2 = $db->query("SELECT * FROM tb_nilai WHERE nim = '" . $id . "' AND sebagai='penguji 2'")->getResult();
    // $penguji3 = $db->query("SELECT * FROM tb_nilai WHERE nim = '" . $id . "' AND sebagai='penguji 3'")->getResult();
    if (!empty($pembimbing1)) {
      $nb_pembimbing1 = $pembimbing1[0]->nilai_bimbingan == NULL ? 0 : $pembimbing1[0]->nilai_bimbingan;
      $ns_pembimbing1 = $pembimbing1[0]->nilai_ujian == NULL ? 0 : $pembimbing1[0]->nilai_ujian;
    } else {
      $nb_pembimbing1 = 0;
      $ns_pembimbing1 = 0;
    }
    if (!empty($pembimbing2)) {
      $nb_pembimbing2 = $pembimbing2[0]->nilai_bimbingan == NULL ? 0 : $pembimbing2[0]->nilai_bimbingan;
      $ns_pembimbing2 = $pembimbing2[0]->nilai_ujian == NULL ? 0 : $pembimbing2[0]->nilai_ujian;
    } else {
      $nb_pembimbing2 = 0;
      $ns_pembimbing2 = 0;
    }
    if (!empty($nilai_penguji1)) {
      $ns_penguji1 = $nilai_penguji1[0]->nilai_ujian == NULL ? 0 : $nilai_penguji1[0]->nilai_ujian;
    } else {
      $ns_penguji1 = 0;
    }
    if (!empty($nilai_penguji2)) {
      $ns_penguji2 = $nilai_penguji2[0]->nilai_ujian == NULL ? 0 : $nilai_penguji2[0]->nilai_ujian;
    } else {
      $ns_penguji2 = 0;
    }
    if (!empty($nilai_penguji3)) {
      $ns_penguji3 = $nilai_penguji3[0]->nilai_ujian == NULL ? 0 : $nilai_penguji3[0]->nilai_ujian;
    } else {
      $ns_penguji3 = 0;
    }
    $nb = (($nb_pembimbing1 + $nb_pembimbing2) / 2) * (60 / 100);
    $ns = (($ns_pembimbing1 + $ns_pembimbing2 + $ns_penguji1 + $ns_penguji2 + $ns_penguji3) / 5) * (40 / 100);
    $total = $nb + $ns;
    $grade = "E";
    $sidang = $db->query("SELECT * FROM tb_pendaftar_sidang a LEFT JOIN tb_jadwal_sidang b ON a.`id_jadwal`=b.`id_jadwal` WHERE a.`nim`='" . $id . "' AND b.`jenis_sidang`='sidang skripsi' ORDER BY create_at DESC LIMIT 1")->getResult();
    if (!empty($sidang)) {
      if ($total >= 80) {
        $grade = "A";
      } elseif ($total >= 75 && $total < 80) {
        $grade = "B+";
      } elseif ($total >= 70 && $total < 75) {
        $grade = "B";
      } elseif ($total >= 65 && $total < 70) {
        $grade = "C+";
      } elseif ($total >= 60 && $total < 65) {
        $grade = "C";
      } elseif ($total >= 55 && $total < 60) {
        $grade = "D+";
      } elseif ($total >= 50 && $total < 55) {
        $grade = "D";
      } else {
        $grade = "E";
      }
    };

    // else {
    $data = [
      'title' => 'Daftar Nilai Ujian Skripsi',
      'db' => $this->db,
      'data_judul' => $data_judul[0]->judul_topik,
      'penguji1' => $penguji1,
      'penguji2' => $penguji2,
      'penguji3' => $penguji3,
      'dosen_pembimbing' => $this->db->query("SELECT * FROM tb_pengajuan_pembimbing a WHERE a.nim='" . $id . "' AND a.status_pengajuan='diterima' ORDER BY a.`sebagai` ASC ")->getResult(),
      'nilai_pem1' => $nilai_pem1,
      'nilai_pem2' => $nilai_pem2,
      'nilai_penguji1' => $nilai_penguji1,
      'nilai_penguji2' => $nilai_penguji2,
      'nilai_penguji3' => $nilai_penguji3,
      'grade' => $grade
    ];
    return view('Mahasiswa/informasi_nilai', $data);
  }
}
