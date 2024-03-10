<?php

namespace App\Controllers\Mahasiswa\Skripsi;

use App\Controllers\BaseController;

use App\Libraries\Access_API; // Import library

class Revisi extends BaseController
{
    public function __construct()
    {
        $this->api = new Access_API();
        $this->db = \Config\Database::connect();
    }
    public function index($how)
    {
        if (session()->get('ses_id') == '' || session()->get('ses_login') != 'mahasiswa') {
            return redirect()->to('/');
        }
        $id = session()->get('ses_id');
        // $dosen_penguji = $this->db->query("SELECT * FROM tb_penguji a LEFT JOIN tb_dosen b ON a.`nip`=b.`nip` LEFT JOIN tb_profil_tambahan c ON a.`nip`=c.`id` WHERE a.nim='" . $id . "' AND a.status='aktif'")->getResult();
        $penguji1  = $this->db->query("SELECT * FROM tb_penguji a LEFT JOIN tb_dosen b ON a.`nip`=b.`nip` LEFT JOIN tb_profil_tambahan c ON a.`nip`=c.`id` WHERE a.nim='" . $id . "' AND a.status='aktif' AND a.jenis_sidang = 'sidang skripsi' AND a.sebagai=1")->getResult();
        if (empty($penguji1)) {
            $penguji1  = $this->db->query("SELECT * FROM tb_penguji a LEFT JOIN tb_dosen b ON a.`nip`=b.`nip` LEFT JOIN tb_profil_tambahan c ON a.`nip`=c.`id` WHERE a.nim='" . $id . "' AND a.status='aktif' AND a.jenis_sidang = '' AND a.sebagai=1")->getResult();
        }
        $penguji2  = $this->db->query("SELECT * FROM tb_penguji a LEFT JOIN tb_dosen b ON a.`nip`=b.`nip` LEFT JOIN tb_profil_tambahan c ON a.`nip`=c.`id` WHERE a.nim='" . $id . "' AND a.status='aktif' AND a.jenis_sidang = 'sidang skripsi' AND a.sebagai=2")->getResult();
        if (empty($penguji2)) {
            $penguji2  = $this->db->query("SELECT * FROM tb_penguji a LEFT JOIN tb_dosen b ON a.`nip`=b.`nip` LEFT JOIN tb_profil_tambahan c ON a.`nip`=c.`id` WHERE a.nim='" . $id . "' AND a.status='aktif' AND a.jenis_sidang = '' AND a.sebagai=2")->getResult();
        }
        $penguji3  = $this->db->query("SELECT * FROM tb_penguji a LEFT JOIN tb_dosen b ON a.`nip`=b.`nip` LEFT JOIN tb_profil_tambahan c ON a.`nip`=c.`id` WHERE a.nim='" . $id . "' AND a.status='aktif' AND a.jenis_sidang = 'sidang skripsi' AND a.sebagai=3")->getResult();
        if (empty($penguji3)) {
            $penguji3  = $this->db->query("SELECT * FROM tb_penguji a LEFT JOIN tb_dosen b ON a.`nip`=b.`nip` LEFT JOIN tb_profil_tambahan c ON a.`nip`=c.`id` WHERE a.nim='" . $id . "' AND a.status='aktif' AND a.jenis_sidang = '' AND a.sebagai=3")->getResult();
        }

        $dosen_penguji = [];
        array_push($dosen_penguji, $penguji1[0], $penguji2[0], $penguji3[0]);

        // dd($penguji1);
        $data = [
            'title' => 'Bimbingan Revisi Skripsi',
            'how' => $how,
            'db' => $this->db,
            'dosen_pembimbing' => $this->db->query("SELECT * FROM tb_pengajuan_pembimbing a LEFT JOIN tb_dosen b ON a.`nip`=b.`nip` LEFT JOIN tb_profil_tambahan c ON a.`nip`=c.`id` WHERE a.nim='" . $id . "' AND a.status_pengajuan='diterima'")->getResult(),
            'dosen_penguji' => $dosen_penguji,
            'progress_bimbingan' => $this->db->query("SELECT * FROM tb_bimbingan a LEFT JOIN tb_profil_tambahan b ON a.`from`=b.`id` WHERE (a.`from` = '" . $id . "' OR a.`to` = '" . $id . "') AND (a.`from` = '" . $how . "' OR a.`to` = '" . $how . "') AND kategori_bimbingan=4 ORDER BY create_at ASC")->getResult(),
        ];
        $this->db->query("UPDATE tb_bimbingan SET status_baca='dibaca' WHERE `from`=$how AND status_baca='belum dibaca' AND kategori_bimbingan=4");
        $this->db->query("INSERT INTO tb_log (user,`action`,`log`,date_time) VALUES ('" . session()->get('ses_id') . "','Bimbingan Dibaca','Bimbingan $how dibaca ',now())");
        return view('Mahasiswa/Skripsi/bimbingan_revisi_skripsi', $data);
    }
    public function reload_m($how)
    {
        if (session()->get('ses_id') == '' || session()->get('ses_login') != 'mahasiswa') {
            return redirect()->to('/');
        }
        $id = session()->get('ses_id');
        $data = [
            'title' => 'Bimbingan Revisi Skripsi',
            'how' => $how,
            'db' => $this->db,
            'dosen_pembimbing' => $this->db->query("SELECT * FROM tb_pengajuan_pembimbing a LEFT JOIN tb_dosen b ON a.`nip`=b.`nip` LEFT JOIN tb_profil_tambahan c ON a.`nip`=c.`id` WHERE a.nim='" . $id . "' AND a.status_pengajuan='diterima'")->getResult(),
            'dosen_penguji' => $this->db->query("SELECT * FROM tb_penguji a LEFT JOIN tb_dosen b ON a.`nip`=b.`nip` LEFT JOIN tb_profil_tambahan c ON a.`nip`=c.`id` WHERE a.nim='" . $id . "' AND a.status='aktif'")->getResult(),
            'progress_bimbingan' => $this->db->query("SELECT * FROM tb_bimbingan a LEFT JOIN tb_profil_tambahan b ON a.`from`=b.`id` WHERE (a.`from` = '" . $id . "' OR a.`to` = '" . $id . "') AND (a.`from` = '" . $how . "' OR a.`to` = '" . $how . "') AND kategori_bimbingan=4 ORDER BY create_at ASC")->getResult(),
        ];
        $this->db->query("UPDATE tb_bimbingan SET status_baca='dibaca' WHERE `from`=$how AND status_baca='belum dibaca' AND kategori_bimbingan=4");
        $this->db->query("INSERT INTO tb_log (user,`action`,`log`,date_time) VALUES ('" . session()->get('ses_id') . "','Bimbingan Dibaca','Bimbingan $how dibaca ',now())");
        return view('Mahasiswa/Skripsi/Pesan/bimbingan_revisi_skripsi', $data);
    }
    public function tambah()
    {
        if (session()->get('ses_id') == '' || session()->get('ses_login') != 'mahasiswa') {
            return redirect()->to('/');
        }
        $pokok_bimbingan = $this->request->getPost('pokok_bimbingan');
        $keterangan = $this->request->getPost('keterangan');
        $berkas = $this->request->getFile('berkas');
        $pembimbing = $this->request->getPost('pembimbing');
        $name = $berkas->getRandomName();

        if ($berkas->getName() != '') {

            $berkas_ext = $berkas->guessExtension();
            $ext_s = ['png', 'jpg', 'zip', 'pdf', 'doc', 'csv', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'jpeg'];
            if (!in_array($berkas_ext, $ext_s)) {
                session()->setFlashdata('message', '<div class="alert alert-danger alert-dismissible fade show mb-0" role="alert">
                    <span class="alert-inner--icon"><i class="fe fe-slash"></i></span>
                    <span class="alert-inner--text"><strong>Gagal!</strong> File extention harus .pdf, .doc, .docx, .ppt, .pptx, .xls, .xlsx, .csv, .jpg, .jpeg, .png, .zip</span>
                    <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>');
                return redirect()->back()->withInput();
            }
            if ($berkas->move(WRITEPATH . '../public/berkas/', $name)) {
                $this->db->query("INSERT INTO tb_bimbingan (`from`,`to`,status_baca,keterangan,berkas,pokok_bimbingan,create_at,kategori_bimbingan) VALUES('" . session()->get('ses_id') . "','$pembimbing','belum dibaca','$keterangan','$name','$pokok_bimbingan',now(),4)");
                $this->db->query("INSERT INTO tb_log (user,`action`,`log`,date_time) VALUES ('" . session()->get('ses_id') . "','Bimbingan','Bimbingan kepada pembimbing $pembimbing',now())");
            }
        } else {
            $this->db->query("INSERT INTO tb_bimbingan (`from`,`to`,status_baca,keterangan,pokok_bimbingan,create_at,kategori_bimbingan) VALUES('" . session()->get('ses_id') . "','$pembimbing','belum dibaca','$keterangan','$pokok_bimbingan',now(),4)");
            $this->db->query("INSERT INTO tb_log (user,`action`,`log`,date_time) VALUES ('" . session()->get('ses_id') . "','Bimbingan','Bimbingan kepada pembimbing $pembimbing',now())");
        }
        return redirect()->to("/bimbingan_revisi_skripsi/$pembimbing");
    }
    public function hapus()
    {
        $id_bimbingan = $this->request->getPost('id_bimbingan');
        $nip = $this->request->getPost('nip');
        $this->db->query("DELETE FROM tb_bimbingan WHERE id_bimbingan='$id_bimbingan'");
        $this->db->query("INSERT INTO tb_log (user,`action`,`log`,date_time) VALUES ('" . session()->get('ses_id') . "','Bimbingan Dihapus','id bimbingan $id_bimbingan dihapus ',now())");
        return redirect()->to("/bimbingan_revisi_skripsi/$nip");
    }
    public function download_berkas()
    {
        $id_bimbingan = $this->request->getPost('id_bimbingan');
        $data = $this->db->query("SELECT * FROM tb_bimbingan where id_bimbingan=$id_bimbingan")->getResult()[0]->berkas;
        return $this->response->download(FCPATH . 'berkas/' . $data, null);
    }
}
