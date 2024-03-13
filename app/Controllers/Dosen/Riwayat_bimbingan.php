<?php

namespace App\Controllers\Dosen;

use App\Controllers\BaseController;

use App\Libraries\Access_API; // Import library

class Riwayat_bimbingan extends BaseController
{
  public function __construct()
  {
    $this->api = new Access_API();
    $this->db = \Config\Database::connect();
  }
  public function index()
  {

    if (session()->get('ses_id') == '' || session()->get('ses_login') == 'mahasiswa') {
      return redirect()->to('/');
    }
    $id = session()->get('ses_id');
    $data_mhs = [];
    $data_mhs_bimbingan_2 = [];

    $data_mhs_bimbingan = $this->db->query("SELECT a.nip,a.nim,a.sebagai,a.status_pengajuan, b.nama as nama_mhs, b.idunit as namaunit  FROM `tb_pengajuan_pembimbing` a LEFT JOIN tb_mahasiswa b ON a.nim = b.nim WHERE a.nip = '" . $id . "'  AND a.status_pengajuan = 'diterima'")->getResult();
    // $data_mhs_uji = $this->db->query("SELECT a.nip,a.nim,a.sebagai, b.nama as nama_mhs, b.idunit as namaunit  FROM `tb_penguji` a LEFT JOIN tb_mahasiswa b ON a.nim = b.nim WHERE a.nip = '" . $id . "'")->getResult();



    $data_mhs = [];
    $id = session()->get('ses_id');
    $data_mhs_uji_jika_diganti = $this->db->query("SELECT a.*,b.`nama` AS nama_mhs, b.`jk`, c.`namaunit`, d.* FROM tb_penguji a LEFT JOIN tb_mahasiswa b ON b.`nim`=a.`nim` LEFT JOIN tb_unit c ON b.`idunit`=c.`idunit` LEFT JOIN tb_profil_tambahan d ON a.`nim`=d.`id` WHERE nip='$id' AND a.status='aktif' AND a.jenis_sidang = 'sidang skripsi'  ")->getResult();
    // $data_mhs_uji = $this->db->query("SELECT a.*,b.`nama` AS nama_mhs, b.`jk`, c.`namaunit`, d.* FROM tb_penguji a LEFT JOIN tb_mahasiswa b ON b.`nim`=a.`nim` LEFT JOIN tb_unit c ON b.`idunit`=c.`idunit` LEFT JOIN tb_profil_tambahan d ON a.`nim`=d.`id` WHERE nip='$id' AND a.status='aktif' ORDER BY a.`jenis_sidang` ASC ")->getResult();
    $data_mhs_uji = $this->db->query("SELECT a.*,b.`nama` AS nama_mhs, b.`jk`, c.`namaunit`, d.* FROM tb_penguji a LEFT JOIN tb_mahasiswa b ON b.`nim`=a.`nim` LEFT JOIN tb_unit c ON b.`idunit`=c.`idunit` LEFT JOIN tb_profil_tambahan d ON a.`nim`=d.`id` WHERE nip='$id' AND a.status='aktif'  ORDER BY a.`jenis_sidang` ASC ")->getResult();
    $mhs_uji_baru = [];
    if (!empty($data_mhs_uji_jika_diganti)) {
      $mhs_ujian = [];
      foreach ($data_mhs_uji_jika_diganti as $key_3) {
        array_push($mhs_ujian, $key_3->id);
      }
      $mhs_uji_baru = [];
      foreach ($data_mhs_uji as $key) {
        if (array_search($key->id, $mhs_ujian) !== false) {
          continue;
        } else {
          array_push($mhs_uji_baru, $key);
        }
      }

      foreach ($data_mhs_uji_jika_diganti as $key_2) {
        array_push($mhs_uji_baru, $key_2);
      }
    } else {
      $mhs_uji_baru = $data_mhs_uji;
    }

    foreach ($data_mhs_bimbingan as $key) {
      $data = [
        'nim' => $key->nim,
        'nama_mhs' => $key->nama_mhs,
        'namaunit' => $key->namaunit,
      ];
      array_push($data_mhs_bimbingan_2, $data);
    }

    foreach ($mhs_uji_baru as $key) {
      $data = [
        'nim' => $key->nim,
        'nama_mhs' => $key->nama_mhs,
        'namaunit' => $key->namaunit,
      ];
      array_push($data_mhs, $data);
    }
    $data = [
      'title' => 'Riwayat Bimbingan',
      'db' => $this->db,
      'data_mhs_bimbingan' => $data_mhs_bimbingan_2,
      'data_mhs_uji' => $data_mhs,
    ];
    return view('Dosen/riwayat_bimbingan', $data);
  }
}
