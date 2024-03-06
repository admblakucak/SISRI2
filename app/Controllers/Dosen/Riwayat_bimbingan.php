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
    $data_mhs_bimbingan = $this->db->query("SELECT a.nip,a.nim,a.sebagai,a.status_pengajuan, b.nama as nama_mhs, b.idunit as namaunit  FROM `tb_pengajuan_pembimbing` a LEFT JOIN tb_mahasiswa b ON a.nim = b.nim WHERE a.nip = '" . $id . "'  AND a.status_pengajuan = 'diterima'")->getResult();
    $data_mhs_uji = $this->db->query("SELECT a.nip,a.nim,a.sebagai, b.nama as nama_mhs, b.idunit as namaunit  FROM `tb_penguji` a LEFT JOIN tb_mahasiswa b ON a.nim = b.nim WHERE a.nip = '".$id."'")->getResult();
    // dd("SELECT a.nip,a.nim,a.sebagai,a.status_pengajuan, b.nama as nama_mhs, b.idunit as namaunit  FROM `tb_pengajuan_pembimbing` a LEFT JOIN tb_mahasiswa b ON a.nim = b.nim WHERE a.nip = '" . $id . "'  AND a.status_pengajuan = 'diterima'");
    foreach ($data_mhs_uji as $key) {
      $data = [
        'nim' => $key->nim,
        'nama_mhs' => $key->nama_mhs,
        'namaunit' => $key->namaunit,
      ];
      array_push($data_mhs, $data);
    }
    // dd($data_mhs);
    $data = [
      'title' => 'Riwayat Bimbingan',
      'db' => $this->db,
      'data_mhs_bimbingan' => $data_mhs,
    ];
    return view('Dosen/riwayat_bimbingan', $data);
  }
}
