<?php

namespace App\Controllers\Dosen;

use App\Controllers\BaseController;

use App\Libraries\Access_API; // Import library

class Nilai extends BaseController
{
    protected $api;
    protected $db;

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
        $data_mhs_bimbingan = $this->db->query("SELECT a.*,b.`nama` AS nama_mhs, b.`jk`, c.`namaunit`, d.* FROM tb_pengajuan_pembimbing a LEFT JOIN tb_mahasiswa b ON b.`nim`=a.`nim` LEFT JOIN tb_unit c ON b.`idunit`=c.`idunit` LEFT JOIN tb_profil_tambahan d ON a.`nim`=d.`id` WHERE nip='$id' AND status_pengajuan='diterima'")->getResult();
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
                'sebagai' => $key->sebagai,
            ];
            array_push($data_mhs, $data);
        }
        $data = [
            'title' => 'Nilai Bimbingan',
            'db' => $this->db,
            'data_mhs_bimbingan' => $data_mhs,
        ];
        return view('Dosen/input_nilai_bimbinga', $data);
    }

    public static function udiffCompare($a, $b)
    {
        return $a['ITEM'] - $b['ITEM'];
    }

    public function nilai_skripsi()
    {
        if (session()->get('ses_id') == '' || session()->get('ses_login') == 'mahasiswa') {
            return redirect()->to('/');
        }
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


        foreach ($mhs_uji_baru as $key) {
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
                'sebagai' => $key->sebagai,
            ];
            array_push($data_mhs, $data);
        }
        $data = [
            'title' => 'Nilai Ujian Skripsi',
            'db' => $this->db,
            'data_mhs_uji' => $data_mhs,
        ];
        return view('Dosen/input_nilai_skripsi', $data);
    }
    public function save_nilai_bimbingan()
    {
        if (session()->get('ses_id') == '' || session()->get('ses_login') == 'mahasiswa') {
            return redirect()->to('/');
        }
        $nim = $this->request->getPost('nim');
        $nilai = $this->request->getPost('nilai_bimbingan');
        $nilai_ujian = $this->request->getPost('nilai_ujian');
        $sebagai = $this->request->getPost('sebagai');
        $d_nilai = $this->db->query("SELECT * FROM tb_nilai WHERE nim='" . $nim . "' AND nip='" . session()->get('ses_id') . "' AND sebagai='" . $sebagai . "'")->getResult();
        if (empty($d_nilai)) {
            $this->db->query("INSERT INTO tb_nilai (nim,nip,sebagai,nilai_bimbingan,nilai_ujian) VALUES ('$nim','" . session()->get('ses_id') . "','$sebagai','$nilai','$nilai_ujian')");
            $jumlah_pembimbing_p1 = $this->db->query("SELECT * FROM tb_jumlah_pembimbing WHERE nip='" . session()->get('ses_id') . "' AND sebagai='$sebagai'")->getResult();
            $jumlah = $jumlah_pembimbing_p1[0]->jumlah - 1;
            $this->db->query("UPDATE tb_jumlah_pembimbing SET jumlah='$jumlah' WHERE nip='" . session()->get('ses_id') . "' AND sebagai='$sebagai'");
        } else {
            $this->db->query("UPDATE tb_nilai SET nilai_bimbingan = '$nilai',nilai_ujian = '$nilai_ujian' WHERE nim='$nim' AND nip='" . session()->get('ses_id') . "' AND sebagai='$sebagai'");
        }
        return redirect()->to('/input_nilai_bimbingan');
    }
    public function save_nilai_skripsi()
    {
        if (session()->get('ses_id') == '' || session()->get('ses_login') == 'mahasiswa') {
            return redirect()->to('/');
        }
        $nim = $this->request->getPost('ni');
        $nilai = $this->request->getPost('nilai_ujian');
        $sebagai = $this->request->getPost('sebagai');
        $d_nilai = $this->db->query("SELECT * FROM tb_nilai WHERE nim='" . $nim . "' AND nip='" . session()->get('ses_id') . "' AND sebagai='" . $sebagai . "'")->getResult();
        if (empty($d_nilai)) {
            $this->db->query("INSERT INTO tb_nilai (nim,nip,sebagai,nilai_ujian) VALUES ('$nim','" . session()->get('ses_id') . "','$sebagai','$nilai')");
        } else {
            $this->db->query("UPDATE tb_nilai SET nilai_ujian = '$nilai' WHERE nim='$nim' AND nip='" . session()->get('ses_id') . "' AND sebagai='$sebagai'");
        }
        return redirect()->to('/input_nilai_skripsi');
    }
}
