<?php

namespace App\Controllers\Dosen;

use App\Controllers\BaseController;

use App\Libraries\Access_API; // Import library

class Beranda extends BaseController
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
        $data_mhs_bimbingan = $this->db->query("SELECT b.nama, a.nim, a.sebagai, a.pesan FROM `tb_pengajuan_pembimbing` a LEFT JOIN tb_mahasiswa b on a.nim=b.nim WHERE nip = '" . session()->get('ses_id') . "' AND status_pengajuan ='diterima';")->getResult();


        $data_mhs = [];
        $id = session()->get('ses_id');
        // dd($id);
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
                'jenis_sidang' => $key->jenis_sidang,
                'id_pendaftar' => $key->id_pendaftar
            ];
            array_push($data_mhs, $data);
        }


        // $data_mhs_uji = $this->db->query("SELECT * FROM `tb_penguji` WHERE nip ='" . session()->get('ses_id') . "' AND status='aktif'")->getResult();

        if (session()->get('ses_login') == 'dosen') {
            $title = 'Beranda Dosen';
            $data = [
                'title' => $title,
                'db' => $this->db,
                'data_mhs_bimbingan' => $data_mhs_bimbingan,
                'data_mhs_uji' => $data_mhs
            ];
        } elseif (session()->get('ses_login') == 'korprodi') {
            $title = 'Beranda Koorprodi';
            $data = [
                'title' => $title,
                'data_dosen' => $this->db->query("SELECT a.* FROM tb_dosen a WHERE a.`idunit`='" . session()->get('ses_idunit') . "'")->getResult(),
                'db' => $this->db,
                'idunit' => session()->get('ses_idunit'),
                'data_periode' => $this->db->query("SELECT * FROM tb_periode WHERE idperiode IN (SELECT DISTINCT idperiode FROM tb_mahasiswa WHERE idunit='" . session()->get('ses_idunit') . "')")->getResult(),
                'data_mhs_bimbingan' => $data_mhs_bimbingan,
                'data_mhs_uji' => $data_mhs,
                'data_jadwal' => $this->db->query("SELECT * FROM tb_jadwal_sidang WHERE idunit='" . session()->get('ses_idunit') . "' AND expire > NOW()")->getResult(),
            ];
        }

        return view('Dosen/beranda_dosen', $data);
        // return view('kosong', $data);
    }
}
