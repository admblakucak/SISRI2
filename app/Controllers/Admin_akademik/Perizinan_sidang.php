<?php

namespace App\Controllers\Admin_akademik;

use App\Controllers\BaseController;

use App\Libraries\Access_API; // Import library
use App\Libraries\QRcodelib; // Import library
use CodeIgniter\Database\Query;
use Dompdf\Dompdf;
use Dompdf\Options;

class Perizinan_sidang extends BaseController
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

    $id = session()->get('ses_id');
    $data_mhs = [];
    $data_mhs_bimbingan = $this->db->query("SELECT a.*,b.`nama` AS nama_mhs, b.`jk`, c.`namaunit`, d.* FROM tb_perizinan_sidang a LEFT JOIN tb_mahasiswa b ON b.`nim`=a.`nim` LEFT JOIN tb_unit c ON b.`idunit`=c.`idunit` LEFT JOIN tb_profil_tambahan d ON a.`nim`=d.`id` WHERE nip='$id' AND a.`izin_sebagai`='admin_akademik'")->getResult();
    foreach ($data_mhs_bimbingan as $key) {
      if ($key->image != NULL) {
        $image = $key->image;
      } else {
        $image = 'Profile_Default.png';
      }
      $data = [
        'nim' => $key->nim,
        'nama_mhs' => $key->nama_mhs,
        'jk' => $key->jk,
        'namaunit' => $key->namaunit,
        'image' => $image,
        'jenis_sidang' => $key->jenis_sidang,
        'id_perizinan_sidang' => $key->id_perizinan_sidang,
      ];
      array_push($data_mhs, $data);
    }
    $data = [
      'title' => 'Validasi Pendaftar Seminar Proposal',
      'db' => $this->db,
      'data_mhs_bimbingan' => $data_mhs,
    ];
    return view('Admin_akademik/validasi_daftar_sidang', $data);
  }
}
