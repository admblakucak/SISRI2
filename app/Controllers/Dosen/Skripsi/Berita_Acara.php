<?php

namespace App\Controllers\Dosen\Skripsi;

use App\Controllers\BaseController;

use App\Libraries\Access_API; // Import library

class Berita_Acara extends BaseController
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
        $data_mhs_bimbingan = $this->db->query("SELECT * FROM tb_pengajuan_pembimbing a LEFT JOIN tb_mahasiswa b ON a.`nim`=b.`nim` WHERE nip='" . session()->get('ses_id') . "' AND status_pengajuan='diterima'")->getResult();
        $data_mhs_uji = $this->db->query("SELECT * FROM tb_penguji a LEFT JOIN tb_mahasiswa b ON a.`nim`=b.`nim` WHERE STATUS='aktif' AND nip='" . session()->get('ses_id') . "'")->getResult();
        $data = [
            'title' => 'Berita Acara Sidang Skripsi',
            'db' => $this->db,
            'data_mhs_bimbingan' => $data_mhs_bimbingan,
            'data_mhs_uji' => $data_mhs_uji
        ];
        return view('Dosen/Skripsi/berita_acara_skripsi', $data);
    }
    public function ttd()
    {
        if (session()->get('ses_id') == '' || session()->get('ses_login') == 'mahasiswa') {
            return redirect()->to('/');
        }
        $nim = $this->request->getPost('nim');
        $id_pendaftar = $this->request->getPost('id_pendaftar');
        $sebagai = $this->request->getPost('sebagai');
        $d_berita_acara = $this->db->query("SELECT * FROM tb_berita_acara WHERE nim='" . $nim . "' AND nip='" . session()->get('ses_id') . "' AND sebagai='" . $sebagai . "' AND status='ditandatangani' AND jenis_sidang='skripsi' AND id_pendaftar='$id_pendaftar'")->getResult();
        if ($sebagai == 'pembimbing 1') {
            $status = $this->request->getPost('status');
            $id_pendaftar = $this->request->getPost('id_pendaftar');
            if ($status == 3) {
                $this->db->query("UPDATE tb_penguji SET `status`='nonaktif' WHERE nim='$nim' AND jenis_sidang='sidang skripsi'");
                $this->db->query("DELETE FROM tb_nilai WHERE nim='$nim'");
                // $cek_pendaftar_sidang = $this->db->query("SELECT * FROM tb_pendaftar_sidang WHERE nim='" . session()->get('ses_id') . "'")->getResult();
            }
            $this->db->query("UPDATE tb_pendaftar_sidang SET hasil_sidang='$status' WHERE id_pendaftar='$id_pendaftar'");
            if (empty($d_berita_acara)) {
                $this->db->query("INSERT INTO tb_berita_acara (nim,nip,sebagai,status,jenis_sidang,id_pendaftar) VALUES ('$nim','" . session()->get('ses_id') . "','$sebagai','ditandatangani','skripsi','$id_pendaftar')");
                $this->db->query("INSERT INTO tb_nilai (nim,nip,sebagai,create_at) VALUES ('$nim','" . session()->get('ses_id') . "','$sebagai',now())");
            } else {
                $this->db->query("UPDATE tb_berita_acara SET status= 'ditandatangani' WHERE nim='" . $nim . "' AND nip='" . session()->get('ses_id') . "' AND sebagai='" . $sebagai . "' AND jenis_sidang='skripsi' AND id_pendaftar='$id_pendaftar'");
            }
        } else {
            if (empty($d_berita_acara)) {
                $this->db->query("INSERT INTO tb_berita_acara (nim,nip,sebagai,status,jenis_sidang,id_pendaftar) VALUES ('$nim','" . session()->get('ses_id') . "','$sebagai','ditandatangani','skripsi','$id_pendaftar')");
                $this->db->query("INSERT INTO tb_nilai (nim,nip,sebagai,create_at) VALUES ('$nim','" . session()->get('ses_id') . "','$sebagai',now())");
            } else {
                $this->db->query("UPDATE tb_berita_acara SET status= 'ditandatangani' WHERE nim='" . $nim . "' AND nip='" . session()->get('ses_id') . "' AND sebagai='" . $sebagai . "' AND jenis_sidang='skripsi' AND id_pendaftar='$id_pendaftar'");
            }
        }
        return redirect()->to('/berita_acara_skripsi');
    }
}
