<?php

namespace App\Controllers\Admin_akademik;

use App\Controllers\BaseController;

use App\Libraries\Access_API; // Import library
use DateTime;

class Penjadwalan_sidang extends BaseController
{
  public function __construct()
  {
    $this->api = new Access_API();
    $this->db = \Config\Database::connect();
  }
  public function index()
  {
    if (session()->get('ses_id') == '' || session()->get('ses_login') != 'admin_akademik') {
      return redirect()->to('/');
    }
    $idunit = session()->get('ses_idunit');
    $idjurusan = $this->db->query("SELECT * FROM `tb_unit` WHERE idunit = '$idunit'")->getResult()[0]->parentunit;
    $data = [
      'title' => 'Penjadwalan Sidang',
      'db' => $this->db,
      'idunit' => $idunit,
      'idjurusan' => $idjurusan,
      'data_jadwal' => $this->db->query("SELECT * FROM tb_jadwal_sidang WHERE idunit='$idunit'")->getResult(),
    ];
    return view('Admin_akademik/penjadwalan_sidang', $data);
  }

  public function data_pendaftar()
  {
    if (session()->get('ses_id') == '' || session()->get('ses_login') != 'admin_akademik') {
      return redirect()->to('/');
    }
    $id_jadwal = $this->request->getPost('idjadwal');
    $idunit = $this->request->getPost('idunit');
    $idjurusan = $this->request->getPost('idjurusan');
    if ($id_jadwal !== null) {
      $idFakultas = $this->db->query("SELECT parentunit FROM tb_unit WHERE idunit='" . $idjurusan . "'")->getResult()[0]->parentunit;
      $data = [
        'title' => 'Data Pendaftar Sidang',
        'db' => $this->db,
        'idunit' => $idunit,
        'id_jadwal' => $id_jadwal,
        'idjurusan' => $idjurusan,
        'data_dosen_fakultas' => $this->db->query("SELECT a.*, b.parentunit as 'jurusan', c.parentunit as fakultas FROM tb_dosen a join tb_unit b on a.idunit = b.idunit join tb_unit c on b.parentunit=c.idunit WHERE c.parentunit = '" . $idFakultas . "'")->getResult(),
        'data_dosen_f' => $this->db->query("SELECT * FROM tb_dosen a LEFT JOIN tb_unit b ON a.`idunit`=b.`idunit` LEFT JOIN tb_unit c ON b.`parentunit`=c.`idunit` WHERE c.`idunit`='$idjurusan'")->getResult(),
        'data_dosen_prodi' => $this->db->query("SELECT * from tb_dosen WHERE idunit = '" . $idunit . "'")->getResult(),
        'data_pendaftar' => $this->db->query("SELECT * FROM tb_pendaftar_sidang WHERE id_jadwal='$id_jadwal'")->getResult(),
        'data_jadwal' => $this->db->query("SELECT * FROM tb_jadwal_sidang WHERE id_jadwal='$id_jadwal'")->getResult(),
      ];
      session()->set('ses_id_jadwal', $id_jadwal);
      return view('Admin_akademik/data_pendaftar_sidang', $data);
    }
  }
}
