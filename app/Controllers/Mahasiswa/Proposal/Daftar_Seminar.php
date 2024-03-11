<?php

namespace App\Controllers\Mahasiswa\Proposal;

use App\Controllers\BaseController;

use App\Libraries\Access_API; // Import library

class Daftar_Seminar extends BaseController
{
    public function __construct()
    {
        $this->api = new Access_API();
        $this->db = \Config\Database::connect();
    }
    public function index()
    {
        if (session()->get('ses_id') == '' || session()->get('ses_login') != 'mahasiswa') {
            return redirect()->to('/');
        }
        $id = session()->get('ses_id');
        $idunit_mhs = $this->db->query("SELECT * FROM tb_mahasiswa WHERE nim='$id'")->getResult()[0]->idunit;
        $data = [
            'title' => 'Daftar Seminar Proposal',
            'db' => $this->db,
            'idunit_mhs' => $idunit_mhs,
            'pem1' => $this->db->query("SELECT a.*,b.`nama`,b.`gelardepan`,b.`gelarbelakang`,c.* FROM tb_pengajuan_pembimbing a LEFT JOIN tb_dosen b ON a.`nip`=b.`nip` LEFT JOIN tb_profil_tambahan c ON a.`nip` = c.`id` WHERE a.nim='$id' AND a.sebagai='1' AND a.status_pengajuan='diterima'")->getResult()[0],
            'pem2' => $this->db->query("SELECT a.*,b.`nama`,b.`gelardepan`,b.`gelarbelakang`,c.* FROM tb_pengajuan_pembimbing a LEFT JOIN tb_dosen b ON a.`nip`=b.`nip` LEFT JOIN tb_profil_tambahan c ON a.`nip` = c.`id` WHERE a.nim='$id' AND a.sebagai='2' AND a.status_pengajuan='diterima'")->getResult()[0],
            'kor' => $this->db->query("SELECT a.*,b.`nama`,b.`gelardepan`,b.`gelarbelakang`,c.* FROM tb_korprodi a LEFT JOIN tb_dosen b ON a.`nip`=b.`nip` LEFT JOIN tb_profil_tambahan c ON a.`nip` = c.`id` WHERE a.idunit='$idunit_mhs'")->getResult()[0],
            'data_jadwal' => $this->db->query("SELECT * FROM tb_jadwal_sidang where jenis_sidang='seminar proposal' AND idunit='$idunit_mhs'")->getResult(),
        ];
        return view('Mahasiswa/Proposal/daftar_seminar', $data);
    }
    public function izin()
    {
        if (session()->get('ses_id') == '' || session()->get('ses_login') != 'mahasiswa') {
            return redirect()->to('/');
        }
        $nip = $this->request->getPost('nip');
        $nim = $this->request->getPost('nim');
        $sebagai = $this->request->getPost('sebagai');
        $idunit = $this->request->getPost('idunit');
        $status = $this->db->query("SELECT * FROM tb_perizinan_sidang WHERE nim='" . session()->get('ses_id') . "' AND nip='" . $nip . "' AND `izin_sebagai`='$sebagai' AND jenis_sidang='seminar proposal'")->getResult();
        $cek_koor = $this->db->query("SELECT * FROM tb_korprodi WHERE nip='$nip' AND idunit='$idunit'")->getResult();
        $cek_pembimbing = $this->db->query("SELECT * FROM tb_pengajuan_pembimbing WHERE nip='$nip' AND nim='$nim' AND status_pengajuan='diterima'")->getResult();

        if (count($status) == NULL) {
            if ($sebagai == 'koordinator') {
                if (count($cek_pembimbing) != NULL) {
                    $this->db->query("INSERT INTO tb_perizinan_sidang (nim,nip,jenis_sidang,izin_sebagai,`status`,idunit) VALUES ('$nim','$nip','seminar proposal','$sebagai','menunggu','$idunit')");
                    $this->db->query("INSERT INTO tb_perizinan_sidang (nim,nip,jenis_sidang,izin_sebagai,`status`,idunit) VALUES ('$nim','$nip','seminar proposal','pembimbing " . $cek_pembimbing[0]->sebagai . "','menunggu','$idunit')");
                } else {
                    $this->db->query("INSERT INTO tb_perizinan_sidang (nim,nip,jenis_sidang,izin_sebagai,`status`,idunit) VALUES ('$nim','$nip','seminar proposal','$sebagai','menunggu','$idunit')");
                }
            } else {
                if (count($cek_koor) != NULL) {
                    $this->db->query("INSERT INTO tb_perizinan_sidang (nim,nip,jenis_sidang,izin_sebagai,`status`,idunit) VALUES ('$nim','$nip','seminar proposal','koordinator','menunggu','$idunit')");
                    $this->db->query("INSERT INTO tb_perizinan_sidang (nim,nip,jenis_sidang,izin_sebagai,`status`,idunit) VALUES ('$nim','$nip','seminar proposal','$sebagai','menunggu','$idunit')");
                } else {
                    $this->db->query("INSERT INTO tb_perizinan_sidang (nim,nip,jenis_sidang,izin_sebagai,`status`,idunit) VALUES ('$nim','$nip','seminar proposal','$sebagai','menunggu','$idunit')");
                }
            }
        } else {
            if ($status[0]->status == 'ditolak') {
                if ($sebagai == 'koordinator') {
                    if (count($cek_pembimbing) != NULL) {
                        $this->db->query("UPDATE tb_perizinan_sidang SET `status`='menunggu' WHERE id_perizinan_sidang='" . $status[0]->id_perizinan_sidang . "'");
                        $this->db->query("UPDATE tb_perizinan_sidang SET `status`='menunggu' WHERE nip='$nip' AND nim='$nim' AND izin_sebagai='pembimbing " . $cek_pembimbing[0]->sebagai . "' AND jenis_sidang='seminar proposal'");
                    } else {
                        $this->db->query("UPDATE tb_perizinan_sidang SET `status`='menunggu' WHERE id_perizinan_sidang='" . $status[0]->id_perizinan_sidang . "'");
                    }
                } else {
                    if (count($cek_koor) != NULL) {
                        $this->db->query("UPDATE tb_perizinan_sidang SET `status`='menunggu' WHERE nip='$nip' AND nim='$nim' AND izin_sebagai='koordinator' AND jenis_sidang='seminar proposal'");
                        $this->db->query("UPDATE tb_perizinan_sidang SET `status`='menunggu' WHERE id_perizinan_sidang='" . $status[0]->id_perizinan_sidang . "'");
                    } else {
                        $this->db->query("UPDATE tb_perizinan_sidang SET `status`='menunggu' WHERE id_perizinan_sidang='" . $status[0]->id_perizinan_sidang . "'");
                    }
                }
            }
        }
        return redirect()->to('/daftar_seminar');
    }
    public function mendaftar()
    {
        set_time_limit(0);
        ini_set('max_execution_time', 0);
        ini_set('max_input_time', 0);
        ini_set('post_max_size', '1000M');
        ini_set('upload_max_filesize', '1000M');
        if (!$this->validate([
            'berkas_proposal' => [
                // 'rules' => 'uploaded[berkas]|mime_in[berkas,application/pdf]|max_size[berkas,2048]',
                'rules' => 'uploaded[berkas_proposal]|mime_in[berkas_proposal,application/pdf]|max_size[berkas_proposal, 2048]',
                'errors' => [
                    'uploaded' => 'Harus Ada File yang diupload',
                    'mime_in' => 'File Extention Harus Berupa pdf',
                    'max_size' => 'Ukuran File Maksimal 2 MB'
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
        if (session()->get('ses_id') == '' || session()->get('ses_login') != 'mahasiswa') {
            return redirect()->to('/');
        }
        $id_jadwal = $this->request->getPost('id_jadwal');
        $berkas = $this->request->getFile('berkas_proposal');
        $name = $berkas->getRandomName();
        if ($berkas->getName() != '') {
            $cek = $this->db->query("SELECT * FROM tb_pendaftar_sidang WHERE nim = '" . session()->get('ses_id') . "' AND id_jadwal='$id_jadwal'")->getResult();
            if (count($cek) > 0) {
                session()->setFlashdata("message", '<div class="alert alert-danger alert-dismissible fade show mb-0" role="alert">
                <span class="alert-inner--icon"><i class="fe fe-slash"></i></span>
                <span class="alert-inner--text"><strong>Gagal!</strong> anda sudah terdaftar.</span>
                <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>');
            } else {
                if ($berkas->move(WRITEPATH . '../public/berkas/', $name)) {
                    $this->db->query("INSERT INTO tb_pendaftar_sidang (nim,id_jadwal,create_at,file_proposal) VALUES ('" . session()->get('ses_id') . "','$id_jadwal',now(),'" . $name . "')");
                    session()->setFlashdata("message", '<div class="alert alert-success alert-dismissible fade show" role="alert">
                <span class="alert-inner--icon"><i class="fe fe-thumbs-up"></i></span>
                <span class="alert-inner--text"><strong>Sukses!</strong> mendaftar sidang seminar proposal.</span>
                <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>');
                } else {
                    session()->setFlashdata("message", '<div class="alert alert-danger alert-dismissible fade show mb-0" role="alert">
                <span class="alert-inner--icon"><i class="fe fe-slash"></i></span>
                <span class="alert-inner--text"><strong>Gagal!</strong> mendaftar sidang seminar proposal.</span>
                <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>');
                }
            }
        }
        return redirect()->to('/daftar_seminar');
    }
}
