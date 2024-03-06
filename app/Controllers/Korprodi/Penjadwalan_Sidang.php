<?php

namespace App\Controllers\Korprodi;

use App\Controllers\BaseController;

use App\Libraries\Access_API; // Import library

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
            $jenis_sidang = $this->request->getPost('jenis_sidang');
            $cek_p1 = $this->db->query("SELECT * FROM tb_penguji WHERE id_pendaftar='$id_pendaftar' AND sebagai='1'")->getResult();
            $cek_p2 = $this->db->query("SELECT * FROM tb_penguji WHERE id_pendaftar='$id_pendaftar' AND sebagai='2'")->getResult();
            $cek_p3 = $this->db->query("SELECT * FROM tb_penguji WHERE id_pendaftar='$id_pendaftar' AND sebagai='3'")->getResult();
            $this->db->query("UPDATE tb_pendaftar_sidang SET waktu_sidang='$waktu_sidang',ruang_sidang='$ruang_sidang' WHERE id_pendaftar='$id_pendaftar'");
            // if ($jenis_sidang == 'seminar proposal' || $jenis_sidang == 'sidang skripsi') {
            if ($jenis_sidang == 'seminar proposal') {
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
