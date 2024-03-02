<?php

namespace App\Controllers;

use App\Controllers\BaseController;

use App\Libraries\Access_API; // Import library
use App\Libraries\QRcodelib; // Import library
use CodeIgniter\Database\Query;
use Dompdf\Dompdf;
use Dompdf\Options;

class Cetak extends BaseController
{
    public function __construct()
    {
        $this->api = new Access_API();
        $this->qr = new QRcodelib();
        $this->db = \Config\Database::connect();
    }
    public function berkas_mhs_proposal()
    {
        if (session()->get('ses_id') == '' || session()->get('ses_login') != 'mahasiswa') {
            return redirect()->to('/');
        }
        $id = session()->get('ses_id');
        $data = [
            'title' => 'Berkas Proposal',
            'db' => $this->db,
            'dosen_pembimbing_1' => $this->db->query("SELECT * FROM tb_pengajuan_pembimbing a LEFT JOIN tb_dosen b ON a.`nip`=b.`nip` LEFT JOIN tb_profil_tambahan c ON a.`nip`=c.`id` WHERE a.nim='" . $id . "' AND a.status_pengajuan='diterima' AND sebagai='1'")->getResult(),
            'dosen_pembimbing_2' => $this->db->query("SELECT * FROM tb_pengajuan_pembimbing a LEFT JOIN tb_dosen b ON a.`nip`=b.`nip` LEFT JOIN tb_profil_tambahan c ON a.`nip`=c.`id` WHERE a.nim='" . $id . "' AND a.status_pengajuan='diterima' AND sebagai='2'")->getResult(),
            'penguji_1' => $this->db->query("SELECT * from tb_penguji a LEFT JOIN tb_dosen b ON a.`nip`=b.`nip` LEFT JOIN tb_profil_tambahan c ON a.`nip`=c.`id` where a.nim='" . session()->get('ses_id') . "' AND a.`status`='aktif' AND a.sebagai='1'")->getResult(),
            'penguji_2' => $this->db->query("SELECT * from tb_penguji a LEFT JOIN tb_dosen b ON a.`nip`=b.`nip` LEFT JOIN tb_profil_tambahan c ON a.`nip`=c.`id` where a.nim='" . session()->get('ses_id') . "' AND a.`status`='aktif' AND a.sebagai='2'")->getResult(),
            'penguji_3' => $this->db->query("SELECT * from tb_penguji a LEFT JOIN tb_dosen b ON a.`nip`=b.`nip` LEFT JOIN tb_profil_tambahan c ON a.`nip`=c.`id` where a.nim='" . session()->get('ses_id') . "' AND a.`status`='aktif' AND a.sebagai='3'")->getResult(),
        ];
        return view('Mahasiswa/Proposal/berkas_proposal', $data);
    }
    public function berkas_mhs_skripsi()
    {
        if (session()->get('ses_id') == '' || session()->get('ses_login') != 'mahasiswa') {
            return redirect()->to('/');
        }
        $id = session()->get('ses_id');
        $data = [
            'title' => 'Berkas Skripsi',
            'db' => $this->db,
            'dosen_pembimbing_1' => $this->db->query("SELECT * FROM tb_pengajuan_pembimbing a LEFT JOIN tb_dosen b ON a.`nip`=b.`nip` LEFT JOIN tb_profil_tambahan c ON a.`nip`=c.`id` WHERE a.nim='" . $id . "' AND a.status_pengajuan='diterima' AND sebagai='1'")->getResult(),
            'dosen_pembimbing_2' => $this->db->query("SELECT * FROM tb_pengajuan_pembimbing a LEFT JOIN tb_dosen b ON a.`nip`=b.`nip` LEFT JOIN tb_profil_tambahan c ON a.`nip`=c.`id` WHERE a.nim='" . $id . "' AND a.status_pengajuan='diterima' AND sebagai='2'")->getResult(),
            'penguji_1' => $this->db->query("SELECT * from tb_penguji a LEFT JOIN tb_dosen b ON a.`nip`=b.`nip` LEFT JOIN tb_profil_tambahan c ON a.`nip`=c.`id` where a.nim='" . session()->get('ses_id') . "' AND a.`status`='aktif' AND a.sebagai='1'")->getResult(),
            'penguji_2' => $this->db->query("SELECT * from tb_penguji a LEFT JOIN tb_dosen b ON a.`nip`=b.`nip` LEFT JOIN tb_profil_tambahan c ON a.`nip`=c.`id` where a.nim='" . session()->get('ses_id') . "' AND a.`status`='aktif' AND a.sebagai='2'")->getResult(),
            'penguji_3' => $this->db->query("SELECT * from tb_penguji a LEFT JOIN tb_dosen b ON a.`nip`=b.`nip` LEFT JOIN tb_profil_tambahan c ON a.`nip`=c.`id` where a.nim='" . session()->get('ses_id') . "' AND a.`status`='aktif' AND a.sebagai='3'")->getResult(),
        ];
        return view('Mahasiswa/Skripsi/berkas_sidang', $data);
    }
    public function form_bimbingan_proposal($id, $id_pembimbing)
    {
        // if (session()->get('ses_id') == '') {
        //     return redirect()->to('/');
        // }
        if ($id == '') {
            $id = session()->get('ses_id');
        }
        $link = base_url() . "form_bimbingan_proposal/$id/$id_pembimbing";
        $nama_pembimbing = $this->db->query("SELECT * FROM tb_dosen WHERE nip='$id_pembimbing'")->getResult()[0];
        $disetujui_pada = $this->db->query("SELECT * FROM tb_perizinan_sidang WHERE nip='$id_pembimbing' AND nim='$id' AND jenis_sidang='seminar proposal'")->getResult();
        // ------------------------------------------------------------------
        $data = ['qr' => ''];
        if (!empty($disetujui_pada)) {
            if ($disetujui_pada[0]->status == 'disetujui') {
                $isi = "Disetujui Pada : " . $disetujui_pada[0]->acc_at . " Oleh " . $disetujui_pada[0]->izin_sebagai . " (" . $nama_pembimbing->gelardepan . ' ' . $nama_pembimbing->nama . ', ' . $nama_pembimbing->gelarbelakang . ")";
                $qr = $this->qr->cetakqr($isi);
            } else {
                $qr = '<br>(BELUM DITANDA TANGANI)<br>';
            }
        } else {
            $qr = '<br>(BELUM DITANDA TANGANI)<br>';
        }
        $qr_link = $this->qr->cetakqr($link);
        // ------------------------------------------------------------------
        $data = [
            'title' => 'Form Bimbingan Proposal',
            'baseurl' => base_url(),
            'judul_skripsi' => $this->db->query("SELECT * FROM tb_pengajuan_topik WHERE nim='$id'")->getResult()[0]->judul_topik,
            'nim' => $id,
            'nama' => $this->db->query("SELECT * FROM tb_mahasiswa WHERE nim='$id'")->getResult()[0]->nama,
            'pembimbing' => $this->db->query("SELECT * FROM tb_pengajuan_pembimbing WHERE nip='$id_pembimbing' AND nim='$id' AND status_pengajuan='diterima'")->getResult()[0]->sebagai,
            'nama_pembimbing' => $nama_pembimbing,
            'nip' => $id_pembimbing,
            'data' => $this->db->query("SELECT * FROM tb_bimbingan WHERE `to`='$id' AND `from`='$id_pembimbing' AND pokok_bimbingan!=''  AND kategori_bimbingan='1'")->getResult(),
            'qr' => $qr,
            'qr_link' => $qr_link,
            'db' => $this->db,
        ];
        $dompdf = new Dompdf();
        $filename = date('y-m-d-H-i-s');
        $dompdf->loadHtml(view('Cetak/form_bimbingan_proposal', $data));
        $dompdf->setPaper('A4', 'potrait');
        $dompdf->render();
        $dompdf->stream($filename, array('Attachment' => false));
        exit();
    }
    public function form_bimbingan_skripsi($id, $id_pembimbing)
    {
        // if (session()->get('ses_id') == '') {
        //     return redirect()->to('/');
        // }
        if ($id == '') {
            $id = session()->get('ses_id');
        }
        $link = base_url() . "form_bimbingan_skripsi/$id/$id_pembimbing";
        $nama_pembimbing = $this->db->query("SELECT * FROM tb_dosen WHERE nip='$id_pembimbing'")->getResult()[0];
        $disetujui_pada = $this->db->query("SELECT * FROM tb_perizinan_sidang WHERE nip='$id_pembimbing' AND nim='$id' AND jenis_sidang='skripsi'")->getResult();
        // ------------------------------------------------------------------
        if (!empty($disetujui_pada)) {
            if ($disetujui_pada[0]->status == 'disetujui') {
                $isi = "Disetujui Pada : " . $disetujui_pada[0]->acc_at . " Oleh " . $disetujui_pada[0]->izin_sebagai . " (" . $nama_pembimbing->gelardepan . ' ' . $nama_pembimbing->nama . ', ' . $nama_pembimbing->gelarbelakang . ")";
                $qr = $this->qr->cetakqr($isi);
            } else {
                $qr = '<br>(BELUM DITANDA TANGANI)<br>';
            }
        } else {
            $qr = '<br>(BELUM DITANDA TANGANI)<br>';
        }
        $qr_link = $this->qr->cetakqr($link);
        // ------------------------------------------------------------------
        $data = [
            'title' => 'Form Bimbingan Skripsi',
            'baseurl' => base_url(),
            'judul_skripsi' => $this->db->query("SELECT * FROM tb_pengajuan_topik WHERE nim='$id'")->getResult()[0]->judul_topik,
            'nim' => $id,
            'nama' => $this->db->query("SELECT * FROM tb_mahasiswa WHERE nim='$id'")->getResult()[0]->nama,
            'pembimbing' => $this->db->query("SELECT * FROM tb_pengajuan_pembimbing WHERE nip='$id_pembimbing' AND nim='$id' AND status_pengajuan='diterima'")->getResult()[0]->sebagai,
            'nama_pembimbing' => $nama_pembimbing,
            'nip' => $id_pembimbing,
            'data' => $this->db->query("SELECT * FROM tb_bimbingan WHERE `to`='$id' AND `from`='$id_pembimbing' AND pokok_bimbingan!='' AND kategori_bimbingan='3'")->getResult(),
            'qr' => $qr,
            'qr_link' => $qr_link,
            'db' => $this->db,
        ];
        $dompdf = new Dompdf();
        $filename = date('y-m-d-H-i-s');
        $dompdf->loadHtml(view('Cetak/form_bimbingan_skripsi', $data));
        $dompdf->setPaper('A4', 'potrait');
        $dompdf->render();
        $dompdf->stream($filename, array('Attachment' => false));
        exit();
    }
    public function berita_acara_proposal($id)
    {
        // if (session()->get('ses_id') == '') {
        //     return redirect()->to('/');
        // }
        if ($id == '') {
            $id = session()->get('ses_id');
        }
        // --------------------------------------------------------------------
        $bc_pembimbing_1 = $this->db->query("SELECT * FROM tb_berita_acara WHERE jenis_sidang='proposal' and nim='$id' and sebagai='pembimbing 1'")->getResult();
        if (!empty($bc_pembimbing_1)) {
            $bc_pembimbing_1nip = $bc_pembimbing_1[0]->nip;
            $data_pembimbing_1 = $this->db->query("SELECT * FROM tb_dosen WHERE nip='" . $bc_pembimbing_1nip . "'")->getResult()[0];
            if ($bc_pembimbing_1[0]->status == 'ditandatangani') {
                $isi = "Disetujui Pada : " . $bc_pembimbing_1[0]->create_at . " Oleh " . $bc_pembimbing_1[0]->sebagai . " (" . $data_pembimbing_1->gelardepan . ' ' . $data_pembimbing_1->nama . ', ' . $data_pembimbing_1->gelarbelakang . ")";
                $qr_pembimbing_1 = $this->qr->cetakqr($isi);
            } else {
                $qr_pembimbing_1 = '<br>(BELUM DITANDA TANGANI)<br>';
            }
        } else {
            $qr_pembimbing_1 = '<br>(BELUM DITANDA TANGANI)<br>';
        }

        $bc_pembimbing_2 = $this->db->query("SELECT * FROM tb_berita_acara WHERE jenis_sidang='proposal' and nim='$id' and sebagai='pembimbing 2'")->getResult();
        if (!empty($bc_pembimbing_2)) {
            $bc_pembimbing_2nip = $bc_pembimbing_2[0]->nip;
            $data_pembimbing_2 = $this->db->query("SELECT * FROM tb_dosen WHERE nip='" . $bc_pembimbing_2nip . "'")->getResult()[0];
            if ($bc_pembimbing_2[0]->status == 'ditandatangani') {
                $isi = "Disetujui Pada : " . $bc_pembimbing_2[0]->create_at . " Oleh " . $bc_pembimbing_2[0]->sebagai . " (" . $data_pembimbing_2->gelardepan . ' ' . $data_pembimbing_2->nama . ', ' . $data_pembimbing_2->gelarbelakang . ")";
                $qr_pembimbing_2 = $this->qr->cetakqr($isi);
            } else {
                $qr_pembimbing_2 = '<br>(BELUM DITANDA TANGANI)<br>';
            }
        } else {
            $qr_pembimbing_2 = '<br>(BELUM DITANDA TANGANI)<br>';
        }

        $bc_penguji_1 = $this->db->query("SELECT * FROM tb_berita_acara WHERE jenis_sidang='proposal' and nim='$id' and sebagai='penguji 1'")->getResult();
        if (!empty($bc_penguji_1)) {
            $bc_penguji_1nip = $bc_penguji_1[0]->nip;
            $data_penguji_1 = $this->db->query("SELECT * FROM tb_dosen WHERE nip='" . $bc_penguji_1nip . "'")->getResult()[0];
            if ($bc_penguji_1[0]->status == 'ditandatangani') {
                $isi = "Disetujui Pada : " . $bc_penguji_1[0]->create_at . " Oleh " . $bc_penguji_1[0]->sebagai . " (" . $data_penguji_1->gelardepan . ' ' . $data_penguji_1->nama . ', ' . $data_penguji_1->gelarbelakang . ")";
                $qr_penguji_1 = $this->qr->cetakqr($isi);
            } else {
                $qr_penguji_1 = '<br>(BELUM DITANDA TANGANI)<br>';
            }
        } else {
            $qr_penguji_1 = '<br>(BELUM DITANDA TANGANI)<br>';
        }

        $bc_penguji_2 = $this->db->query("SELECT * FROM tb_berita_acara WHERE jenis_sidang='proposal' and nim='$id' and sebagai='penguji 2'")->getResult();
        if (!empty($bc_penguji_2)) {
            $bc_penguji_2nip = $bc_penguji_2[0]->nip;
            $data_penguji_2 = $this->db->query("SELECT * FROM tb_dosen WHERE nip='" . $bc_penguji_2nip . "'")->getResult()[0];
            if ($bc_penguji_2[0]->status == 'ditandatangani') {
                $isi = "Disetujui Pada : " . $bc_penguji_2[0]->create_at . " Oleh " . $bc_penguji_2[0]->sebagai . " (" . $data_penguji_2->gelardepan . ' ' . $data_penguji_2->nama . ', ' . $data_penguji_2->gelarbelakang . ")";
                $qr_penguji_2 = $this->qr->cetakqr($isi);
            } else {
                $qr_penguji_2 = '<br>(BELUM DITANDA TANGANI)<br>';
            }
        } else {
            $qr_penguji_2 = '<br>(BELUM DITANDA TANGANI)<br>';
        }

        $bc_penguji_3 = $this->db->query("SELECT * FROM tb_berita_acara WHERE jenis_sidang='proposal' and nim='$id' and sebagai='penguji 3'")->getResult();
        if (!empty($bc_penguji_3)) {
            $bc_penguji_3nip = $bc_penguji_3[0]->nip;
            $data_penguji_3 = $this->db->query("SELECT * FROM tb_dosen WHERE nip='" . $bc_penguji_3nip . "'")->getResult()[0];
            if ($bc_penguji_3[0]->status == 'ditandatangani') {
                $isi = "Disetujui Pada : " . $bc_penguji_3[0]->create_at . " Oleh " . $bc_penguji_3[0]->sebagai . " (" . $data_penguji_3->gelardepan . ' ' . $data_penguji_3->nama . ', ' . $data_penguji_3->gelarbelakang . ")";
                $qr_penguji_3 = $this->qr->cetakqr($isi);
            } else {
                $qr_penguji_3 = '<br>(BELUM DITANDA TANGANI)<br>';
            }
        } else {
            $qr_penguji_3 = '<br>(BELUM DITANDA TANGANI)<br>';
        }
        // ------------------------------------------------------------------

        $link = base_url() . "berita_acara_proposal/$id";
        $qr_link = $this->qr->cetakqr($link);
        $prodi = $this->db->query("SELECT * FROM tb_unit WHERE idunit='" . session()->get('ses_idunit') . "'")->getResult();
        $jurusan = $this->db->query("SELECT * FROM tb_unit WHERE idunit='" . $prodi[0]->parentunit . "'")->getResult();
        $fakultas = $this->db->query("SELECT * FROM tb_unit WHERE idunit='" . $jurusan[0]->parentunit . "'")->getResult();
        $data = [
            'title' => 'Berita Acara Seminar Proposal',
            'baseurl' => base_url(),
            'judul_skripsi' => $this->db->query("SELECT * FROM tb_pengajuan_topik WHERE nim='$id'")->getResult()[0]->judul_topik,
            'nim' => $id,
            'nama' => $this->db->query("SELECT * FROM tb_mahasiswa WHERE nim='$id'")->getResult()[0]->nama,
            'dosen_pembimbing_1' => $this->db->query("SELECT * FROM tb_pengajuan_pembimbing a LEFT JOIN tb_dosen b ON a.`nip`=b.`nip` LEFT JOIN tb_profil_tambahan c ON a.`nip`=c.`id` WHERE a.nim='" . $id . "' AND a.status_pengajuan='diterima' AND sebagai='1'")->getResult(),
            'dosen_pembimbing_2' => $this->db->query("SELECT * FROM tb_pengajuan_pembimbing a LEFT JOIN tb_dosen b ON a.`nip`=b.`nip` LEFT JOIN tb_profil_tambahan c ON a.`nip`=c.`id` WHERE a.nim='" . $id . "' AND a.status_pengajuan='diterima' AND sebagai='2'")->getResult(),
            'penguji_1' => $this->db->query("SELECT * from tb_penguji a LEFT JOIN tb_dosen b ON a.`nip`=b.`nip` LEFT JOIN tb_profil_tambahan c ON a.`nip`=c.`id` where a.nim='" . session()->get('ses_id') . "' AND a.`status`='aktif' AND a.sebagai='1'")->getResult(),
            'penguji_2' => $this->db->query("SELECT * from tb_penguji a LEFT JOIN tb_dosen b ON a.`nip`=b.`nip` LEFT JOIN tb_profil_tambahan c ON a.`nip`=c.`id` where a.nim='" . session()->get('ses_id') . "' AND a.`status`='aktif' AND a.sebagai='2'")->getResult(),
            'penguji_3' => $this->db->query("SELECT * from tb_penguji a LEFT JOIN tb_dosen b ON a.`nip`=b.`nip` LEFT JOIN tb_profil_tambahan c ON a.`nip`=c.`id` where a.nim='" . session()->get('ses_id') . "' AND a.`status`='aktif' AND a.sebagai='3'")->getResult(),
            'qr_pembimbing_1' => $qr_pembimbing_1,
            'qr_pembimbing_2' => $qr_pembimbing_2,
            'qr_penguji_1' => $qr_penguji_1,
            'qr_penguji_2' => $qr_penguji_2,
            'qr_penguji_3' => $qr_penguji_3,
            'qr_link' => $qr_link,
            'db' => $this->db,
            'nm_prodi' => $prodi[0]->namaunit,
            'nm_jurusan' => $jurusan[0]->namaunit,
            'nm_fakultas' => $fakultas[0]->namaunit,
        ];
        $dompdf = new Dompdf();
        $filename = date('y-m-d-H-i-s');
        $dompdf->loadHtml(view('Cetak/berita_acara_proposal', $data));
        $dompdf->setPaper('A4', 'potrait');
        $dompdf->render();
        $dompdf->stream($filename, array('Attachment' => false));
        exit();
    }
    public function berita_acara_skripsi($id)
    {
        // if (session()->get('ses_id') == '') {
        //     return redirect()->to('/');
        // }
        if ($id == '') {
            $id = session()->get('ses_id');
        }
        // --------------------------------------------------------------------
        $bc_pembimbing_1 = $this->db->query("SELECT * FROM tb_berita_acara WHERE jenis_sidang='skripsi' and nim='$id' and sebagai='pembimbing 1'")->getResult();
        if (!empty($bc_pembimbing_1)) {
            $bc_pembimbing_1nip = $bc_pembimbing_1[0]->nip;
            $data_pembimbing_1 = $this->db->query("SELECT * FROM tb_dosen WHERE nip='" . $bc_pembimbing_1nip . "'")->getResult()[0];
            if ($bc_pembimbing_1[0]->status == 'ditandatangani') {
                $isi = "Disetujui Pada : " . $bc_pembimbing_1[0]->create_at . " Oleh " . $bc_pembimbing_1[0]->sebagai . " (" . $data_pembimbing_1->gelardepan . ' ' . $data_pembimbing_1->nama . ', ' . $data_pembimbing_1->gelarbelakang . ")";
                $qr_pembimbing_1 = $this->qr->cetakqr($isi);
            } else {
                $qr_pembimbing_1 = '<br>(BELUM DITANDA TANGANI)<br>';
            }
        } else {
            $qr_pembimbing_1 = '<br>(BELUM DITANDA TANGANI)<br>';
        }

        $bc_pembimbing_2 = $this->db->query("SELECT * FROM tb_berita_acara WHERE jenis_sidang='skripsi' and nim='$id' and sebagai='pembimbing 2'")->getResult();
        if (!empty($bc_pembimbing_2)) {
            $bc_pembimbing_2nip = $bc_pembimbing_2[0]->nip;
            $data_pembimbing_2 = $this->db->query("SELECT * FROM tb_dosen WHERE nip='" . $bc_pembimbing_2nip . "'")->getResult()[0];
            if ($bc_pembimbing_2[0]->status == 'ditandatangani') {
                $isi = "Disetujui Pada : " . $bc_pembimbing_2[0]->create_at . " Oleh " . $bc_pembimbing_2[0]->sebagai . " (" . $data_pembimbing_2->gelardepan . ' ' . $data_pembimbing_2->nama . ', ' . $data_pembimbing_2->gelarbelakang . ")";
                $qr_pembimbing_2 = $this->qr->cetakqr($isi);
            } else {
                $qr_pembimbing_2 = '<br>(BELUM DITANDA TANGANI)<br>';
            }
        } else {
            $qr_pembimbing_2 = '<br>(BELUM DITANDA TANGANI)<br>';
        }

        $bc_penguji_1 = $this->db->query("SELECT * FROM tb_berita_acara WHERE jenis_sidang='skripsi' and nim='$id' and sebagai='penguji 1'")->getResult();
        if (!empty($bc_penguji_1)) {
            $bc_penguji_1nip = $bc_penguji_1[0]->nip;
            $data_penguji_1 = $this->db->query("SELECT * FROM tb_dosen WHERE nip='" . $bc_penguji_1nip . "'")->getResult()[0];
            if ($bc_penguji_1[0]->status == 'ditandatangani') {
                $isi = "Disetujui Pada : " . $bc_penguji_1[0]->create_at . " Oleh " . $bc_penguji_1[0]->sebagai . " (" . $data_penguji_1->gelardepan . ' ' . $data_penguji_1->nama . ', ' . $data_penguji_1->gelarbelakang . ")";
                $qr_penguji_1 = $this->qr->cetakqr($isi);
            } else {
                $qr_penguji_1 = '<br>(BELUM DITANDA TANGANI)<br>';
            }
        } else {
            $qr_penguji_1 = '<br>(BELUM DITANDA TANGANI)<br>';
        }

        $bc_penguji_2 = $this->db->query("SELECT * FROM tb_berita_acara WHERE jenis_sidang='skripsi' and nim='$id' and sebagai='penguji 2'")->getResult();
        if (!empty($bc_penguji_2)) {
            $bc_penguji_2nip = $bc_penguji_2[0]->nip;
            $data_penguji_2 = $this->db->query("SELECT * FROM tb_dosen WHERE nip='" . $bc_penguji_2nip . "'")->getResult()[0];
            if ($bc_penguji_2[0]->status == 'ditandatangani') {
                $isi = "Disetujui Pada : " . $bc_penguji_2[0]->create_at . " Oleh " . $bc_penguji_2[0]->sebagai . " (" . $data_penguji_2->gelardepan . ' ' . $data_penguji_2->nama . ', ' . $data_penguji_2->gelarbelakang . ")";
                $qr_penguji_2 = $this->qr->cetakqr($isi);
            } else {
                $qr_penguji_2 = '<br>(BELUM DITANDA TANGANI)<br>';
            }
        } else {
            $qr_penguji_2 = '<br>(BELUM DITANDA TANGANI)<br>';
        }

        $bc_penguji_3 = $this->db->query("SELECT * FROM tb_berita_acara WHERE jenis_sidang='skripsi' and nim='$id' and sebagai='penguji 3'")->getResult();
        if (!empty($bc_penguji_3)) {
            $bc_penguji_3nip = $bc_penguji_3[0]->nip;
            $data_penguji_3 = $this->db->query("SELECT * FROM tb_dosen WHERE nip='" . $bc_penguji_3nip . "'")->getResult()[0];
            if ($bc_penguji_3[0]->status == 'ditandatangani') {
                $isi = "Disetujui Pada : " . $bc_penguji_3[0]->create_at . " Oleh " . $bc_penguji_3[0]->sebagai . " (" . $data_penguji_3->gelardepan . ' ' . $data_penguji_3->nama . ', ' . $data_penguji_3->gelarbelakang . ")";
                $qr_penguji_3 = $this->qr->cetakqr($isi);
            } else {
                $qr_penguji_3 = '<br>(BELUM DITANDA TANGANI)<br>';
            }
        } else {
            $qr_penguji_3 = '<br>(BELUM DITANDA TANGANI)<br>';
        }
        // ------------------------------------------------------------------
        $link = base_url() . "berita_acara_skripsi/$id";
        $qr_link = $this->qr->cetakqr($link);
        $prodi = $this->db->query("SELECT * FROM tb_unit WHERE idunit='" . session()->get('ses_idunit') . "'")->getResult();
        $jurusan = $this->db->query("SELECT * FROM tb_unit WHERE idunit='" . $prodi[0]->parentunit . "'")->getResult();
        $fakultas = $this->db->query("SELECT * FROM tb_unit WHERE idunit='" . $jurusan[0]->parentunit . "'")->getResult();
        $data = [
            'title' => 'Berita Acara Sidang Skripsi',
            'baseurl' => base_url(),
            'judul_skripsi' => $this->db->query("SELECT * FROM tb_pengajuan_topik WHERE nim='$id'")->getResult()[0]->judul_topik,
            'nim' => $id,
            'nama' => $this->db->query("SELECT * FROM tb_mahasiswa WHERE nim='$id'")->getResult()[0]->nama,
            'dosen_pembimbing_1' => $this->db->query("SELECT * FROM tb_pengajuan_pembimbing a LEFT JOIN tb_dosen b ON a.`nip`=b.`nip` LEFT JOIN tb_profil_tambahan c ON a.`nip`=c.`id` WHERE a.nim='" . $id . "' AND a.status_pengajuan='diterima' AND sebagai='1'")->getResult(),
            'dosen_pembimbing_2' => $this->db->query("SELECT * FROM tb_pengajuan_pembimbing a LEFT JOIN tb_dosen b ON a.`nip`=b.`nip` LEFT JOIN tb_profil_tambahan c ON a.`nip`=c.`id` WHERE a.nim='" . $id . "' AND a.status_pengajuan='diterima' AND sebagai='2'")->getResult(),
            'penguji_1' => $this->db->query("SELECT * from tb_penguji a LEFT JOIN tb_dosen b ON a.`nip`=b.`nip` LEFT JOIN tb_profil_tambahan c ON a.`nip`=c.`id` where a.nim='" . session()->get('ses_id') . "' AND a.`status`='aktif' AND a.sebagai='1'")->getResult(),
            'penguji_2' => $this->db->query("SELECT * from tb_penguji a LEFT JOIN tb_dosen b ON a.`nip`=b.`nip` LEFT JOIN tb_profil_tambahan c ON a.`nip`=c.`id` where a.nim='" . session()->get('ses_id') . "' AND a.`status`='aktif' AND a.sebagai='2'")->getResult(),
            'penguji_3' => $this->db->query("SELECT * from tb_penguji a LEFT JOIN tb_dosen b ON a.`nip`=b.`nip` LEFT JOIN tb_profil_tambahan c ON a.`nip`=c.`id` where a.nim='" . session()->get('ses_id') . "' AND a.`status`='aktif' AND a.sebagai='3'")->getResult(),
            'qr_pembimbing_1' => $qr_pembimbing_1,
            'qr_pembimbing_2' => $qr_pembimbing_2,
            'qr_penguji_1' => $qr_penguji_1,
            'qr_penguji_2' => $qr_penguji_2,
            'qr_penguji_3' => $qr_penguji_3,
            'qr_link' => $qr_link,
            'db' => $this->db,
            'nm_prodi' => $prodi[0]->namaunit,
            'nm_jurusan' => $jurusan[0]->namaunit,
            'nm_fakultas' => $fakultas[0]->namaunit,
        ];
        $dompdf = new Dompdf();
        $filename = date('y-m-d-H-i-s');
        $dompdf->loadHtml(view('Cetak/berita_acara_skripsi', $data));
        $dompdf->setPaper('A4', 'potrait');
        $dompdf->render();
        $dompdf->stream($filename, array('Attachment' => false));
        exit();
    }

    public static function _grade($db, $id)
    {
        $pembimbing1 = $db->query("SELECT * FROM tb_nilai WHERE nim = '" . $id . "' AND sebagai='pembimbing 1'")->getResult();
        $pembimbing2 = $db->query("SELECT * FROM tb_nilai WHERE nim = '" . $id . "' AND sebagai='pembimbing 2'")->getResult();
        $penguji1 = $db->query("SELECT * FROM tb_nilai WHERE nim = '" . $id . "' AND sebagai='penguji 1'")->getResult();
        $penguji2 = $db->query("SELECT * FROM tb_nilai WHERE nim = '" . $id . "' AND sebagai='penguji 2'")->getResult();
        $penguji3 = $db->query("SELECT * FROM tb_nilai WHERE nim = '" . $id . "' AND sebagai='penguji 3'")->getResult();
        if (!empty($pembimbing1)) {
            $nb_pembimbing1 = $pembimbing1[0]->nilai_bimbingan == NULL ? 0 : $pembimbing1[0]->nilai_bimbingan;
            $ns_pembimbing1 = $pembimbing1[0]->nilai_ujian == NULL ? 0 : $pembimbing1[0]->nilai_ujian;
        } else {
            $nb_pembimbing1 = 0;
            $ns_pembimbing1 = 0;
        }
        if (!empty($pembimbing2)) {
            $nb_pembimbing2 = $pembimbing2[0]->nilai_bimbingan == NULL ? 0 : $pembimbing2[0]->nilai_bimbingan;
            $ns_pembimbing2 = $pembimbing2[0]->nilai_ujian == NULL ? 0 : $pembimbing2[0]->nilai_ujian;
        } else {
            $nb_pembimbing2 = 0;
            $ns_pembimbing2 = 0;
        }
        if (!empty($penguji1)) {
            $ns_penguji1 = $penguji1[0]->nilai_ujian == NULL ? 0 : $penguji1[0]->nilai_ujian;
        } else {
            $ns_penguji1 = 0;
        }
        if (!empty($penguji2)) {
            $ns_penguji2 = $penguji2[0]->nilai_ujian == NULL ? 0 : $penguji2[0]->nilai_ujian;
        } else {
            $ns_penguji2 = 0;
        }
        if (!empty($penguji3)) {
            $ns_penguji3 = $penguji3[0]->nilai_ujian == NULL ? 0 : $penguji3[0]->nilai_ujian;
        } else {
            $ns_penguji3 = 0;
        }
        $nb = (($nb_pembimbing1 + $nb_pembimbing2) / 2) * (60 / 100);
        $ns = (($ns_pembimbing1 + $ns_pembimbing2 + $ns_penguji1 + $ns_penguji2 + $ns_penguji3) / 5) * (40 / 100);
        $total = $nb + $ns;
        $grade = "E";
        $sidang = $db->query("SELECT * FROM tb_pendaftar_sidang a LEFT JOIN tb_jadwal_sidang b ON a.`id_jadwal`=b.`id_jadwal` WHERE a.`nim`='" . $id . "' AND b.`jenis_sidang`='sidang skripsi' ORDER BY create_at DESC LIMIT 1")->getResult();
        if (!empty($sidang)) {
            if ($total >= 80) {
                $grade = "A";
            } elseif ($total >= 75 && $total < 80) {
                $grade = "B+";
            } elseif ($total >= 70 && $total < 75) {
                $grade = "B";
            } elseif ($total >= 65 && $total < 70) {
                $grade = "C+";
            } elseif ($total >= 60 && $total < 65) {
                $grade = "C";
            } elseif ($total >= 55 && $total < 60) {
                $grade = "D+";
            } elseif ($total >= 50 && $total < 55) {
                $grade = "D";
            } else {
                $grade = "E";
            }
        };

        return $grade;
    }
    public function nilai_akhir_skripsi($id, $idunit)
    {
        // if (session()->get('ses_id') == '') {
        //     return redirect()->to('/');
        // }
        if ($id == '') {
            $id = session()->get('ses_id');
        }

        $cek_id = $this->db->query("SELECT nim FROM tb_mahasiswa where nim = '" . $id . "' and idunit='" . $idunit . "'")->getResult();
        if (empty($cek_id)) {
            return redirect()->to('/');
        }

        // --------------------------------------------------------------------
        $bc_pembimbing_1 = $this->db->query("SELECT * FROM tb_berita_acara WHERE jenis_sidang='skripsi' and nim='$id' and sebagai='pembimbing 1'")->getResult();
        if (!empty($bc_pembimbing_1)) {
            $bc_pembimbing_1nip = $bc_pembimbing_1[0]->nip;
            $data_pembimbing_1 = $this->db->query("SELECT * FROM tb_dosen WHERE nip='" . $bc_pembimbing_1nip . "'")->getResult()[0];
            if ($bc_pembimbing_1[0]->status == 'ditandatangani') {
                $isi = "Disetujui Pada : " . $bc_pembimbing_1[0]->create_at . " Oleh " . $bc_pembimbing_1[0]->sebagai . " (" . $data_pembimbing_1->gelardepan . ' ' . $data_pembimbing_1->nama . ', ' . $data_pembimbing_1->gelarbelakang . ")";
                $qr_pembimbing_1 = $this->qr->cetakqr($isi);
            } else {
                $qr_pembimbing_1 = '<br>(BELUM DITANDA TANGANI)<br>';
            }
        } else {
            $qr_pembimbing_1 = '<br>(BELUM DITANDA TANGANI)<br>';
        }

        $bc_pembimbing_2 = $this->db->query("SELECT * FROM tb_berita_acara WHERE jenis_sidang='skripsi' and nim='$id' and sebagai='pembimbing 2'")->getResult();
        if (!empty($bc_pembimbing_2)) {
            $bc_pembimbing_2nip = $bc_pembimbing_2[0]->nip;
            $data_pembimbing_2 = $this->db->query("SELECT * FROM tb_dosen WHERE nip='" . $bc_pembimbing_2nip . "'")->getResult()[0];
            if ($bc_pembimbing_2[0]->status == 'ditandatangani') {
                $isi = "Disetujui Pada : " . $bc_pembimbing_2[0]->create_at . " Oleh " . $bc_pembimbing_2[0]->sebagai . " (" . $data_pembimbing_2->gelardepan . ' ' . $data_pembimbing_2->nama . ', ' . $data_pembimbing_2->gelarbelakang . ")";
                $qr_pembimbing_2 = $this->qr->cetakqr($isi);
            } else {
                $qr_pembimbing_2 = '<br>(BELUM DITANDA TANGANI)<br>';
            }
        } else {
            $qr_pembimbing_2 = '<br>(BELUM DITANDA TANGANI)<br>';
        }

        $bc_penguji_1 = $this->db->query("SELECT * FROM tb_berita_acara WHERE jenis_sidang='skripsi' and nim='$id' and sebagai='penguji 1'")->getResult();
        if (!empty($bc_penguji_1)) {
            $bc_penguji_1nip = $bc_penguji_1[0]->nip;
            $data_penguji_1 = $this->db->query("SELECT * FROM tb_dosen WHERE nip='" . $bc_penguji_1nip . "'")->getResult()[0];
            if ($bc_penguji_1[0]->status == 'ditandatangani') {
                $isi = "Disetujui Pada : " . $bc_penguji_1[0]->create_at . " Oleh " . $bc_penguji_1[0]->sebagai . " (" . $data_penguji_1->gelardepan . ' ' . $data_penguji_1->nama . ', ' . $data_penguji_1->gelarbelakang . ")";
                $qr_penguji_1 = $this->qr->cetakqr($isi);
            } else {
                $qr_penguji_1 = '<br>(BELUM DITANDA TANGANI)<br>';
            }
        } else {
            $qr_penguji_1 = '<br>(BELUM DITANDA TANGANI)<br>';
        }

        $bc_penguji_2 = $this->db->query("SELECT * FROM tb_berita_acara WHERE jenis_sidang='skripsi' and nim='$id' and sebagai='penguji 2'")->getResult();
        if (!empty($bc_penguji_2)) {
            $bc_penguji_2nip = $bc_penguji_2[0]->nip;
            $data_penguji_2 = $this->db->query("SELECT * FROM tb_dosen WHERE nip='" . $bc_penguji_2nip . "'")->getResult()[0];
            if ($bc_penguji_2[0]->status == 'ditandatangani') {
                $isi = "Disetujui Pada : " . $bc_penguji_2[0]->create_at . " Oleh " . $bc_penguji_2[0]->sebagai . " (" . $data_penguji_2->gelardepan . ' ' . $data_penguji_2->nama . ', ' . $data_penguji_2->gelarbelakang . ")";
                $qr_penguji_2 = $this->qr->cetakqr($isi);
            } else {
                $qr_penguji_2 = '<br>(BELUM DITANDA TANGANI)<br>';
            }
        } else {
            $qr_penguji_2 = '<br>(BELUM DITANDA TANGANI)<br>';
        }

        $bc_penguji_3 = $this->db->query("SELECT * FROM tb_berita_acara WHERE jenis_sidang='skripsi' and nim='$id' and sebagai='penguji 3'")->getResult();
        if (!empty($bc_penguji_3)) {
            $bc_penguji_3nip = $bc_penguji_3[0]->nip;
            $data_penguji_3 = $this->db->query("SELECT * FROM tb_dosen WHERE nip='" . $bc_penguji_3nip . "'")->getResult()[0];
            if ($bc_penguji_3[0]->status == 'ditandatangani') {
                $isi = "Disetujui Pada : " . $bc_penguji_3[0]->create_at . " Oleh " . $bc_penguji_3[0]->sebagai . " (" . $data_penguji_3->gelardepan . ' ' . $data_penguji_3->nama . ', ' . $data_penguji_3->gelarbelakang . ")";
                $qr_penguji_3 = $this->qr->cetakqr($isi);
            } else {
                $qr_penguji_3 = '<br>(BELUM DITANDA TANGANI)<br>';
            }
        } else {
            $qr_penguji_3 = '<br>(BELUM DITANDA TANGANI)<br>';
        }
        // ------------------------------------------------------------------
        $link = base_url() . "nilai_akhir_skripsi/$id/$idunit";
        $qr_link = $this->qr->cetakqr($link);
        $prodi = $this->db->query("SELECT * FROM tb_unit WHERE idunit='" . $idunit . "'")->getResult();
        $jurusan = $this->db->query("SELECT * FROM tb_unit WHERE idunit='" . $prodi[0]->parentunit . "'")->getResult();
        $fakultas = $this->db->query("SELECT * FROM tb_unit WHERE idunit='" . $jurusan[0]->parentunit . "'")->getResult();
        // --------------------------------------------------------------------------------------
        $data_nilai = $this->db->query("SELECT * FROM `tb_nilai` a  WHERE a.nim = '" . $id . "'  ORDER BY `a`.`sebagai` ASC")->getResult();
        $dummy_nilai = [1, 2, 3, 4, 5];
        $nilai = empty($data_nilai) ? $dummy_nilai : $data_nilai;
        $grade = $this->_grade($this->db, $id);
        $data = [
            'title' => 'Nilai Akhir Skripsi',
            'baseurl' => base_url(),
            'judul_skripsi' => $this->db->query("SELECT * FROM tb_pengajuan_topik WHERE nim='$id'")->getResult()[0]->judul_topik,
            'nim' => $id,
            'nama' => $this->db->query("SELECT * FROM tb_mahasiswa WHERE nim='$id'")->getResult()[0]->nama,
            'dosen_pembimbing_1' => $this->db->query("SELECT * FROM tb_pengajuan_pembimbing a LEFT JOIN tb_dosen b ON a.`nip`=b.`nip` LEFT JOIN tb_profil_tambahan c ON a.`nip`=c.`id` WHERE a.nim='" . $id . "' AND a.status_pengajuan='diterima' AND sebagai='1'")->getResult(),
            'dosen_pembimbing_2' => $this->db->query("SELECT * FROM tb_pengajuan_pembimbing a LEFT JOIN tb_dosen b ON a.`nip`=b.`nip` LEFT JOIN tb_profil_tambahan c ON a.`nip`=c.`id` WHERE a.nim='" . $id . "' AND a.status_pengajuan='diterima' AND sebagai='2'")->getResult(),
            'penguji_1' => $this->db->query("SELECT * from tb_penguji a LEFT JOIN tb_dosen b ON a.`nip`=b.`nip` LEFT JOIN tb_profil_tambahan c ON a.`nip`=c.`id` where a.nim='" . $id . "' AND a.`status`='aktif' AND a.sebagai='1'")->getResult(),
            'penguji_2' => $this->db->query("SELECT * from tb_penguji a LEFT JOIN tb_dosen b ON a.`nip`=b.`nip` LEFT JOIN tb_profil_tambahan c ON a.`nip`=c.`id` where a.nim='" . $id . "' AND a.`status`='aktif' AND a.sebagai='2'")->getResult(),
            'penguji_3' => $this->db->query("SELECT * from tb_penguji a LEFT JOIN tb_dosen b ON a.`nip`=b.`nip` LEFT JOIN tb_profil_tambahan c ON a.`nip`=c.`id` where a.nim='" . $id . "' AND a.`status`='aktif' AND a.sebagai='3'")->getResult(),
            'qr_pembimbing_1' => $qr_pembimbing_1,
            'qr_pembimbing_2' => $qr_pembimbing_2,
            'qr_penguji_1' => $qr_penguji_1,
            'qr_penguji_2' => $qr_penguji_2,
            'qr_penguji_3' => $qr_penguji_3,
            'qr_link' => $qr_link,
            'db' => $this->db,
            'nm_prodi' => $prodi[0]->namaunit,
            'nm_jurusan' => $jurusan[0]->namaunit,
            'nm_fakultas' => $fakultas[0]->namaunit,
            'data_nilai' => $nilai,
            'grade' => $grade
        ];
        $dompdf = new Dompdf();
        $filename = $id . "-" . date('y-m-d-H-i-s');
        $dompdf->loadHtml(view('Cetak/nilai_akhir_skripsi', $data));
        $dompdf->setPaper('A4', 'potrait');
        $dompdf->render();
        $dompdf->stream($filename, array('Attachment' => false));
        exit();
    }


    public function pendaftar($id, $date1, $date2)
    {
        $link = base_url() . "cetak_pendaftar/$id/$date1/$date2";
        $qr_link = $this->qr->cetakqr($link);
        $data_jadwal = $this->db->query("SELECT * FROM tb_jadwal_sidang a LEFT JOIN tb_unit b ON a.`idunit`=b.`idunit` WHERE a.`id_jadwal`='$id'")->getResult();
        if ($date1 == 'semua') {
            if ($date2 == 'semua') {
                $data_pendaftar = $this->db->query("SELECT * FROM tb_pendaftar_sidang WHERE id_jadwal='$id'")->getResult();
            } else {
            }
        } else {
            if ($date2 == 'semua') {
            } else {
                $data_pendaftar = $this->db->query("SELECT * FROM tb_pendaftar_sidang WHERE id_jadwal='$id' AND (waktu_sidang BETWEEN '$date1' AND ('$date2' + INTERVAL 1 DAY))")->getResult();
            }
        }
        $data = [
            'baseurl' => base_url(),
            'title' => 'Data Pendaftar Sidang',
            'db' => $this->db,
            'id_jadwal' => $id,
            'jenis' => 'semua',
            'data_pendaftar' => $data_pendaftar,
            'data_jadwal' => $data_jadwal,
            'namaunit' => $data_jadwal[0]->namaunit,
            'qr_link' => $qr_link,
        ];
        session()->set('ses_id_jadwal', $id);
        $dompdf = new Dompdf();
        $filename = date('y-m-d-H-i-s');
        $dompdf->loadHtml(view('Cetak/data_pendaftar_sidang', $data));
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream($filename, array('Attachment' => false));
        exit();
    }
    public function direct_hasil_dosen()
    {
        if (session()->get('ses_id') == '' || session()->get('ses_login') == 'mahasiswa') {
            return redirect()->to('/');
        }
        if (!$this->validate([
            'start' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Pilih Tanggal Mulai'
                ]
            ],
            'end' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Pilih Tanggal Akhir'
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
        $idunit = session()->get('ses_idunit');
        $date1 = $this->request->getPost('start');
        $date2 = $this->request->getPost('end');
        $jenis_file = $this->request->getPost('jenis_file');
        if ($jenis_file == 'pdf') {
            return redirect()->to("hasil_dosen/$idunit/$date1/$date2");
        } else {
            return redirect()->to("hasil_dosen_excel/$idunit/$date1/$date2");
        }
    }
    public function hasil_dosen($idunit, $date1, $date2)
    {
        $link = base_url() . "hasil_dosen/$idunit/$date1/$date2";
        $qr_link = $this->qr->cetakqr($link);
        $tb_unit = $this->db->query("SELECT * FROM tb_unit WHERE idunit='$idunit'")->getResult();
        $data = [
            'baseurl' => base_url(),
            'title' => 'Data Hasil Dosen',
            'db' => $this->db,
            'qr_link' => $qr_link,
            'namaunit' => $tb_unit[0]->namaunit,
            'date1' => $date1,
            'date2' => $date2,
            'tb_dosen' => $this->db->query("SELECT DISTINCT a.`nip`,b.* FROM tb_nilai a LEFT JOIN tb_dosen b ON a.`nip`=b.`nip` WHERE idunit='$idunit'")->getResult(),
        ];
        $dompdf = new Dompdf();
        $filename = date('y-m-d-H-i-s');
        $dompdf->loadHtml(view('Cetak/hasil_dosen', $data));
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream($filename, array('Attachment' => false));
        exit();
    }
    public function hasil_dosen_excel($idunit, $date1, $date2)
    {
        $link = base_url() . "hasil_dosen/$idunit/$date1/$date2";
        $qr_link = $this->qr->cetakqr($link);
        $tb_unit = $this->db->query("SELECT * FROM tb_unit WHERE idunit='$idunit'")->getResult();
        $data = [
            'baseurl' => base_url(),
            'title' => 'Data Hasil Dosen',
            'db' => $this->db,
            'qr_link' => $qr_link,
            'namaunit' => $tb_unit[0]->namaunit,
            'date1' => $date1,
            'date2' => $date2,
            'tb_dosen' => $this->db->query("SELECT DISTINCT a.`nip`,b.* FROM tb_nilai a LEFT JOIN tb_dosen b ON a.`nip`=b.`nip` WHERE idunit='$idunit'")->getResult(),
        ];
        return view('Cetak/hasil_dosen_excel', $data);
    }
}
