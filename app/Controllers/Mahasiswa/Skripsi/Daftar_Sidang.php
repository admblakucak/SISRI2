<?php

namespace App\Controllers\Mahasiswa\Skripsi;

use App\Controllers\BaseController;

use App\Libraries\Access_API; // Import library

class Daftar_Sidang extends BaseController
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
        set_time_limit(0);
        ini_set('max_execution_time', '0');
        ini_set('max_input_time', '0');
        $data = $this->api->get_data_api("https://api.trunojoyo.ac.id:8212/siakad/v1/krs?page=1&take=1000&nim=" . session()->get('ses_id'));
        // $datamka = [];
        // $datanmka = [];
        // foreach ($data as $key) {
        //     $datamk = $this->api->get_data_api("https://api.trunojoyo.ac.id:8212/siakad/v1/matakuliah?page=1&take=100&idmk=" . $key->idmk);
        //     if (strtoupper($datamk[0]->namamk) != 'SKRIPSI' && strtoupper($datamk[0]->namamk) != 'TUGAS AKHIR') {
        //         $a = array_search(strtoupper($datamk[0]->namamk), $datamka);
        //         if ($a == NULL) {
        //             array_push($datamka, strtoupper($datamk[0]->namamk));
        //             array_push($datanmka, $key->nhuruf);
        //         } else {
        //             $datanmka[$a] = $key->nhuruf;
        //         }
        //     }
        // }
        // $datafix = [];
        // for ($i = 0; $i < count($datamka); $i++) {
        //     $error = [
        //         'namamk' => $datamka[$i],
        //         'nhuruf' => $datanmka[$i]
        //     ];
        //     array_push($datafix, $error);
        // }
        // $mkerror = [];
        // foreach ($datafix as $key) {
        //     if ($key['nhuruf'] == 'E' || $key['nhuruf'] == 'D' || $key['nhuruf'] == NULL) {
        //         $error = [
        //             'namamk' => $key['namamk'],
        //             'nilai' => $key['nhuruf']
        //         ];
        //         array_push($mkerror, $error);
        //     }
        // }
        $id = session()->get('ses_id');
        $idunit_mhs = $this->db->query("SELECT * FROM tb_mahasiswa WHERE nim='$id'")->getResult()[0]->idunit;
        $data = [
            'title' => 'Daftar Sidang Skripsi',
            'db' => $this->db,
            'idunit_mhs' => $idunit_mhs,
            'pem1' => $this->db->query("SELECT a.*,b.`nama`,b.`gelardepan`,b.`gelarbelakang`,c.* FROM tb_pengajuan_pembimbing a LEFT JOIN tb_dosen b ON a.`nip`=b.`nip` LEFT JOIN tb_profil_tambahan c ON a.`nip` = c.`id` WHERE a.nim='$id' AND a.sebagai='1' AND a.status_pengajuan='diterima'")->getResult()[0],
            'pem2' => $this->db->query("SELECT a.*,b.`nama`,b.`gelardepan`,b.`gelarbelakang`,c.* FROM tb_pengajuan_pembimbing a LEFT JOIN tb_dosen b ON a.`nip`=b.`nip` LEFT JOIN tb_profil_tambahan c ON a.`nip` = c.`id` WHERE a.nim='$id' AND a.sebagai='2' AND a.status_pengajuan='diterima'")->getResult()[0],
            'kor' => $this->db->query("SELECT a.*,b.`nama`,b.`gelardepan`,b.`gelarbelakang`,c.* FROM tb_korprodi a LEFT JOIN tb_dosen b ON a.`nip`=b.`nip` LEFT JOIN tb_profil_tambahan c ON a.`nip` = c.`id` WHERE a.idunit='$idunit_mhs'")->getResult()[0],
            'admin_akademik' => $this->db->query("SELECT a.*,c.* FROM tb_admin_akademik a LEFT JOIN tb_profil_tambahan c ON a.`nip`=c.`id` WHERE a.idunit='$idunit_mhs'")->getResult()[0],
            'data_jadwal' => $this->db->query("SELECT * FROM tb_jadwal_sidang where jenis_sidang='sidang skripsi' AND idunit='$idunit_mhs'")->getResult(),
            // 'mkerror' => $mkerror,
        ];
        return view('Mahasiswa/Skripsi/daftar_sidang_skripsi', $data);
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
        $status = $this->db->query("SELECT * FROM tb_perizinan_sidang WHERE nim='" . session()->get('ses_id') . "' AND nip='" . $nip . "' AND `izin_sebagai`='$sebagai' AND jenis_sidang='skripsi'")->getResult();
        $cek_koor = $this->db->query("SELECT * FROM tb_korprodi WHERE nip='$nip' AND idunit='$idunit'")->getResult();
        $cek_admin_akademik = $this->db->query("SELECT * FROM tb_admin_akademik WHERE nip='$nip' AND idunit='$idunit'")->getResult();
        $cek_pembimbing = $this->db->query("SELECT * FROM tb_pengajuan_pembimbing WHERE nip='$nip' AND nim='$nim' AND status_pengajuan='diterima'")->getResult();

        if (count($status) == NULL) {
            if ($sebagai == 'koordinator') {
                if (count($cek_pembimbing) != NULL) {
                    $this->db->query("INSERT INTO tb_perizinan_sidang (nim,nip,jenis_sidang,izin_sebagai,`status`,idunit) VALUES ('$nim','$nip','skripsi','$sebagai','menunggu','$idunit')");
                    $this->db->query("INSERT INTO tb_perizinan_sidang (nim,nip,jenis_sidang,izin_sebagai,`status`,idunit) VALUES ('$nim','$nip','skripsi','pembimbing " . $cek_pembimbing[0]->sebagai . "','menunggu','$idunit')");
                } else {
                    $this->db->query("INSERT INTO tb_perizinan_sidang (nim,nip,jenis_sidang,izin_sebagai,`status`,idunit) VALUES ('$nim','$nip','skripsi','$sebagai','menunggu','$idunit')");
                }
            } elseif ($sebagai == 'admin_akademik') {
                $this->db->query("INSERT INTO tb_perizinan_sidang (nim,nip,jenis_sidang,izin_sebagai,`status`,idunit) VALUES ('$nim','$nip','skripsi','$sebagai','menunggu','$idunit')");
            } else {
                if (count($cek_koor) != NULL) {
                    $this->db->query("INSERT INTO tb_perizinan_sidang (nim,nip,jenis_sidang,izin_sebagai,`status`,idunit) VALUES ('$nim','$nip','skripsi','koordinator','menunggu','$idunit')");
                    $this->db->query("INSERT INTO tb_perizinan_sidang (nim,nip,jenis_sidang,izin_sebagai,`status`,idunit) VALUES ('$nim','$nip','skripsi','$sebagai','menunggu','$idunit')");
                } else {
                    $this->db->query("INSERT INTO tb_perizinan_sidang (nim,nip,jenis_sidang,izin_sebagai,`status`,idunit) VALUES ('$nim','$nip','skripsi','$sebagai','menunggu','$idunit')");
                }
            }
        } else {
            if ($status[0]->status == 'ditolak') {
                if ($sebagai == 'koordinator') {
                    if (count($cek_pembimbing) != NULL) {
                        $this->db->query("UPDATE tb_perizinan_sidang SET `status`='menunggu' WHERE id_perizinan_sidang='" . $status[0]->id_perizinan_sidang . "'");
                        $this->db->query("UPDATE tb_perizinan_sidang SET `status`='menunggu' WHERE nip='$nip' AND nim='$nim' AND izin_sebagai='pembimbing " . $cek_pembimbing[0]->sebagai . "' AND jenis_sidang='skripsi'");
                    } else {
                        $this->db->query("UPDATE tb_perizinan_sidang SET `status`='menunggu' WHERE id_perizinan_sidang='" . $status[0]->id_perizinan_sidang . "'");
                    }
                } elseif ($sebagai == 'admin_akademik') {
                    $this->db->query("INSERT INTO tb_perizinan_sidang (nim,nip,jenis_sidang,izin_sebagai,`status`,idunit) VALUES ('$nim','$nip','skripsi','$sebagai','menunggu','$idunit')");
                } else {
                    if (count($cek_koor) != NULL) {
                        $this->db->query("UPDATE tb_perizinan_sidang SET `status`='menunggu' WHERE nip='$nip' AND nim='$nim' AND izin_sebagai='koordinator' AND jenis_sidang='skripsi'");
                        $this->db->query("UPDATE tb_perizinan_sidang SET `status`='menunggu' WHERE id_perizinan_sidang='" . $status[0]->id_perizinan_sidang . "'");
                    } else {
                        $this->db->query("UPDATE tb_perizinan_sidang SET `status`='menunggu' WHERE id_perizinan_sidang='" . $status[0]->id_perizinan_sidang . "'");
                    }
                }
            }
        }
        return redirect()->to('/daftar_sidang');
    }
    public function mendaftar()
    {
        if (session()->get('ses_id') == '' || session()->get('ses_login') != 'mahasiswa') {
            return redirect()->to('/');
        }
        set_time_limit(0);
        ini_set('max_execution_time', 0);
        ini_set('max_input_time', 0);
        ini_set('post_max_size', '1000M');
        ini_set('upload_max_filesize', '1000M');
        if (!$this->validate([
            'berkas_proposal' => [
                'rules' => 'uploaded[berkas_proposal]|mime_in[berkas_proposal,application/pdf]|max_size[berkas_proposal, 5026]',
                'errors' => [
                    'uploaded' => 'File berkas proposal harus diupload.',
                    'mime_in' => 'File Extention Harus Berupa pdf',
                    'max_size' => 'Ukuran File proposal Maksimal 5 MB'
                ]

            ],
            'berkas_turnitin' => [
                'rules' => 'uploaded[berkas_turnitin]|mime_in[berkas_turnitin,application/pdf]|max_size[berkas_proposal, 5026]',
                'errors' => [
                    'uploaded' => 'File berkas turnitin harus diupload.',
                    'mime_in' => 'File Extention Harus Berupa pdf',
                    'max_size' => 'Ukuran File turnitin Maksimal 20 MB'
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
        $id_jadwal = $this->request->getPost('id_jadwal');
        $berkas = $this->request->getFile('berkas_proposal');
        $name = $berkas->getRandomName();
        $berkas2 = $this->request->getFile('berkas_turnitin');
        $name2 = $berkas2->getRandomName();
        if ($berkas->getName() != '' && $berkas2->getName() != '') {
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
                $nim = session()->get('ses_id');
                $this->db->query("UPDATE tb_penguji SET `status`='nonaktif' WHERE nim='$nim' AND jenis_sidang = 'sidang skripsi'");
                $penguji1 = $this->db->query("SELECT * FROM tb_penguji where nim ='" . session()->get('ses_id') . "' AND jenis_sidang = '' AND status='aktif' AND sebagai = 1")->getResult()[0]->nip;
                $penguji2 = $this->db->query("SELECT * FROM tb_penguji where nim ='" . session()->get('ses_id') . "' AND jenis_sidang = '' AND status='aktif' AND sebagai = 2")->getResult()[0]->nip;
                $penguji3 = $this->db->query("SELECT * FROM tb_penguji where nim ='" . session()->get('ses_id') . "' AND jenis_sidang = '' AND status='aktif' AND sebagai = 3")->getResult()[0]->nip;
                $this->db->query("INSERT INTO tb_penguji (nim,nip,sebagai,`status`,jenis_sidang) VALUES('$nim','$penguji1','1','aktif','sidang skripsi')");
                $this->db->query("INSERT INTO tb_penguji (nim,nip,sebagai,`status`,jenis_sidang) VALUES('$nim','$penguji2','2','aktif','sidang skripsi')");
                $this->db->query("INSERT INTO tb_penguji (nim,nip,sebagai,`status`,jenis_sidang) VALUES('$nim','$penguji3','3','aktif','sidang skripsi')");
                if ($berkas->move(WRITEPATH . '../public/berkas/', $name) && $berkas2->move(WRITEPATH . '../public/berkas/', $name2)) {
                    $this->db->query("INSERT INTO tb_pendaftar_sidang (nim,id_jadwal,create_at,file_proposal,file_turnitin) VALUES ('" . session()->get('ses_id') . "','$id_jadwal',now(),'" . $name . "','" . $name2 . "')");
                    session()->setFlashdata("message", '<div class="alert alert-success alert-dismissible fade show" role="alert">
                <span class="alert-inner--icon"><i class="fe fe-thumbs-up"></i></span>
                <span class="alert-inner--text"><strong>Sukses!</strong> mendaftar sidang skripsi.</span>
                <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>');
                } else {
                    session()->setFlashdata("message", '<div class="alert alert-danger alert-dismissible fade show mb-0" role="alert">
                <span class="alert-inner--icon"><i class="fe fe-slash"></i></span>
                <span class="alert-inner--text"><strong>Gagal!</strong> mendaftar sidang skripsi.</span>
                <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>');
                }
            }
        }
        return redirect()->to('/daftar_sidang');
    }
}
