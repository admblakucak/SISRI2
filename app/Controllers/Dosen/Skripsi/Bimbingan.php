<?php

namespace App\Controllers\Dosen\Skripsi;

use App\Controllers\BaseController;

use App\Libraries\Access_API; // Import library

class Bimbingan extends BaseController
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
        $id = session()->get('ses_id');
        $data_mhs = [];
        $data_mhs_bimbingan = $this->db->query("SELECT a.*,b.`nama` AS nama_mhs, b.`jk`, c.`namaunit`, d.* FROM tb_pengajuan_pembimbing a LEFT JOIN tb_mahasiswa b ON b.`nim`=a.`nim` LEFT JOIN tb_unit c ON b.`idunit`=c.`idunit` LEFT JOIN tb_profil_tambahan d ON a.`nim`=d.`id` WHERE nip='$id' AND status_pengajuan='diterima'")->getResult();
        foreach ($data_mhs_bimbingan as $key) {
            if ($key->image != NULL) {
                $image = $key->image;
            } else {
                $image = 'Profile_Default.png';
            }
            $sum_pemberitahuan = $this->db->query("SELECT `from`,COUNT(*) AS sum_pemberitahuan FROM tb_bimbingan WHERE status_baca='belum dibaca' AND `to`=" . session()->get('ses_id') . " AND `from`=" . $key->nim . " AND kategori_bimbingan=3 GROUP BY `from`")->getResult();
            if ($sum_pemberitahuan != NULL) {
                $sum_pemberitahuan = $sum_pemberitahuan[0]->sum_pemberitahuan;
            } else {
                $sum_pemberitahuan = 0;
            }
            $data = [
                'nim' => $key->nim,
                'nama_mhs' => $key->nama_mhs,
                'jk' => $key->jk,
                'namaunit' => $key->namaunit,
                'image' => $image,
                'sum_pemberitahuan' => $sum_pemberitahuan,
                'pesan' => $key->pesan
            ];
            array_push($data_mhs, $data);
        }
        $data_baru = array_filter($data_mhs, function ($item) {
            return $item['sum_pemberitahuan'] != '0';
        });
        $data_mhs = array_filter($data_mhs, function ($item) {
            return $item['sum_pemberitahuan'] == '0';
        });
        $data = [
            'title' => 'Data Mahasiswa Bimbingan Skripsi',
            'db' => $this->db,
            'data_mhs_bimbingan_baru' => $data_baru,
            'data_mhs_bimbingan' => $data_mhs,
        ];
        return view('Dosen/Skripsi/data_bimbingan_skripsi', $data);
    }
    public function bimbingan_skripsi_dosen($nim)
    {
        if (session()->get('ses_id') == '' || session()->get('ses_login') == 'mahasiswa') {
            return redirect()->to('/');
        }

        $id = $nim;
        $pembimbing = $this->db->query("SELECT * FROM tb_pengajuan_pembimbing where nip='" . session()->get('ses_id') . "' AND nim='$nim'")->getResult();
        $penguji = $this->db->query("SELECT * FROM tb_pengajuan_pembimbing where nip='" . session()->get('ses_id') . "' AND nim='$nim'")->getResult();
        if ($pembimbing != null) {
            $sebagai = 'Pembimbing ' . $pembimbing[0]->sebagai;
        } else {
            $sebagai = 'Pembimbing ' . $penguji[0]->sebagai;
        }
        $data = [
            'title' => 'Bimbingan Skripsi',
            'db' => $this->db,
            'nim' => $id,
            'sebagai' => $sebagai,
            'data_mhs' => $this->db->query("SELECT * FROM tb_mahasiswa a LEFT JOIN tb_profil_tambahan b ON b.`id` = a.`nim` WHERE a.`nim` = $id")->getResult()[0],
            'progress_bimbingan' => $this->db->query("SELECT * FROM tb_bimbingan a LEFT JOIN tb_profil_tambahan b ON a.`from`=b.`id` WHERE (a.`from` = '" . $id . "' OR a.`to` = '" . $id . "') AND (a.`from` = '" . session()->get('ses_id') . "' OR a.`to` = '" . session()->get('ses_id') . "') AND kategori_bimbingan=3 ORDER BY create_at ASC")->getResult(),
        ];
        $this->db->query("UPDATE tb_bimbingan SET status_baca='dibaca' WHERE `from`=$nim AND status_baca='belum dibaca' AND kategori_bimbingan=3");
        $this->db->query("INSERT INTO tb_log (user,`action`,`log`,date_time) VALUES ('" . session()->get('ses_id') . "','Bimbingan Dibaca','Bimbingan $nim dibaca ',now())");
        return view('Dosen/Skripsi/bimbingan_skripsi', $data);
    }
    public function reload_m_dosen($nim)
    {
        if (session()->get('ses_id') == '' || session()->get('ses_login') == 'mahasiswa') {
            return redirect()->to('/');
        }

        $id = $nim;
        $pembimbing = $this->db->query("SELECT * FROM tb_pengajuan_pembimbing where nip='" . session()->get('ses_id') . "' AND nim='$nim'")->getResult();
        $penguji = $this->db->query("SELECT * FROM tb_pengajuan_pembimbing where nip='" . session()->get('ses_id') . "' AND nim='$nim'")->getResult();
        if ($pembimbing != null) {
            $sebagai = 'Pembimbing ' . $pembimbing[0]->sebagai;
        } else {
            $sebagai = 'Pembimbing ' . $penguji[0]->sebagai;
        }
        $data = [
            'title' => 'Bimbingan Skripsi',
            'db' => $this->db,
            'nim' => $id,
            'sebagai' => $sebagai,
            'data_mhs' => $this->db->query("SELECT * FROM tb_mahasiswa a LEFT JOIN tb_profil_tambahan b ON b.`id` = a.`nim` WHERE a.`nim` = $id")->getResult()[0],
            'progress_bimbingan' => $this->db->query("SELECT * FROM tb_bimbingan a LEFT JOIN tb_profil_tambahan b ON a.`from`=b.`id` WHERE (a.`from` = '" . $id . "' OR a.`to` = '" . $id . "') AND (a.`from` = '" . session()->get('ses_id') . "' OR a.`to` = '" . session()->get('ses_id') . "') AND kategori_bimbingan=3 ORDER BY create_at ASC")->getResult(),
        ];
        $this->db->query("UPDATE tb_bimbingan SET status_baca='dibaca' WHERE `from`=$nim AND status_baca='belum dibaca' AND kategori_bimbingan=3");
        $this->db->query("INSERT INTO tb_log (user,`action`,`log`,date_time) VALUES ('" . session()->get('ses_id') . "','Bimbingan Dibaca','Bimbingan $nim dibaca ',now())");
        return view('Dosen/Skripsi/Pesan/bimbingan_skripsi', $data);
    }
    public function tambah()
    {
        if (session()->get('ses_id') == '' || session()->get('ses_login') == 'mahasiswa') {
            return redirect()->to('/');
        }
        $nim = $this->request->getPost('nim');
        $id_bimbingan = $this->request->getPost('id_bimbingan');
        $pokok_bimbingan = $this->request->getPost('pokok_bimbingan');
        $keterangan = $this->request->getPost('keterangan');
        $berkas = $this->request->getFile('berkas');
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
                $this->db->query("INSERT INTO tb_bimbingan (`from`,`to`,status_baca,keterangan,berkas,pokok_bimbingan,create_at,parent_id_bimbingan,kategori_bimbingan) VALUES('" . session()->get('ses_id') . "','$nim','belum dibaca','$keterangan','$name','$pokok_bimbingan',now(),'$id_bimbingan',3)");
                session()->setFlashdata("message_bimbingan", '<div class="alert alert-success alert-dismissible fade show" role="alert">
            <span class="alert-inner--icon"><i class="fe fe-thumbs-up"></i></span>
            <span class="alert-inner--text"><strong>Sukses!</strong> kirim revisi.</span>
            <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>');
                $this->db->query("INSERT INTO tb_log (user,`action`,`log`,date_time) VALUES ('" . session()->get('ses_id') . "','Bimbingan','Revisi bimbingan $id_bimbingan',now())");
            } else {
                session()->setFlashdata("message_bimbingan", '<div class="alert alert-danger alert-dismissible fade show mb-0" role="alert">
            <span class="alert-inner--icon"><i class="fe fe-slash"></i></span>
            <span class="alert-inner--text"><strong>Gagal!</strong> kirim revisi.</span>
            <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>');
            }
        } else {
            $this->db->query("INSERT INTO tb_bimbingan (`from`,`to`,status_baca,keterangan,pokok_bimbingan,create_at,parent_id_bimbingan,kategori_bimbingan) VALUES('" . session()->get('ses_id') . "','$nim','belum dibaca','$keterangan','$pokok_bimbingan',now(),'$id_bimbingan',3)");
            session()->setFlashdata("message_bimbingan", '<div class="alert alert-success alert-dismissible fade show" role="alert">
            <span class="alert-inner--icon"><i class="fe fe-thumbs-up"></i></span>
            <span class="alert-inner--text"><strong>Sukses!</strong> kirim bimbingan.</span>
            <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>');
            $this->db->query("INSERT INTO tb_log (user,`action`,`log`,date_time) VALUES ('" . session()->get('ses_id') . "','Bimbingan','Revisi bimbingan $id_bimbingan',now())");
        }
        return redirect()->to("/bimbingan_skripsi_dosen/$nim");
    }
    public function hapus()
    {
        $id_bimbingan = $this->request->getPost('id_bimbingan');
        $nim = $this->request->getPost('nim');
        $this->db->query("DELETE FROM tb_bimbingan WHERE id_bimbingan='$id_bimbingan'");
        $this->db->query("INSERT INTO tb_log (user,`action`,`log`,date_time) VALUES ('" . session()->get('ses_id') . "','Bimbingan Dihapus','id bimbingan $id_bimbingan dihapus ',now())");
        return redirect()->to("/bimbingan_skripsi_dosen/$nim");
    }
}
