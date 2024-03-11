<?php

namespace App\Controllers\Korprodi;

use App\Controllers\BaseController;

use App\Libraries\Access_API; // Import library
use App\Libraries\QRcodelib; // Import library
use CodeIgniter\Database\Query;
use Dompdf\Dompdf;
use Dompdf\Options;

class Nilai extends BaseController
{
    public function __construct()
    {
        $this->api = new Access_API();
        $this->qr = new QRcodelib();
        $this->db = \Config\Database::connect();
    }
    public function index()
    {
        if (session()->get('ses_id') == '' || session()->get('ses_login') != 'korprodi') {
            return redirect()->to('/');
        }

        $data = [
            'title' => 'Daftar Nilai Ujian Skripsi',
            'db' => $this->db,
            'data_mhs' => $this->db->query("SELECT * FROM tb_users WHERE idunit='" . session()->get('ses_idunit') . "' AND role='mahasiswa' ORDER BY id ASC")->getResult(),
            'data_periode' => $this->db->query("SELECT * FROM tb_periode")->getResult(),
            'data_jadwal' => $this->db->query("SELECT * FROM tb_jadwal_sidang WHERE idunit='" . session()->get('ses_idunit') . "' AND jenis_sidang='sidang skripsi'")->getResult(),
        ];
        return view('Korprodi/daftar_nilai', $data);
    }

    public function belum_dinilai()
    {
        if (session()->get('ses_id') == '' || session()->get('ses_login') != 'korprodi') {
            return redirect()->to('/');
        }

        $data = [
            'title' => 'Daftar Dosen yang Belum Menilai',
            'tipe' => 'belum_dinilai',
            'db' => $this->db,
            'data_mhs' => $this->db->query("SELECT * FROM tb_users  WHERE idunit='" . session()->get('ses_idunit') . "' AND role='mahasiswa' ORDER BY id ASC")->getResult(),
            'data_periode' => $this->db->query("SELECT * FROM tb_periode")->getResult(),
            'data_jadwal' => $this->db->query("SELECT * FROM tb_jadwal_sidang WHERE idunit='" . session()->get('ses_idunit') . "' AND jenis_sidang='sidang skripsi'")->getResult(),
        ];
        return view('Korprodi/daftar_informasi_nilai', $data);
    }
    public function sudah_dinilai()
    {
        if (session()->get('ses_id') == '' || session()->get('ses_login') != 'korprodi') {
            return redirect()->to('/');
        }

        $data = [
            'title' => 'Daftar Dosen yang sudah Menilai',
            'tipe' => 'sudah_dinilai',
            'db' => $this->db,
            'data_mhs' => $this->db->query("SELECT * FROM tb_users  WHERE idunit='" . session()->get('ses_idunit') . "' AND role='mahasiswa' ORDER BY id ASC")->getResult(),
            'data_periode' => $this->db->query("SELECT * FROM tb_periode")->getResult(),
            'data_jadwal' => $this->db->query("SELECT * FROM tb_jadwal_sidang WHERE idunit='" . session()->get('ses_idunit') . "' AND jenis_sidang='sidang skripsi'")->getResult(),
        ];
        return view('Korprodi/daftar_informasi_nilai', $data);
    }

    public function export()
    {
        if (session()->get('ses_id') == '' || session()->get('ses_login') != 'korprodi') {
            return redirect()->to('/');
        }
        $idperiode = $this->request->getPost("id_periode");
        $id_jadwal = $this->request->getPost("id_jadwal");
        $jenis_file = $this->request->getPost("jenis_file");
        if ($jenis_file == 'excel') {
            if ($idperiode == '') {
                if ($id_jadwal == '') {
                    $data = $this->db->query("SELECT * FROM tb_users a LEFT JOIN tb_mahasiswa b ON a.`id`=b.`nim` WHERE a.`idunit`='" . session()->get('ses_idunit') . "' AND a.role='mahasiswa' ORDER BY id ASC")->getResult();
                } else {
                    $data = $this->db->query("SELECT * FROM tb_users a LEFT JOIN tb_mahasiswa b ON a.`id`=b.`nim` LEFT JOIN tb_pendaftar_sidang c ON c.`nim`=a.`id` WHERE a.`idunit`='" . session()->get('ses_idunit') . "' AND a.`role`='mahasiswa' AND c.`id_jadwal`='" . $id_jadwal . "' ORDER BY id ASC")->getResult();
                }
            } else {
                if ($id_jadwal == '') {
                    $data = $this->db->query("SELECT * FROM tb_users a LEFT JOIN tb_mahasiswa b ON a.`id`=b.`nim` LEFT JOIN tb_pendaftar_sidang c ON c.`nim`=a.`id` WHERE a.`idunit`='" . session()->get('ses_idunit') . "' AND a.`role`='mahasiswa' AND b.`idperiode`='" . $idperiode . "' ORDER BY id ASC")->getResult();
                } else {
                    $data = $this->db->query("SELECT * FROM tb_users a LEFT JOIN tb_mahasiswa	b ON a.`id`=b.`nim` LEFT JOIN tb_pendaftar_sidang c ON c.`nim`=a.`id` WHERE a.`idunit`='" . session()->get('ses_idunit') . "' AND a.role='mahasiswa' AND b.`idperiode`='$idperiode' AND c.`id_jadwal`='$id_jadwal' ORDER BY id ASC;")->getResult();
                }
            }
            $data = [
                'title' => 'Daftar Nilai Ujian Skripsi',
                'db' => $this->db,
                'data_mhs' => $data
            ];
            return view('Cetak/export_nilai', $data);
        } else {
            if ($idperiode == '') {
                $idperiode = 'semua';
            }
            if ($id_jadwal == '') {
                $id_jadwal = 'semua';
            }
            return redirect()->to("export_nilai_pdf/$idperiode/$id_jadwal");
        }
    }

    public function export_pdf($idperiode = null, $id_jadwal = null)
    {
        $link = base_url() . "export_nilai_pdf/$idperiode/$id_jadwal";
        $qr_link = $this->qr->cetakqr($link);
        $tb_unit = $this->db->query("SELECT * FROM tb_unit WHERE idunit='" . session()->get('ses_idunit') . "'")->getResult();
        if ($idperiode == 'semua') {
            if ($id_jadwal == 'semua') {
                $data = $this->db->query("SELECT * FROM tb_users a LEFT JOIN tb_mahasiswa b ON a.`id`=b.`nim` WHERE a.`idunit`='" . session()->get('ses_idunit') . "' AND a.role='mahasiswa' ORDER BY id ASC")->getResult();
            } else {
                $data = $this->db->query("SELECT * FROM tb_users a LEFT JOIN tb_mahasiswa b ON a.`id`=b.`nim` LEFT JOIN tb_pendaftar_sidang c ON c.`nim`=a.`id` WHERE a.`idunit`='" . session()->get('ses_idunit') . "' AND a.`role`='mahasiswa' AND c.`id_jadwal`='" . $id_jadwal . "' ORDER BY id ASC")->getResult();
            }
        } else {
            if ($id_jadwal == 'semua') {
                $data = $this->db->query("SELECT * FROM tb_users a LEFT JOIN tb_mahasiswa b ON a.`id`=b.`nim` LEFT JOIN tb_pendaftar_sidang c ON c.`nim`=a.`id` WHERE a.`idunit`='" . session()->get('ses_idunit') . "' AND a.`role`='mahasiswa' AND b.`idperiode`='" . $idperiode . "' ORDER BY id ASC")->getResult();
            } else {
                $data = $this->db->query("SELECT * FROM tb_users a LEFT JOIN tb_mahasiswa	b ON a.`id`=b.`nim` LEFT JOIN tb_pendaftar_sidang c ON c.`nim`=a.`id` WHERE a.`idunit`='" . session()->get('ses_idunit') . "' AND a.role='mahasiswa' AND b.`idperiode`='$idperiode' AND c.`id_jadwal`='$id_jadwal' ORDER BY id ASC;")->getResult();
            }
        }
        $data = [
            'title' => 'Daftar Nilai Ujian Skripsi',
            'db' => $this->db,
            'data_mhs' => $data,
            'baseurl' => base_url(),
            'qr_link' => $qr_link,
            'namaunit' => $tb_unit[0]->namaunit
        ];
        $dompdf = new Dompdf();
        $filename = date('y-m-d-H-i-s');
        $dompdf->loadHtml(view('Cetak/export_nilai_pdf', $data));
        $dompdf->setPaper('A4', 'potrait');
        $dompdf->render();
        $dompdf->stream($filename, array('Attachment' => false));
        exit();
    }
    public function export_sudah_dinilai_pdf($tipe = null, $id_jadwal = null)
    {
        $idperiode = 'semua';
        $id_jadwal = 'semua';
        $link = base_url() . "export_nilai_pdf/$tipe/$id_jadwal";
        $qr_link = $this->qr->cetakqr($link);
        $tb_unit = $this->db->query("SELECT * FROM tb_unit WHERE idunit='" . session()->get('ses_idunit') . "'")->getResult();
        if ($idperiode == 'semua') {
            if ($id_jadwal == 'semua') {
                $data = $this->db->query("SELECT * FROM tb_users a LEFT JOIN tb_mahasiswa b ON a.`id`=b.`nim` WHERE a.`idunit`='" . session()->get('ses_idunit') . "' AND a.role='mahasiswa' ORDER BY id ASC")->getResult();
            } else {
                $data = $this->db->query("SELECT * FROM tb_users a LEFT JOIN tb_mahasiswa b ON a.`id`=b.`nim` LEFT JOIN tb_pendaftar_sidang c ON c.`nim`=a.`id` WHERE a.`idunit`='" . session()->get('ses_idunit') . "' AND a.`role`='mahasiswa' AND c.`id_jadwal`='" . $id_jadwal . "' ORDER BY id ASC")->getResult();
            }
        } else {
            if ($id_jadwal == 'semua') {
                $data = $this->db->query("SELECT * FROM tb_users a LEFT JOIN tb_mahasiswa b ON a.`id`=b.`nim` LEFT JOIN tb_pendaftar_sidang c ON c.`nim`=a.`id` WHERE a.`idunit`='" . session()->get('ses_idunit') . "' AND a.`role`='mahasiswa' AND b.`idperiode`='" . $idperiode . "' ORDER BY id ASC")->getResult();
            } else {
                $data = $this->db->query("SELECT * FROM tb_users a LEFT JOIN tb_mahasiswa	b ON a.`id`=b.`nim` LEFT JOIN tb_pendaftar_sidang c ON c.`nim`=a.`id` WHERE a.`idunit`='" . session()->get('ses_idunit') . "' AND a.role='mahasiswa' AND b.`idperiode`='$idperiode' AND c.`id_jadwal`='$id_jadwal' ORDER BY id ASC;")->getResult();
            }
        }
        $data = [
            'tipe' => $tipe,
            'title' => 'Daftar Nilai Ujian Skripsi',
            'db' => $this->db,
            'data_mhs' => $data,
            'baseurl' => base_url(),
            'qr_link' => $qr_link,
            'namaunit' => $tb_unit[0]->namaunit
        ];
        $dompdf = new Dompdf();
        $filename = date('y-m-d-H-i-s');
        $dompdf->loadHtml(view('Cetak/export_sudah_nilai_pdf', $data));
        $dompdf->setPaper('A4', 'potrait');
        $dompdf->render();
        $dompdf->stream($filename, array('Attachment' => false));
        exit();
    }
}
