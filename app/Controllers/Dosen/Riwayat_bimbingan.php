<?php

namespace App\Controllers\Dosen;

use App\Controllers\BaseController;
use Dompdf\Dompdf;
use App\Libraries\QRcodelib; // Import library
use Dompdf\Options;

use App\Libraries\Access_API; // Import library

class Riwayat_bimbingan extends BaseController
{
  public function __construct()
  {
    $this->api = new Access_API();
    $this->qr = new QRcodelib();
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

    // $data_mhs_uji = $this->db->query("SELECT a.nip,a.nim,a.sebagai, b.nama as nama_mhs, b.idunit as namaunit  FROM `tb_penguji` a LEFT JOIN tb_mahasiswa b ON a.nim = b.nim WHERE a.nip = '" . $id . "'")->getResult();

    $id_periode = $this->request->getPost('id_periode');
    $id = session()->get('ses_id');
    if (isset($id_periode)) {
      $data_mhs_uji_jika_diganti = $this->db->query("SELECT a.*,b.`nama` AS nama_mhs, b.`jk`, c.`namaunit`, d.* FROM tb_penguji a LEFT JOIN tb_mahasiswa b ON b.`nim`=a.`nim` LEFT JOIN tb_unit c ON b.`idunit`=c.`idunit` LEFT JOIN tb_profil_tambahan d ON a.`nim`=d.`id` WHERE nip='$id' AND a.status='aktif' AND a.jenis_sidang = 'sidang skripsi' AND b.idperiode='$id_periode'  ")->getResult();
      $data_mhs_uji = $this->db->query("SELECT a.*,b.`nama` AS nama_mhs, b.`jk`, c.`namaunit`, d.* FROM tb_penguji a LEFT JOIN tb_mahasiswa b ON b.`nim`=a.`nim` LEFT JOIN tb_unit c ON b.`idunit`=c.`idunit` LEFT JOIN tb_profil_tambahan d ON a.`nim`=d.`id` WHERE nip='$id' AND a.status='aktif' AND b.idperiode='$id_periode'  ORDER BY a.`jenis_sidang` ASC ")->getResult();
      $data_mhs_bimbingan = $this->db->query("SELECT a.nip,a.nim,a.sebagai,a.status_pengajuan, b.nama as nama_mhs, b.idunit as namaunit  FROM `tb_pengajuan_pembimbing` a LEFT JOIN tb_mahasiswa b ON a.nim = b.nim WHERE a.nip = '" . $id . "'  AND a.status_pengajuan = 'diterima' AND b.idperiode='$id_periode'")->getResult();
    } else {
      $data_mhs_bimbingan = $this->db->query("SELECT a.nip,a.nim,a.sebagai,a.status_pengajuan, b.nama as nama_mhs, b.idunit as namaunit  FROM `tb_pengajuan_pembimbing` a LEFT JOIN tb_mahasiswa b ON a.nim = b.nim WHERE a.nip = '" . $id . "'  AND a.status_pengajuan = 'diterima'")->getResult();
      $data_mhs_uji_jika_diganti = $this->db->query("SELECT a.*,b.`nama` AS nama_mhs, b.`jk`, c.`namaunit`, d.* FROM tb_penguji a LEFT JOIN tb_mahasiswa b ON b.`nim`=a.`nim` LEFT JOIN tb_unit c ON b.`idunit`=c.`idunit` LEFT JOIN tb_profil_tambahan d ON a.`nim`=d.`id` WHERE nip='$id' AND a.status='aktif' AND a.jenis_sidang = 'sidang skripsi'  ")->getResult();
      $data_mhs_uji = $this->db->query("SELECT a.*,b.`nama` AS nama_mhs, b.`jk`, c.`namaunit`, d.* FROM tb_penguji a LEFT JOIN tb_mahasiswa b ON b.`nim`=a.`nim` LEFT JOIN tb_unit c ON b.`idunit`=c.`idunit` LEFT JOIN tb_profil_tambahan d ON a.`nim`=d.`id` WHERE nip='$id' AND a.status='aktif'  ORDER BY a.`jenis_sidang` ASC ")->getResult();
    }
    // $data_mhs_uji = $this->db->query("SELECT a.*,b.`nama` AS nama_mhs, b.`jk`, c.`namaunit`, d.* FROM tb_penguji a LEFT JOIN tb_mahasiswa b ON b.`nim`=a.`nim` LEFT JOIN tb_unit c ON b.`idunit`=c.`idunit` LEFT JOIN tb_profil_tambahan d ON a.`nim`=d.`id` WHERE nip='$id' AND a.status='aktif' ORDER BY a.`jenis_sidang` ASC ")->getResult();
    $data_mhs = [];
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
      'data_periode' => $this->db->query("SELECT * FROM tb_periode")->getResult(),
      'id_periode' => $id_periode,
    ];
    return view('Dosen/riwayat_bimbingan', $data);
  }

  public function direct_cetak()
  {
    if (session()->get('ses_id') == '' || session()->get('ses_login') == 'mahasiswa') {
      return redirect()->to('/');
    }
    if (!$this->validate([
      'start' => [
        'rules' => 'required',
        'errors' => [
          'required' => 'Pilih Tanggal Mulai'
        ]
      ],
      'end' => [
        'rules' => 'required',
        'errors' => [
          'required' => 'Pilih Tanggal Akhir'
        ]
      ]
    ])) {
      session()->setFlashdata('message', '<div class="alert alert-danger alert-dismissible fade show mb-0" role="alert">
          <span class="alert-inner--icon"><i class="fe fe-slash"></i></span>
          <span class="alert-inner--text"><strong>Gagal!</strong> ' . $this->validator->listErrors() . '</span>
          <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">×</span>
          </button>
      </div>');
      return redirect()->back()->withInput();
    }
    $idunit = session()->get('ses_idunit');
    $date1 = $this->request->getPost('start');
    $date2 = $this->request->getPost('end');
    $sebagai = $this->request->getPost('sebagai');

    $jenis_file = $this->request->getPost('jenis_file');
    return redirect()->to("riwayat_bimbingan_cetak/$idunit/$date1/$date2/$sebagai");
  }

  public function cetak($idunit, $date1, $date2, $sebagai)
  {
    $link = base_url() . "riwayat_bimbingan/$idunit/$date1/$date2";
    $id = session()->get('ses_id');
    $qr_link = $this->qr->cetakqr($link);
    $tb_unit = $this->db->query("SELECT * FROM tb_unit WHERE idunit='$idunit'")->getResult();
    $data_mhs_bimbingan = $this->db->query("SELECT a.nip,a.nim,a.sebagai,a.status_pengajuan, b.nama as nama_mhs, b.idunit as namaunit  FROM `tb_pengajuan_pembimbing` a LEFT JOIN tb_mahasiswa b ON a.nim = b.nim WHERE a.nip = '" . $id . "'  AND a.status_pengajuan = 'diterima'")->getResult();
    $data_mhs_uji_jika_diganti = $this->db->query("SELECT a.*,b.`nama` AS nama_mhs, b.`jk`, c.`namaunit`, d.* FROM tb_penguji a LEFT JOIN tb_mahasiswa b ON b.`nim`=a.`nim` LEFT JOIN tb_unit c ON b.`idunit`=c.`idunit` LEFT JOIN tb_profil_tambahan d ON a.`nim`=d.`id` WHERE nip='$id' AND a.status='aktif' AND a.jenis_sidang = 'sidang skripsi'  ")->getResult();
    $data_mhs_uji = $this->db->query("SELECT a.*,b.`nama` AS nama_mhs, b.`jk`, c.`namaunit`, d.* FROM tb_penguji a LEFT JOIN tb_mahasiswa b ON b.`nim`=a.`nim` LEFT JOIN tb_unit c ON b.`idunit`=c.`idunit` LEFT JOIN tb_profil_tambahan d ON a.`nim`=d.`id` WHERE nip='$id' AND a.status='aktif'  ORDER BY a.`jenis_sidang` ASC ")->getResult();

    $data_mhs_bimbingan_2 = [];
    $data_mhs = [];
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

    if ($sebagai != 'pembimbing') {
      $data_mhs_bimbingan_2 = $data_mhs;
    }
    $data = [
      'baseurl' => base_url(),
      'title' => 'Riwayat Bimbingan',
      'db' => $this->db,
      'qr_link' => $qr_link,
      'data_mhs_bimbingan' => $data_mhs_bimbingan_2,
      'data_mhs_uji' => $data_mhs,
      'namaunit' => $tb_unit[0]->namaunit,
      'date1' => $date1,
      'date2' => $date2,
      'sebagai' => $sebagai,
      'tb_dosen' => $this->db->query("SELECT DISTINCT a.`nip`,b.* FROM tb_nilai a LEFT JOIN tb_dosen b ON a.`nip`=b.`nip` WHERE idunit='$idunit'")->getResult(),
    ];

    // return view('Cetak/riwayat_bimbingan', $data);

    $dompdf = new Dompdf();
    $filename = date('y-m-d-H-i-s');
    $dompdf->loadHtml(view('Cetak/riwayat_bimbingan', $data));
    $dompdf->setPaper('A4', 'landscape');
    $dompdf->render();
    $dompdf->stream($filename, array('Attachment' => false));
    exit();
  }
}
