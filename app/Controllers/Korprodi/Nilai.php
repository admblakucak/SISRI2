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
    protected $api;
    protected $qr;
    protected $db;

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


    public function nilai_default()
    {
        if (session()->get('ses_id') == '' || session()->get('ses_login') != 'korprodi') {
            return redirect()->to('/');
        }

        $data = [
            'title' => 'Daftar Nilai Default Skripsi',
            'db' => $this->db,
            'data_mhs' => $this->db->query("SELECT * FROM tb_users WHERE idunit='" . session()->get('ses_idunit') . "' AND role='mahasiswa' ORDER BY id ASC")->getResult(),
            'data_periode' => $this->db->query("SELECT * FROM tb_periode")->getResult(),
            'data_jadwal' => $this->db->query("SELECT * FROM tb_jadwal_sidang WHERE idunit='" . session()->get('ses_idunit') . "' AND jenis_sidang='sidang skripsi'")->getResult(),
        ];
        return view('Korprodi/nilai_defa', $data);
    }

    private function update_tb_nilai($nim, $sebagai, $nilai_bimbingan, $nilai_ujian)
    {
        // $this->db->query("INSERT INTO tb_nilai (nim,nip,sebagai,nilai_bimbingan,nilai_ujian,pesan) VALUES ('$nim','" . session()->get('ses_id') . "','$sebagai','$nilai_bimbingan','$nilai_ujian', 'update by koorprodi')");
        $this->db->query("UPDATE `tb_nilai` SET `nilai_ujian` = '$nilai_ujian', `nilai_bimbingan` = '$nilai_bimbingan' WHERE sebagai='$sebagai' AND nim='$nim' ");
    }

    public function update_nilai()
    {
        if (session()->get('ses_id') == '' || session()->get('ses_login') == 'mahasiswa') {
            return redirect()->to('/');
        }
        $nim = $this->request->getPost('nim');
        $message = '';

        // Pembimbing 1
        $nilai_bimbingan_1 = $this->request->getPost('nilai_bimbingan_1');
        $nilai_ujian_1 = $this->request->getPost('nilai_ujian_1');

        // Pembimbing 2
        $nilai_bimbingan_2 = $this->request->getPost('nilai_bimbingan_2');
        $nilai_ujian_2 = $this->request->getPost('nilai_ujian_2');

        // Penguji 1
        $nilai_penguji_1 = $this->request->getPost('nilai_penguji_1');

        // Penguji 2
        $nilai_penguji_2 = $this->request->getPost('nilai_penguji_2');

        // Penguji 3
        $nilai_penguji_3 = $this->request->getPost('nilai_penguji_3');
        $nilai = $this->db->query("SELECT * FROM `tb_nilai` WHERE nim = '$nim' ORDER BY `tb_nilai`.`sebagai` ASC")->getResult();
        $sebagai = $this->request->getPost('sebagai');

        // Update Nilai Pembimbing 1
        if ($nilai[0]->nilai_bimbingan != $nilai_bimbingan_1 || $nilai[0]->nilai_ujian != $nilai_ujian_1) {
            $this->update_tb_nilai($nim, 'pembimbing 1', $nilai_bimbingan_1, $nilai_ujian_1);
            $message = $message . '<li>Nilai Pembimbing 1 Berhasil di Update</li>';
        }

        // Update Nilai Pembimbing 2
        if ($nilai[1]->nilai_bimbingan != $nilai_bimbingan_2 || $nilai[1]->nilai_ujian != $nilai_ujian_2) {
            $this->update_tb_nilai($nim, 'pembimbing 2', $nilai_bimbingan_2, $nilai_ujian_2);
            $message = $message . '<li>Nilai Pembimbing 2 Berhasil di Update</li>';
        }

        // Update Nilai Penguji 1
        if ($nilai[2]->nilai_ujian != $nilai_penguji_1) {
            $this->update_tb_nilai($nim, 'penguji 1', null, $nilai_penguji_1);
            $message = $message . '<li>Nilai Penguji 1 Berhasil di Update</li>';
        }

        // Update Nilai Penguji 2
        if ($nilai[3]->nilai_ujian != $nilai_penguji_2) {
            $this->update_tb_nilai($nim, 'penguji 2', null, $nilai_penguji_2);
            $message = $message . '<li>Nilai Penguji 2 Berhasil di Update</li>';
        }

        // Update Nilai Penguji 3
        if ($nilai[4]->nilai_ujian != $nilai_penguji_3) {
            $this->update_tb_nilai($nim, 'penguji 3', null, $nilai_penguji_3);
            $message = $message . '<li>Nilai Penguji 3 Berhasil di Update</li>';
        }

        session()->setFlashdata('message', $message);

        return redirect()->to('/daftar_nilai_default');
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
        $tipe = $this->request->getPost("tipe");
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

            $new_data = [];
            foreach ($data as $key) {
                $sidang = $this->db->query("SELECT * FROM tb_pendaftar_sidang a LEFT JOIN tb_jadwal_sidang b ON a.`id_jadwal`=b.`id_jadwal` WHERE a.`nim`='" . $key->id . "' AND b.`jenis_sidang`='sidang skripsi' ORDER BY create_at DESC LIMIT 1")->getResult();
                if (!empty($sidang)) {
                    array_push($new_data, $key);
                }
            }

            if (empty($tipe)) {
                $tipe = 'semua';
            }
            $data = [
                'title' => 'Daftar Nilai Ujian Skripsi',
                'db' => $this->db,
                'data_mhs' => $new_data,
                'tipe' => $tipe,
            ];
            return view('Cetak/export_nilai', $data);
        } else {
            if ($idperiode == '') {
                $idperiode = 'semua';
            }
            if ($id_jadwal == '') {
                $id_jadwal = 'semua';
            }
            if (!empty($tipe)) {
                return redirect()->to("export_sudah_nilai_pdf/$idperiode/$id_jadwal/$tipe");
            } else {
                return redirect()->to("export_nilai_pdf/$idperiode/$id_jadwal");
            }
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

        // return view('Cetak/export_nilai_pdf', $data);
        $dompdf = new Dompdf();
        $filename = date('y-m-d-H-i-s');
        $dompdf->loadHtml(view('Cetak/export_nilai_pdf', $data));
        $dompdf->setPaper('A4', 'potrait');
        $dompdf->render();
        $dompdf->stream($filename, array('Attachment' => false));
        exit();
    }
    public function export_sudah_dinilai_pdf($idperiode = null, $id_jadwal = null, $tipe = null,)
    {
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
