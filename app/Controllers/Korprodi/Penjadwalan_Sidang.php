<?php

namespace App\Controllers\Korprodi;

use App\Controllers\BaseController;

use App\Libraries\Access_API; // Import library
use DateTime;

class Penjadwalan_sidang extends BaseController
{
    public function __construct()
    {
        $this->api = new Access_API();
        $this->db = \Config\Database::connect();
    }
    public function index()
    {
        if (session()->get('ses_id') == '' || session()->get('ses_login') != 'korprodi') {
            return redirect()->to('/');
        }
        $idunit = $this->db->query("SELECT * FROM tb_dosen WHERE nip='" . session()->get('ses_id') . "'")->getResult()[0]->idunit;
        $idjurusan = $this->db->query("SELECT c.`idunit` AS idjurusan FROM tb_dosen a LEFT JOIN tb_unit b ON a.`idunit`=b.idunit LEFT JOIN tb_unit c ON b.`parentunit`=c.`idunit` WHERE a.nip='" . session()->get('ses_id') . "'")->getResult()[0]->idjurusan;
        $data = [
            'title' => 'Penjadwalan Sidang',
            'db' => $this->db,
            'idunit' => $idunit,
            'idjurusan' => $idjurusan,
            'data_jadwal' => $this->db->query("SELECT * FROM tb_jadwal_sidang WHERE idunit='$idunit'")->getResult(),
        ];
        return view('Korprodi/penjadwalan_sidang', $data);
    }
    public function add()
    {
        if (session()->get('ses_id') == '' || session()->get('ses_login') != 'korprodi') {
            return redirect()->to('/');
        }
        if (!$this->validate([
            'periode' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Silahkan berikan keterangan periode'
                ]
            ],
            'open' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Tentukan dibuka pendaftaran'
                ]
            ], 'expire' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Tentukan ditutup pendaftaran'
                ]
            ], 'jenis_sidang' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Pilih jenis sidang'
                ]
            ],
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
        $periode = $this->request->getPost('periode');
        $open = $this->request->getPost('open');
        $expire = $this->request->getPost('expire');
        $jenis_sidang = $this->request->getPost('jenis_sidang');
        $idunit = $this->request->getPost('idunit');
        $this->db->query("INSERT INTO tb_jadwal_sidang (periode,`open`,expire,jenis_sidang,idunit) VALUES ('$periode','$open','$expire','$jenis_sidang','$idunit')");

        session()->setFlashdata("message", '<div class="alert alert-success alert-dismissible fade show" role="alert">
            <span class="alert-inner--icon"><i class="fe fe-thumbs-up"></i></span>
            <span class="alert-inner--text"><strong>Sukses!</strong> Menambahkan pendaftaran sidang</span>
            <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>');

        return redirect()->to('/penjadwalan_sidang');
    }
    public function del()
    {
        if (session()->get('ses_id') == '' || session()->get('ses_login') != 'korprodi') {
            return redirect()->to('/');
        }
        $id_jadwal = $this->request->getPost('id_jadwal');
        $data = $this->db->query("SELECT * FROM tb_pendaftar_sidang WHERE id_jadwal='$id_jadwal'")->getResult();
        foreach ($data as $key) {
            $this->db->query("DELETE FROM tb_penguji WHERE id_pendaftar='$key->id_pendaftar'");
        }
        $this->db->query("DELETE FROM tb_pendaftar_sidang WHERE id_jadwal='$id_jadwal'");
        $this->db->query("DELETE FROM tb_jadwal_sidang WHERE id_jadwal='$id_jadwal'");

        session()->setFlashdata("message", '<div class="alert alert-success alert-dismissible fade show" role="alert">
            <span class="alert-inner--icon"><i class="fe fe-thumbs-up"></i></span>
            <span class="alert-inner--text"><strong>Sukses!</strong> Menghapus jadwal pendaftaran sidang</span>
            <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>');

        return redirect()->to('/penjadwalan_sidang');
    }
    public function upd()
    {
        if (session()->get('ses_id') == '' || session()->get('ses_login') != 'korprodi') {
            return redirect()->to('/');
        }
        $id_jadwal = $this->request->getPost('id_jadwal');
        $periode = $this->request->getPost('periode');
        $open = $this->request->getPost('open');
        $expire = $this->request->getPost('expire');
        $jenis_sidang = $this->request->getPost('jenis_sidang');
        $this->db->query("UPDATE tb_jadwal_sidang SET periode='$periode',`open`='$open',expire='$expire',jenis_sidang='$jenis_sidang' WHERE id_jadwal='$id_jadwal'");

        session()->setFlashdata("message", '<div class="alert alert-success alert-dismissible fade show" role="alert">
            <span class="alert-inner--icon"><i class="fe fe-thumbs-up"></i></span>
            <span class="alert-inner--text"><strong>Sukses!</strong> Mengedit jadwal pendaftaran sidang</span>
            <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>');

        return redirect()->to('/penjadwalan_sidang');
    }
    public function data_pendaftar()
    {
        if (session()->get('ses_id') == '' || session()->get('ses_login') != 'korprodi') {
            return redirect()->to('/');
        }
        $id_jadwal = $this->request->getPost('id_jadwal');
        $jenis_sidang = $this->request->getPost('jenis_sidang');
        if ($id_jadwal == NULL) {
            $id_jadwal = session()->get('ses_id_jadwal');
        }
        $update = $this->request->getPost('update');
        if (isset($update)) {
            $nim = $this->request->getPost('nim');
            $id_pendaftar = $this->request->getPost('id_pendaftar');
            $waktu_sidang = $this->request->getPost('waktu_sidang');
            $ruang_sidang = $this->request->getPost('ruang_sidang');
            $nip_p1 = $this->request->getPost('nip_p1');
            $nip_p2 = $this->request->getPost('nip_p2');
            $nip_p3 = $this->request->getPost('nip_p3');
            if ($nip_p1 == $nip_p2 || $nip_p2 == $nip_p3) {
                session()->setFlashdata('message', '<div class="alert alert-danger alert-dismissible fade show mb-0" role="alert">
                <span class="alert-inner--icon"><i class="fe fe-slash"></i></span>
                <span class="alert-inner--text"><strong>Gagal!</strong> ' . 'Dosen Penguji Tidak Boleh Sama' . '</span>
                <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>');
                return redirect()->back()->withInput();
            }

            // $tb_penguji_1 = $this->db->query("SELECT a.id_pendaftar,b.waktu_sidang, a.nim, a.nip FROM tb_penguji a LEFT JOIN tb_pendaftar_sidang b ON a.nim=b.nim WHERE a.nip='" . $nip_p1 . "' AND a.nim !='" . $nim . "' ")->getResult();
            // if (!empty($tb_penguji_1)) {
            //     foreach ($tb_penguji_1 as $key) {
            //         $d_sidang = new DateTime($waktu_sidang);
            //         $d_sidang_2 = new DateTime($key->waktu_sidang);
            //         $interval = date_diff($d_sidang_2, $d_sidang);
            //         $min = $interval->days * 24 * 60;
            //         $min += $interval->h * 60;
            //         $min += $interval->i;
            //         if ($min <= 90) {
            //             session()->setFlashdata('message', '<div class="alert alert-danger alert-dismissible fade show mb-0" role="alert">
            //             <span class="alert-inner--icon"><i class="fe fe-slash"></i></span>
            //             <span class="alert-inner--text"><strong>Gagal!</strong> ' . 'Jadwal Penguji 1 bentrok' . '</span>
            //             <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
            //                 <span aria-hidden="true">×</span>
            //             </button>
            //         </div>');
            //             return redirect()->back()->withInput();
            //         }
            //     }
            // }
            // $tb_penguji_2 = $this->db->query("SELECT a.id_pendaftar,b.waktu_sidang, a.nim, a.nip FROM tb_penguji a LEFT JOIN tb_pendaftar_sidang b ON a.nim=b.nim WHERE a.nip='" . $nip_p2 . "' AND a.nim !='" . $nim . "' ")->getResult();
            // if (!empty($tb_penguji_2)) {
            //     foreach ($tb_penguji_2 as $key) {
            //         $d_sidang = new DateTime($waktu_sidang);
            //         $d_sidang_2 = new DateTime($key->waktu_sidang);
            //         $interval = date_diff($d_sidang_2, $d_sidang);
            //         $min = $interval->days * 24 * 60;
            //         $min += $interval->h * 60;
            //         $min += $interval->i;
            //         if ($min <= 90) {
            //             session()->setFlashdata('message', '<div class="alert alert-danger alert-dismissible fade show mb-0" role="alert">
            //             <span class="alert-inner--icon"><i class="fe fe-slash"></i></span>
            //             <span class="alert-inner--text"><strong>Gagal!</strong> ' . 'Jadwal Penguji 2 bentrok' . '</span>
            //             <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
            //                 <span aria-hidden="true">×</span>
            //             </button>
            //         </div>');
            //             return redirect()->back()->withInput();
            //         }
            //     }
            // }
            // $tb_penguji_3 = $this->db->query("SELECT a.id_pendaftar,b.waktu_sidang, a.nim, a.nip FROM tb_penguji a LEFT JOIN tb_pendaftar_sidang b ON a.nim=b.nim WHERE a.nip='" . $nip_p3 . "' AND a.nim !='" . $nim . "' ")->getResult();
            // if (!empty($tb_penguji_3)) {
            //     foreach ($tb_penguji_3 as $key) {
            //         $d_sidang = new DateTime($waktu_sidang);
            //         $d_sidang_2 = new DateTime($key->waktu_sidang);
            //         $interval = date_diff($d_sidang_2, $d_sidang);
            //         $min = $interval->days * 24 * 60;
            //         $min += $interval->h * 60;
            //         $min += $interval->i;
            //         if ($min <= 90) {
            //             session()->setFlashdata('message', '<div class="alert alert-danger alert-dismissible fade show mb-0" role="alert">
            //             <span class="alert-inner--icon"><i class="fe fe-slash"></i></span>
            //             <span class="alert-inner--text"><strong>Gagal!</strong> ' . 'Jadwal Penguji 3 bentrok' . '</span>
            //             <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
            //                 <span aria-hidden="true">×</span>
            //             </button>
            //         </div>');
            //             return redirect()->back()->withInput();
            //         }
            //     }
            // }
            $jenis_sidang = $this->request->getPost('jenis_sidang');
            $cek_p1 = $this->db->query("SELECT * FROM tb_penguji WHERE id_pendaftar='$id_pendaftar' AND sebagai='1'")->getResult();
            $cek_p2 = $this->db->query("SELECT * FROM tb_penguji WHERE id_pendaftar='$id_pendaftar' AND sebagai='2'")->getResult();
            $cek_p3 = $this->db->query("SELECT * FROM tb_penguji WHERE id_pendaftar='$id_pendaftar' AND sebagai='3'")->getResult();
            $this->db->query("UPDATE tb_pendaftar_sidang SET waktu_sidang='$waktu_sidang',ruang_sidang='$ruang_sidang' WHERE id_pendaftar='$id_pendaftar'");
            // if ($jenis_sidang == 'seminar proposal' || $jenis_sidang == 'sidang skripsi') {
            if ($jenis_sidang == 'seminar proposal') {


                // interval waktu sidang
                $time_intervar_sempro = 60;

                $tb_penguji_1 = $this->db->query("SELECT a.id_pendaftar,b.waktu_sidang, a.nim, a.nip FROM tb_penguji a LEFT JOIN tb_pendaftar_sidang b ON a.nim=b.nim WHERE a.nip='" . $nip_p1 . "' AND a.nim !='" . $nim . "' ")->getResult();
                if (!empty($tb_penguji_1)) {
                    foreach ($tb_penguji_1 as $key) {
                        $d_sidang = new DateTime($waktu_sidang);
                        $d_sidang_2 = new DateTime($key->waktu_sidang);
                        $interval = date_diff($d_sidang_2, $d_sidang);
                        $min = $interval->days * 24 * 60;
                        $min += $interval->h * 60;
                        $min += $interval->i;
                        if ($min <= $time_intervar_sempro) {
                            session()->setFlashdata('message', '<div class="alert alert-danger alert-dismissible fade show mb-0" role="alert">
                            <span class="alert-inner--icon"><i class="fe fe-slash"></i></span>
                            <span class="alert-inner--text"><strong>Gagal!</strong> ' . 'Jadwal Penguji 1 bentrok' . '</span>
                            <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>');
                            return redirect()->back()->withInput();
                        }
                    }
                }
                $tb_penguji_2 = $this->db->query("SELECT a.id_pendaftar,b.waktu_sidang, a.nim, a.nip FROM tb_penguji a LEFT JOIN tb_pendaftar_sidang b ON a.nim=b.nim WHERE a.nip='" . $nip_p2 . "' AND a.nim !='" . $nim . "' ")->getResult();
                if (!empty($tb_penguji_2)) {
                    foreach ($tb_penguji_2 as $key) {
                        $d_sidang = new DateTime($waktu_sidang);
                        $d_sidang_2 = new DateTime($key->waktu_sidang);
                        $interval = date_diff($d_sidang_2, $d_sidang);
                        $min = $interval->days * 24 * 60;
                        $min += $interval->h * 60;
                        $min += $interval->i;
                        if ($min <= $time_intervar_sempro) {
                            session()->setFlashdata('message', '<div class="alert alert-danger alert-dismissible fade show mb-0" role="alert">
                            <span class="alert-inner--icon"><i class="fe fe-slash"></i></span>
                            <span class="alert-inner--text"><strong>Gagal!</strong> ' . 'Jadwal Penguji 2 bentrok' . '</span>
                            <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>');
                            return redirect()->back()->withInput();
                        }
                    }
                }
                $tb_penguji_3 = $this->db->query("SELECT a.id_pendaftar,b.waktu_sidang, a.nim, a.nip FROM tb_penguji a LEFT JOIN tb_pendaftar_sidang b ON a.nim=b.nim WHERE a.nip='" . $nip_p3 . "' AND a.nim !='" . $nim . "' ")->getResult();
                if (!empty($tb_penguji_3)) {
                    foreach ($tb_penguji_3 as $key) {
                        $d_sidang = new DateTime($waktu_sidang);
                        $d_sidang_2 = new DateTime($key->waktu_sidang);
                        $interval = date_diff($d_sidang_2, $d_sidang);
                        $min = $interval->days * 24 * 60;
                        $min += $interval->h * 60;
                        $min += $interval->i;
                        if ($min <= $time_intervar_sempro) {
                            session()->setFlashdata('message', '<div class="alert alert-danger alert-dismissible fade show mb-0" role="alert">
                            <span class="alert-inner--icon"><i class="fe fe-slash"></i></span>
                            <span class="alert-inner--text"><strong>Gagal!</strong> ' . 'Jadwal Penguji 3 bentrok' . '</span>
                            <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>');
                            return redirect()->back()->withInput();
                        }
                    }
                }

                $cek = $this->db->query("SELECT * FROM tb_penguji WHERE nim ='$nim'")->getResult();
                if ($cek != NULL) {
                    $this->db->query("UPDATE tb_penguji SET `status`='nonaktif' WHERE nim='$nim'");
                }
                if ($cek_p1 != NULL) {
                    $this->db->query("UPDATE tb_penguji SET nip='$nip_p1',`status`='aktif' WHERE nim='$nim' AND id_pendaftar='$id_pendaftar' AND sebagai='1'");
                } else {
                    $this->db->query("INSERT INTO tb_penguji (nim,nip,sebagai,id_pendaftar,`status`) VALUES('$nim','$nip_p1','1','$id_pendaftar','aktif')");
                }
                if ($cek_p2 != NULL) {
                    $this->db->query("UPDATE tb_penguji SET nip='$nip_p2',`status`='aktif' WHERE nim='$nim' AND id_pendaftar='$id_pendaftar' AND sebagai='2'");
                } else {
                    $this->db->query("INSERT INTO tb_penguji (nim,nip,sebagai,id_pendaftar,`status`) VALUES('$nim','$nip_p2','2','$id_pendaftar','aktif')");
                }
                if ($cek_p3 != NULL) {
                    $this->db->query("UPDATE tb_penguji SET nip='$nip_p3',`status`='aktif' WHERE nim='$nim' AND id_pendaftar='$id_pendaftar' AND sebagai='3'");
                } else {
                    $this->db->query("INSERT INTO tb_penguji (nim,nip,sebagai,id_pendaftar,`status`) VALUES('$nim','$nip_p3','3','$id_pendaftar','aktif')");
                }
                echo "INSERT INTO tb_penguji (nim,nip,sebagai,id_pendaftar,`status`) VALUES('$nim','$nip_p3','3','$id_pendaftar','aktif')";
            } else {
                // interval waktu sidang
                $time_intervar_sidang = 90;
                $tb_penguji_1 = $this->db->query("SELECT a.id_pendaftar,b.waktu_sidang, a.nim, a.nip FROM tb_penguji a LEFT JOIN tb_pendaftar_sidang b ON a.nim=b.nim WHERE a.nip='" . $nip_p1 . "' AND a.nim !='" . $nim . "' ")->getResult();
                if (!empty($tb_penguji_1)) {
                    foreach ($tb_penguji_1 as $key) {
                        $d_sidang = new DateTime($waktu_sidang);
                        $d_sidang_2 = new DateTime($key->waktu_sidang);
                        $interval = date_diff($d_sidang_2, $d_sidang);
                        $min = $interval->days * 24 * 60;
                        $min += $interval->h * 60;
                        $min += $interval->i;
                        if ($min <= $time_intervar_sidang) {
                            session()->setFlashdata('message', '<div class="alert alert-danger alert-dismissible fade show mb-0" role="alert">
                            <span class="alert-inner--icon"><i class="fe fe-slash"></i></span>
                            <span class="alert-inner--text"><strong>Gagal!</strong> ' . 'Jadwal Penguji 1 bentrok' . '</span>
                            <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>');
                            return redirect()->back()->withInput();
                        }
                    }
                }
                
                $tb_penguji_2 = $this->db->query("SELECT a.id_pendaftar,b.waktu_sidang, a.nim, a.nip FROM tb_penguji a LEFT JOIN tb_pendaftar_sidang b ON a.nim=b.nim WHERE a.nip='" . $nip_p2 . "' AND a.nim !='" . $nim . "' ")->getResult();
                if (!empty($tb_penguji_2)) {
                    foreach ($tb_penguji_2 as $key) {
                        $d_sidang = new DateTime($waktu_sidang);
                        $d_sidang_2 = new DateTime($key->waktu_sidang);
                        $interval = date_diff($d_sidang_2, $d_sidang);
                        $min = $interval->days * 24 * 60;
                        $min += $interval->h * 60;
                        $min += $interval->i;
                        if ($min <= $time_intervar_sidang) {
                            session()->setFlashdata('message', '<div class="alert alert-danger alert-dismissible fade show mb-0" role="alert">
                            <span class="alert-inner--icon"><i class="fe fe-slash"></i></span>
                            <span class="alert-inner--text"><strong>Gagal!</strong> ' . 'Jadwal Penguji 2 bentrok' . '</span>
                            <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>');
                            return redirect()->back()->withInput();
                        }
                    }
                }

                $tb_penguji_3 = $this->db->query("SELECT a.id_pendaftar,b.waktu_sidang, a.nim, a.nip FROM tb_penguji a LEFT JOIN tb_pendaftar_sidang b ON a.nim=b.nim WHERE a.nip='" . $nip_p3 . "' AND a.nim !='" . $nim . "' ")->getResult();
                if (!empty($tb_penguji_3)) {
                    foreach ($tb_penguji_3 as $key) {
                        $d_sidang = new DateTime($waktu_sidang);
                        $d_sidang_2 = new DateTime($key->waktu_sidang);
                        $interval = date_diff($d_sidang_2, $d_sidang);
                        $min = $interval->days * 24 * 60;
                        $min += $interval->h * 60;
                        $min += $interval->i;
                        if ($min <= $time_intervar_sidang) {
                            session()->setFlashdata('message', '<div class="alert alert-danger alert-dismissible fade show mb-0" role="alert">
                            <span class="alert-inner--icon"><i class="fe fe-slash"></i></span>
                            <span class="alert-inner--text"><strong>Gagal!</strong> ' . 'Jadwal Penguji 3 bentrok' . '</span>
                            <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>');
                            return redirect()->back()->withInput();
                        }
                    }
                }

                $this->db->query("UPDATE tb_penguji SET `status`='nonaktif' WHERE nim='$nim' AND jenis_sidang='sidang skripsi'");
                $cek_p1 = $this->db->query("SELECT * FROM tb_penguji WHERE id_pendaftar='$id_pendaftar' AND sebagai='1' AND jenis_sidang ='sidang skripsi'")->getResult();
                $cek_p2 = $this->db->query("SELECT * FROM tb_penguji WHERE id_pendaftar='$id_pendaftar' AND sebagai='2' AND jenis_sidang ='sidang skripsi'")->getResult();
                $cek_p3 = $this->db->query("SELECT * FROM tb_penguji WHERE id_pendaftar='$id_pendaftar' AND sebagai='3' AND jenis_sidang ='sidang skripsi'")->getResult();
                if ($cek_p1 != NULL) {
                    $this->db->query("UPDATE tb_penguji SET nip='$nip_p1',`status`='aktif',jenis_sidang='sidang skripsi' WHERE nim='$nim' AND id_pendaftar='$id_pendaftar' AND sebagai='1'");
                } else {
                    $this->db->query("INSERT INTO tb_penguji (nim,nip,sebagai,id_pendaftar,`status`,jenis_sidang) VALUES('$nim','$nip_p1','1','$id_pendaftar','aktif','sidang skripsi')");
                }
                if ($cek_p2 != NULL) {
                    $this->db->query("UPDATE tb_penguji SET nip='$nip_p2',`status`='aktif',jenis_sidang='sidang skripsi' WHERE nim='$nim' AND id_pendaftar='$id_pendaftar' AND sebagai='2'");
                } else {
                    $this->db->query("INSERT INTO tb_penguji (nim,nip,sebagai,id_pendaftar,`status`,jenis_sidang) VALUES('$nim','$nip_p2','2','$id_pendaftar','aktif','sidang skripsi')");
                }
                if ($cek_p3 != NULL) {
                    $this->db->query("UPDATE tb_penguji SET nip='$nip_p3',`status`='aktif',jenis_sidang='sidang skripsi' WHERE nim='$nim' AND id_pendaftar='$id_pendaftar' AND sebagai='3'");
                } else {
                    $this->db->query("INSERT INTO tb_penguji (nim,nip,sebagai,id_pendaftar,`status`,jenis_sidang) VALUES('$nim','$nip_p3','3','$id_pendaftar','aktif','sidang skripsi')");
                }
            }
            session()->set('ses_id_jadwal', $id_jadwal);
            return redirect()->to('/data_pendaftar');
        } else {
            $idunit = $this->db->query("SELECT * FROM tb_dosen WHERE nip='" . session()->get('ses_id') . "'")->getResult()[0]->idunit;
            $idjurusan = $this->db->query("SELECT c.`idunit` AS idjurusan FROM tb_dosen a LEFT JOIN tb_unit b ON a.`idunit`=b.idunit LEFT JOIN tb_unit c ON b.`parentunit`=c.`idunit` WHERE a.nip='" . session()->get('ses_id') . "'")->getResult()[0]->idjurusan;
            $idFakultas = $this->db->query("SELECT parentunit FROM tb_unit WHERE idunit='" . $idjurusan . "'")->getResult()[0]->parentunit;
            $data = [
                'title' => 'Data Pendaftar Sidang',
                'db' => $this->db,
                'idunit' => $idunit,
                'id_jadwal' => $id_jadwal,
                'idjurusan' => $idjurusan,
                'data_dosen_fakultas' => $this->db->query("SELECT a.*, b.parentunit as 'jurusan', c.parentunit as fakultas FROM tb_dosen a join tb_unit b on a.idunit = b.idunit join tb_unit c on b.parentunit=c.idunit WHERE c.parentunit = '" . $idFakultas . "'")->getResult(),
                'data_dosen_f' => $this->db->query("SELECT * FROM tb_dosen a LEFT JOIN tb_unit b ON a.`idunit`=b.`idunit` LEFT JOIN tb_unit c ON b.`parentunit`=c.`idunit` WHERE c.`idunit`='$idjurusan'")->getResult(),
                'data_dosen_prodi' => $this->db->query("SELECT * from tb_dosen WHERE idunit = '" . $idunit . "'")->getResult(),
                'data_pendaftar' => $this->db->query("SELECT * FROM tb_pendaftar_sidang WHERE id_jadwal='$id_jadwal'")->getResult(),
                'data_jadwal' => $this->db->query("SELECT * FROM tb_jadwal_sidang WHERE id_jadwal='$id_jadwal'")->getResult(),
            ];
            session()->set('ses_id_jadwal', $id_jadwal);
            return view('Korprodi/data_pendaftar_sidang', $data);
        }
    }

    public function edit_data_pendaftar($nim, $id_pendaftar, $jenis_sidang, $waktu_sidang = null, $ruang_sidang = null)
    {
        if (session()->get('ses_id') == '' || session()->get('ses_login') != 'korprodi') {
            return redirect()->to('/');
        }

        $waktu_sidang = $waktu_sidang == 'kosong' ? '' : $waktu_sidang;
        $ruang_sidang = $ruang_sidang == 'kosong' ? '' : $ruang_sidang;

        $judul = $this->db->query("SELECT * FROM tb_pengajuan_topik WHERE nim='$nim'")->getResult();
        $penguji1 = $this->db->query("SELECT * FROM tb_penguji a left join tb_dosen b on a.nip=b.nip WHERE a.nim='$nim' AND a.sebagai='1' AND a.id_pendaftar='$id_pendaftar'")->getResult();
        $penguji2 = $this->db->query("SELECT * FROM tb_penguji a left join tb_dosen b on a.nip=b.nip WHERE a.nim='$nim' AND a.sebagai='2' AND a.id_pendaftar='$id_pendaftar'")->getResult();
        $penguji3 = $this->db->query("SELECT * FROM tb_penguji a left join tb_dosen b on a.nip=b.nip WHERE a.nim='$nim' AND a.sebagai='3' AND a.id_pendaftar='$id_pendaftar'")->getResult();
        $penguji_1 = '';
        $penguji_2 = '';
        $penguji_3 = '';
        if ($jenis_sidang == 'sidang skripsi') {
            $penguji1 = $this->db->query("SELECT * FROM tb_penguji a left join tb_dosen b on a.nip=b.nip WHERE a.nim='$nim' AND a.sebagai='1' AND a.`status`='aktif' AND jenis_sidang='sidang skripsi'")->getResult();
            $penguji2 = $this->db->query("SELECT * FROM tb_penguji a left join tb_dosen b on a.nip=b.nip WHERE a.nim='$nim' AND a.sebagai='2' AND a.`status`='aktif' AND jenis_sidang='sidang skripsi'")->getResult();
            $penguji3 = $this->db->query("SELECT * FROM tb_penguji a left join tb_dosen b on a.nip=b.nip WHERE a.nim='$nim' AND a.sebagai='3' AND a.`status`='aktif' AND jenis_sidang='sidang skripsi'")->getResult();
            if (empty($penguji1[0]->jenis_sidang)) {
                $penguji1 = $this->db->query("SELECT * FROM tb_penguji a left join tb_dosen b on a.nip=b.nip WHERE a.nim='$nim' AND a.sebagai='1' AND a.`status`='aktif'")->getResult();
            }
            if (empty($penguji2[0]->jenis_sidang)) {
                $penguji2 = $this->db->query("SELECT * FROM tb_penguji a left join tb_dosen b on a.nip=b.nip WHERE a.nim='$nim' AND a.sebagai='2' AND a.`status`='aktif'")->getResult();
            }
            if (empty($penguji3[0]->jenis_sidang)) {
                $penguji3 = $this->db->query("SELECT * FROM tb_penguji a left join tb_dosen b on a.nip=b.nip WHERE a.nim='$nim' AND a.sebagai='3' AND a.`status`='aktif'")->getResult();
            }
        }
        if ($penguji1 != NULL) {
            $penguji_1 = $penguji1[0]->nip;
        }
        if ($penguji2 != NULL) {
            $penguji_2 = $penguji2[0]->nip;
        }
        if ($penguji3 != NULL) {
            $penguji_3 = $penguji3[0]->nip;
        }



        $pem1 = $this->db->query("SELECT * FROM tb_pengajuan_pembimbing a left join tb_dosen b on a.nip=b.nip WHERE a.nim='$nim' AND a.sebagai='1' AND a.status_pengajuan='diterima'")->getResult();
        $pem2 = $this->db->query("SELECT * FROM tb_pengajuan_pembimbing a left join tb_dosen b on a.nip=b.nip WHERE a.nim='$nim' AND a.sebagai='2' AND a.status_pengajuan='diterima'")->getResult();

        $idunit = $this->db->query("SELECT * FROM tb_dosen WHERE nip='" . session()->get('ses_id') . "'")->getResult()[0]->idunit;
        $idjurusan = $this->db->query("SELECT c.`idunit` AS idjurusan FROM tb_dosen a LEFT JOIN tb_unit b ON a.`idunit`=b.idunit LEFT JOIN tb_unit c ON b.`parentunit`=c.`idunit` WHERE a.nip='" . session()->get('ses_id') . "'")->getResult()[0]->idjurusan;
        $idFakultas = $this->db->query("SELECT parentunit FROM tb_unit WHERE idunit='" . $idjurusan . "'")->getResult()[0]->parentunit;
        $data = [
            'title' => 'edit data pendaftar',
            'data_dosen_fakultas' => $this->db->query("SELECT a.*, b.parentunit as 'jurusan', c.parentunit as fakultas FROM tb_dosen a join tb_unit b on a.idunit = b.idunit join tb_unit c on b.parentunit=c.idunit WHERE c.parentunit = '" . $idFakultas . "'")->getResult(),
            'data_dosen_f' => $this->db->query("SELECT * FROM tb_dosen a LEFT JOIN tb_unit b ON a.`idunit`=b.`idunit` LEFT JOIN tb_unit c ON b.`parentunit`=c.`idunit` WHERE c.`idunit`='$idjurusan'")->getResult(),
            'data_dosen_prodi' => $this->db->query("SELECT * from tb_dosen WHERE idunit = '" . $idunit . "'")->getResult(),
            'id_pendaftar' => $id_pendaftar,
            'id_jadwal' => session()->get('ses_id_jadwal'),
            'data_jadwal' => $jenis_sidang,
            'idunit' => $idunit,
            'nim' => $nim,
            'pem1' => $pem1[0]->nip,
            'pem2' => $pem2[0]->nip,
            'penguji_1' => $penguji_1,
            'penguji_2' => $penguji_2,
            'penguji_3' => $penguji_3,
            'waktu_sidang' => $waktu_sidang,
            'ruang_sidang' => $ruang_sidang,
            'jenis_sidang' => $jenis_sidang,
            'db' => $this->db
        ];

        return view('Korprodi/edit_data_pendaftar', $data);
    }
    public function direct_cetak_pendaftar()
    {
        if (session()->get('ses_id') == '' || session()->get('ses_login') == 'mahasiswa') {
            return redirect()->to('/');
        }
        $idunit = session()->get('ses_idunit');
        $id_jadwal = $this->request->getPost('id_jadwal');
        $date1 = $this->request->getPost('start');
        $date2 = $this->request->getPost('end');
        $jenis_file = $this->request->getPost('jenis_file');
        if ($date1 == '') {
            $date1 = 'semua';
        }
        if ($date2 == '') {
            $date2 = 'semua';
        }
        if ($jenis_file == 'pdf') {
            if ($date1 == 'semua') {
                if ($date2 == 'semua') {
                    return redirect()->to("cetak_pendaftar/$id_jadwal/semua/semua");
                } else {
                    session()->setFlashdata('message', '<div class="alert alert-danger alert-dismissible fade show mb-0" role="alert">
            <span class="alert-inner--icon"><i class="fe fe-slash"></i></span>
            <span class="alert-inner--text"><strong>Gagal!</strong> Tentukan tanggal mulai & akhir.</span>
            <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>');
                    return redirect()->back()->withInput();
                }
            } else {
                if ($date2 == 'semua') {
                    session()->setFlashdata('message', '<div class="alert alert-danger alert-dismissible fade show mb-0" role="alert">
            <span class="alert-inner--icon"><i class="fe fe-slash"></i></span>
            <span class="alert-inner--text"><strong>Gagal!</strong> Tentukan tanggal mulai & akhir.</span>
            <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>');
                    return redirect()->back()->withInput();
                } else {
                    return redirect()->to("cetak_pendaftar/$id_jadwal/$date1/$date2");
                }
            }
        } else {
            // return redirect()->to("hasil_dosen_excel/$idunit/$date1/$date2");
            return "EXCEL";
        }
    }
}
