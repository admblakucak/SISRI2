<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

use App\Libraries\Access_API; // Import library

class Admin_Akademik extends BaseController
{
  public function __construct()
  {
    $this->api = new Access_API();
    $this->db = \Config\Database::connect();
  }
  public function index()
  {
    if (session()->get('ses_id') == '' || session()->get('ses_login') != 'admin') {
      return redirect()->to('/');
    }
    $data = [
      'tab' => 'Data Dosen',
      'title' => 'Data Admin Akademik',
      'db' => $this->db,
      'data' => $this->db->query("SELECT a.*,b.namaunit AS prodi,c.namaunit AS jurusan, d.namaunit AS fakultas FROM `tb_admin_akademik` a LEFT JOIN tb_unit b ON a.idunit=b.idunit LEFT JOIN tb_unit c ON b.parentunit=c.idunit LEFT JOIN tb_unit d ON c.parentunit=d.idunit")->getResult(),
      'data_prodi' => $this->db->query("SELECT * FROM tb_unit WHERE jenisunit='P'")->getResult(),
      'dosen' => $this->db->query("SELECT * FROM tb_dosen")->getResult()
    ];

    return view('Admin/data_admin_akademik', $data);
  }
  public function add()
  {
    if (session()->get('ses_id') == '' || session()->get('ses_login') != 'admin') {
      return redirect()->to('/');
    }
    $nip = $this->request->getPost("nip");
    $gelar_depan = $this->request->getPost("gelar_depan");
    $nama = $this->request->getPost("nama");
    $gelar_belakang = $this->request->getPost("gelar_belakang");
    $jenis_kelamin = $this->request->getPost("jenis_kelamin");
    $idunit = $this->request->getPost("idunit");
    $email = $this->request->getPost("email");
    if (!$this->validate([
      'idunit' => [
        'rules' => 'required',
        'errors' => [
          'required' => 'Pilih Program Studi'
        ]
      ],
      'nip' => [
        'rules' => 'required',
        'errors' => [
          'required' => 'Pilih Dosen sebagai Admin Akademik'
        ]
      ],
    ])) {
      session()->setFlashdata('message', '<div class="alert alert-danger alert-dismissible fade show mb-0" role="alert">
            <span class="alert-inner--icon"><i class="fe fe-slash"></i></span>
            <span class="alert-inner--text"><strong>Gagal!</strong> ' . $this->validator->listErrors() . '</span>
            <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>');
      return redirect()->to('/data_admin_akademik');
    }

    $cek_idunit = $this->db->query("SELECT * FROM tb_admin_akademik WHERE idunit='$idunit'")->getResult();
    $cek_nip = $this->db->query("SELECT * FROM tb_admin_akademik WHERE nip='$nip'")->getResult();
    if (count($cek_idunit) != NULL) {
      session()->setFlashdata('message', '<div class="alert alert-danger alert-dismissible fade show mb-0" role="alert">
            <span class="alert-inner--icon"><i class="fe fe-slash"></i></span>
            <span class="alert-inner--text"><strong>Gagal!</strong> Program studi sudah ada.</span>
            <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>');
      return redirect()->to('/data_admin_akademik');
    }
    if (count($cek_nip) != NULL) {
      session()->setFlashdata('message', '<div class="alert alert-danger alert-dismissible fade show mb-0" role="alert">
            <span class="alert-inner--icon"><i class="fe fe-slash"></i></span>
            <span class="alert-inner--text"><strong>Gagal!</strong> Dosen sudah terpilih sebagai Admin Akademik.</span>
            <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>');
      return redirect()->to('/data_admin_akademik');
    }
    $this->db->query("INSERT INTO tb_admin_akademik (idunit,nip,nama,email,gelar_depan,gelar_belakang,jenis_kelamin) VALUES ('$idunit','$nip','$nama','$email','$gelar_depan','$gelar_belakang','$jenis_kelamin')");
    session()->setFlashdata('message', '<div class="alert alert-success alert-dismissible fade show mb-0" role="alert">
            <span class="alert-inner--icon"><i class="fe fe-thumbs-up"></i></span>
            <span class="alert-inner--text"><strong>Sukses!</strong> Menambahkan Admin Akademik</span>
            <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>');
    return redirect()->to('/data_admin_akademik');
  }
  public function edit()
  {
    if (session()->get('ses_id') == '' || session()->get('ses_login') != 'admin') {
      return redirect()->to('/');
    }
    $id = $this->request->getPost("id");
    $nip = $this->request->getPost("nip");
    $gelar_depan = $this->request->getPost("gelar_depan");
    $nama = $this->request->getPost("nama");
    $gelar_belakang = $this->request->getPost("gelar_belakang");
    $jenis_kelamin = $this->request->getPost("jenis_kelamin");
    $idunit = $this->request->getPost("idunit");
    $email = $this->request->getPost("email");
    if (!$this->validate([
      'idunit' => [
        'rules' => 'required',
        'errors' => [
          'required' => 'Pilih Program Studi'
        ]
      ],
      'nip' => [
        'rules' => 'required',
        'errors' => [
          'required' => 'Pilih Dosen sebagai Admin Akademik'
        ]
      ],
    ])) {
      session()->setFlashdata('message', '<div class="alert alert-danger alert-dismissible fade show mb-0" role="alert">
            <span class="alert-inner--icon"><i class="fe fe-slash"></i></span>
            <span class="alert-inner--text"><strong>Gagal!</strong> ' . $this->validator->listErrors() . '</span>
            <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>');
      return redirect()->to('/data_admin_akademik');
    }

    $cek_idunit = $this->db->query("SELECT * FROM tb_admin_akademik WHERE idunit='$idunit' AND id_admin_akademik != '$id'")->getResult();
    $cek_nip = $this->db->query("SELECT * FROM tb_admin_akademik WHERE nip='$nip' AND id_admin_akademik != '$id'")->getResult();
    $cek_email = $this->db->query("SELECT * FROM tb_admin_akademik WHERE email='$email' AND id_admin_akademik != '$id'")->getResult();
    if (count($cek_idunit) != NULL) {
      session()->setFlashdata('message', '<div class="alert alert-danger alert-dismissible fade show mb-0" role="alert">
      <span class="alert-inner--icon"><i class="fe fe-slash"></i></span>
      <span class="alert-inner--text"><strong>Gagal!</strong> Program studi sudah ada.</span>
      <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">×</span>
      </button>
      </div>');
      return redirect()->to('/data_admin_akademik');
    } else {
      $cek_email_change = $this->db->query("SELECT email FROM tb_admin_akademik WHERE id_admin_akademik = '$id'")->getResult();
      if ($email != $cek_email_change[0]->email) {
        $this->db->query("DELETE FROM tb_users WHERE email='" . $cek_email_change[0]->email . "' ");
      }
    }
    if (count($cek_nip) != NULL) {
      session()->setFlashdata('message', '<div class="alert alert-danger alert-dismissible fade show mb-0" role="alert">
            <span class="alert-inner--icon"><i class="fe fe-slash"></i></span>
            <span class="alert-inner--text"><strong>Gagal!</strong> NIP Sudah Terdaftar Sebagai Admin Akademik.</span>
            <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>');
      return redirect()->to('/data_admin_akademik');
    }
    if (count($cek_email) != NULL) {
      session()->setFlashdata('message', '<div class="alert alert-danger alert-dismissible fade show mb-0" role="alert">
            <span class="alert-inner--icon"><i class="fe fe-slash"></i></span>
            <span class="alert-inner--text"><strong>Gagal!</strong> Email Sudah Terdaftar Sebagai Admin Akademik</span>
            <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>');
      return redirect()->to('/data_admin_akademik');
    }
    $this->db->query("UPDATE tb_admin_akademik set idunit='$idunit',nip='$nip',nama='$nama',email='$email',gelar_depan='$gelar_depan',gelar_belakang='$gelar_belakang',jenis_kelamin='$jenis_kelamin' WHERE id_admin_akademik='$id'");
    session()->setFlashdata('message', '<div class="alert alert-success alert-dismissible fade show mb-0" role="alert">
            <span class="alert-inner--icon"><i class="fe fe-thumbs-up"></i></span>
            <span class="alert-inner--text"><strong>Sukses!</strong> Berhasil Update Admin Akademik</span>
            <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>');
    return redirect()->to('/data_admin_akademik');
  }
  public function delete()
  {
    if (session()->get('ses_id') == '' || session()->get('ses_login') != 'admin') {
      return redirect()->to('/');
    }
    $id_admin_akademik = $this->request->getPost("id_admin_akademik");
    $email = $this->request->getPost("email");
    $this->db->query("DELETE FROM tb_admin_akademik WHERE id_admin_akademik = $id_admin_akademik");
    $this->db->query("DELETE FROM tb_users WHERE email = '" . $email . "'");

    session()->setFlashdata('message', '<div class="alert alert-danger alert-dismissible fade show mb-0" role="alert">
            <span class="alert-inner--icon"><i class="fe fe-thumbs-up"></i></span>
            <span class="alert-inner--text"><strong>Sukses!</strong> Menghapus Admin Akademik</span>
            <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>');
    return redirect()->to('/data_admin_akademik');
  }
}
