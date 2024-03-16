<?php

namespace App\Controllers\Admin_akademik;

use App\Controllers\BaseController;

use App\Libraries\Access_API; // Import library
use App\Libraries\QRcodelib; // Import library
use CodeIgniter\Database\Query;
use Dompdf\Dompdf;
use Dompdf\Options;

class Beranda extends BaseController
{
  public function __construct()
  {
    $this->api = new Access_API();
    $this->qr = new QRcodelib();
    $this->db = \Config\Database::connect();
  }
  public function index()
  {
    if (session()->get('ses_id') == '' || session()->get('ses_login') != 'admin_akademik') {
      return redirect()->to('/');
    }

    $data = [
      'title' => 'Beranda Admin akademik',
      'db' => $this->db,
      'data_mhs' => $this->db->query("SELECT * FROM tb_users WHERE idunit='" . session()->get('ses_idunit') . "' AND role='mahasiswa' ORDER BY id ASC")->getResult(),
      'data_periode' => $this->db->query("SELECT * FROM tb_periode")->getResult(),
      'data_jadwal' => $this->db->query("SELECT * FROM tb_jadwal_sidang WHERE idunit='" . session()->get('ses_idunit') . "' AND jenis_sidang='sidang skripsi'")->getResult(),
    ];
    return view('Admin_akademik/Beranda', $data);
  }
}
