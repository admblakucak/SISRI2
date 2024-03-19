<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use \DateTime;
use App\Libraries\Access_API; // Import library
use App\Libraries\QRcodelib; // Import library
use CodeIgniter\Database\Query;
use Dompdf\Dompdf;
use Dompdf\Options;
use Google\Service\AdExchangeBuyerII\Date;

class UpdateNilaiDosen implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return mixed
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $this->api = new Access_API();
        $this->qr = new QRcodelib();
        $db = \Config\Database::connect();

        $nip_dosen = session()->get('ses_id');
        $data_mhs_bimbingan_dosen = $db->query("SELECT nim FROM `tb_pengajuan_pembimbing` WHERE nip ='$nip_dosen' AND status_pengajuan='diterima' AND pesan IS NULL ")->getResult();
        $data_mhs_uji_dosen = $db->query("SELECT DISTINCT nim FROM `tb_penguji` WHERE nip='$nip_dosen' AND status='aktif'")->getResult();

        foreach ($data_mhs_bimbingan_dosen as $key) {
            $nim_mhs = $key->nim;

            $id_pendaftar = $db->query("SELECT * FROM tb_pendaftar_sidang a LEFT JOIN tb_jadwal_sidang b ON a.`id_jadwal`=b.`id_jadwal` WHERE b.`jenis_sidang`='sidang skripsi' AND nim='" . $nim_mhs . "' ORDER BY create_at DESC LIMIT 1")->getResult();
            if (!empty($id_pendaftar)) {
                $id_pendaftar = $id_pendaftar[0]->id_pendaftar;
            } else {
                $id_pendaftar = 0;
            }
            $jadwal_sidang = $db->query("SELECT * FROM tb_pendaftar_sidang WHERE id_pendaftar='$id_pendaftar' AND (hasil_sidang is null or hasil_sidang < 3 )")->getResult();
            // Revisi TB_NILAI jika lebih dari 24 jam otomatis nilai 80
            if (!empty($jadwal_sidang)) {
                // Dosen Pembimbing
                $dosen_pembimbing = $db->query("SELECT * FROM tb_pengajuan_pembimbing a WHERE a.nim='" . $nim_mhs . "' AND a.status_pengajuan='diterima' ORDER BY a.`sebagai` ASC ")->getResult();

                // penguji 
                $penguji1  = $db->query("SELECT * FROM tb_penguji a WHERE a.nim='" . $nim_mhs  . "' AND a.status='aktif' AND a.jenis_sidang = 'sidang skripsi' AND a.sebagai=1")->getResult();
                if (empty($penguji1)) {
                    $penguji1  = $db->query("SELECT * FROM tb_penguji a WHERE a.nim='" . $nim_mhs  . "' AND a.status='aktif' AND a.jenis_sidang = '' AND a.sebagai=1")->getResult();
                }
                $penguji2  = $db->query("SELECT * FROM tb_penguji a WHERE a.nim='" . $nim_mhs  . "' AND a.status='aktif' AND a.jenis_sidang = 'sidang skripsi' AND a.sebagai=2")->getResult();
                if (empty($penguji2)) {
                    $penguji2  = $db->query("SELECT * FROM tb_penguji a WHERE a.nim='" . $nim_mhs  . "' AND a.status='aktif' AND a.jenis_sidang = '' AND a.sebagai=2")->getResult();
                }
                $penguji3  = $db->query("SELECT * FROM tb_penguji a WHERE a.nim='" . $nim_mhs  . "' AND a.status='aktif' AND a.jenis_sidang = 'sidang skripsi' AND a.sebagai=3")->getResult();
                if (empty($penguji3)) {
                    $penguji3  = $db->query("SELECT * FROM tb_penguji a WHERE a.nim='" . $nim_mhs  . "' AND a.status='aktif' AND a.jenis_sidang = '' AND a.sebagai=3")->getResult();
                }


                $d_now = new Datetime();
                $d_sidang = new Datetime($jadwal_sidang[0]->waktu_sidang);
                // $d_sidang = new Datetime('2024-06-07 10:22:00');
                if ($d_now > $d_sidang) {
                    $selisih = date_diff($d_now, $d_sidang);
                    $interval = $selisih->days * 24;
                    $interval += $selisih->h;
                    // karena $d_now utc+00 sedangkan $d_sidang utc+7
                    if ($interval >= 18) {

                        // Update keterangan lulus
                        $db->query("UPDATE `tb_pengajuan_pembimbing` SET pesan='Sudah Lulus' WHERE status_pengajuan='diterima' and nim='$nim_mhs' ");


                        $cek_nilai_penguji1 = $db->query("SELECT * FROM tb_nilai where nim ='" . $nim_mhs . "' AND nip ='" . $penguji1[0]->nip . "' AND sebagai = 'penguji 1' ")->getResult();
                        if (empty($cek_nilai_penguji1)) {
                            // ttd berita acara
                            $berita_acara_penguji1 = $db->query("SELECT * FROM tb_berita_acara WHERE nim='" . $nim_mhs . "' AND nip='" . $penguji1[0]->nip . "' AND sebagai='" . 'penguji 1' . "' AND status='ditandatangani' AND jenis_sidang='skripsi' AND id_pendaftar='$id_pendaftar' ")->getResult();
                            if (empty($berita_acara_penguji1)) {
                                $db->query("INSERT INTO tb_berita_acara (nim,nip,sebagai,status,jenis_sidang,id_pendaftar) VALUES ('" . $nim_mhs . "','" . $penguji1[0]->nip . "','penguji 1','ditandatangani','skripsi','$id_pendaftar')");
                            } else {
                                $db->query("UPDATE tb_berita_acara SET status= 'ditandatangani' WHERE nim='" . $nim_mhs . "' AND nip='" . $penguji1[0]->nip . "' AND sebagai='" . 'penguji 1' . "' AND jenis_sidang='skripsi' AND id_pendaftar='$id_pendaftar'");
                            }

                            // acc revisi
                            $cek_acc_penguji_1 = $db->query("SELECT * FROM `tb_acc_revisi` WHERE nim ='" . $nim_mhs . "' AND nip = '" . $penguji1[0]->nip . "' AND sebagai = 'Penguji 1' AND jenis_sidang='skripsi'")->getResult();
                            if (empty($cek_acc_penguji_1)) {
                                $db->query("INSERT INTO tb_acc_revisi (nip,nim,jenis_sidang,sebagai) VALUES ('" . $penguji1[0]->nip . "','" . $nim_mhs . "','skripsi','Penguji 1')");
                            }



                            $db->query("INSERT INTO tb_nilai (nim,nip,sebagai,nilai_ujian) VALUES ('" . $nim_mhs . "','" . $penguji1[0]->nip . "','penguji 1','80')  ");
                        } elseif (empty($cek_nilai_penguji1[0]->nilai_ujian)) {

                            // ttd berita acara
                            $berita_acara_penguji1 = $db->query("SELECT * FROM tb_berita_acara WHERE nim='" . $nim_mhs . "' AND nip='" . $penguji1[0]->nip . "' AND sebagai='" . 'penguji 1' . "' AND status='ditandatangani' AND jenis_sidang='skripsi' AND id_pendaftar='$id_pendaftar' ")->getResult();
                            if (empty($berita_acara_penguji1)) {
                                $db->query("INSERT INTO tb_berita_acara (nim,nip,sebagai,status,jenis_sidang,id_pendaftar) VALUES ('" . $nim_mhs . "','" . $penguji1[0]->nip . "','penguji 1','ditandatangani','skripsi','$id_pendaftar')");
                            } else {
                                $db->query("UPDATE tb_berita_acara SET status= 'ditandatangani' WHERE nim='" . $nim_mhs . "' AND nip='" . $penguji1[0]->nip . "' AND sebagai='" . 'penguji 1' . "' AND jenis_sidang='skripsi' AND id_pendaftar='$id_pendaftar'");
                            }

                            // acc revisi
                            $cek_acc_penguji_1 = $db->query("SELECT * FROM `tb_acc_revisi` WHERE nim ='" . $nim_mhs . "' AND nip = '" . $penguji1[0]->nip . "' AND sebagai = 'Penguji 1' AND jenis_sidang='skripsi'")->getResult();
                            if (empty($cek_acc_penguji_1)) {
                                $db->query("INSERT INTO tb_acc_revisi (nip,nim,jenis_sidang,sebagai) VALUES ('" . $penguji1[0]->nip . "','" . $nim_mhs . "','skripsi','Penguji 1')");
                            }

                            $db->query("UPDATE `tb_nilai` SET nilai_ujian='80' where id_nilai='" . $cek_nilai_penguji1[0]->id_nilai . "'");
                        }


                        $cek_nilai_penguji2 = $db->query("SELECT * FROM tb_nilai where nim ='" . $nim_mhs . "' AND nip ='" . $penguji2[0]->nip . "' AND sebagai = 'penguji 2' ")->getResult();
                        if (empty($cek_nilai_penguji2)) {

                            // ttd berita acara
                            $berita_acara_penguji2 = $db->query("SELECT * FROM tb_berita_acara WHERE nim='" . $nim_mhs . "' AND nip='" . $penguji2[0]->nip . "' AND sebagai='" . 'penguji 2' . "' AND status='ditandatangani' AND jenis_sidang='skripsi' AND id_pendaftar='$id_pendaftar' ")->getResult();
                            if (empty($berita_acara_penguji2)) {
                                $db->query("INSERT INTO tb_berita_acara (nim,nip,sebagai,status,jenis_sidang,id_pendaftar) VALUES ('" . $nim_mhs . "','" . $penguji2[0]->nip . "','penguji 2','ditandatangani','skripsi','$id_pendaftar')");
                            } else {
                                $db->query("UPDATE tb_berita_acara SET status= 'ditandatangani' WHERE nim='" . $nim_mhs . "' AND nip='" . $penguji2[0]->nip . "' AND sebagai='" . 'penguji 2' . "' AND jenis_sidang='skripsi' AND id_pendaftar='$id_pendaftar'");
                            }

                            // acc revisi
                            $cek_acc_penguji_2 = $db->query("SELECT * FROM `tb_acc_revisi` WHERE nim ='" . $nim_mhs . "' AND nip = '" . $penguji2[0]->nip . "' AND sebagai = 'Penguji 2' AND jenis_sidang='skripsi'")->getResult();
                            if (empty($cek_acc_penguji_2)) {
                                $db->query("INSERT INTO tb_acc_revisi (nip,nim,jenis_sidang,sebagai) VALUES ('" . $penguji2[0]->nip . "','" . $nim_mhs . "','skripsi','Penguji 2')");
                            }

                            $db->query("INSERT INTO tb_nilai (nim,nip,sebagai,nilai_ujian) VALUES ('" . $nim_mhs . "','" . $penguji2[0]->nip . "','penguji 2','80')");
                        } elseif (empty($cek_nilai_penguji2[0]->nilai_ujian)) {
                            // ttd berita acara
                            $berita_acara_penguji2 = $db->query("SELECT * FROM tb_berita_acara WHERE nim='" . $nim_mhs . "' AND nip='" . $penguji2[0]->nip . "' AND sebagai='" . 'penguji 2' . "' AND status='ditandatangani' AND jenis_sidang='skripsi' AND id_pendaftar='$id_pendaftar'")->getResult();
                            if (empty($berita_acara_penguji2)) {
                                $db->query("INSERT INTO tb_berita_acara (nim,nip,sebagai,status,jenis_sidang,id_pendaftar) VALUES ('" . $nim_mhs . "','" . $penguji2[0]->nip . "','penguji 2','ditandatangani','skripsi','$id_pendaftar')");
                            } else {
                                $db->query("UPDATE tb_berita_acara SET status= 'ditandatangani' WHERE nim='" . $nim_mhs . "' AND nip='" . $penguji2[0]->nip . "' AND sebagai='" . 'penguji 2' . "' AND jenis_sidang='skripsi' AND id_pendaftar='$id_pendaftar'");
                            }

                            // acc revisi
                            $cek_acc_penguji_2 = $db->query("SELECT * FROM `tb_acc_revisi` WHERE nim ='" . $nim_mhs . "' AND nip = '" . $penguji2[0]->nip . "' AND sebagai = 'Penguji 2' AND jenis_sidang='skripsi'")->getResult();
                            if (empty($cek_acc_penguji_2)) {
                                $db->query("INSERT INTO tb_acc_revisi (nip,nim,jenis_sidang,sebagai) VALUES ('" . $penguji2[0]->nip . "','" . $nim_mhs . "','skripsi','Penguji 2')");
                            }

                            $db->query("UPDATE `tb_nilai` SET nilai_ujian='80' where id_nilai='" . $cek_nilai_penguji2[0]->id_nilai . "'");
                            // $db->query("INSERT INTO tb_nilai (nim,nip,sebagai,nilai_ujian) VALUES ('" . $nim_mhs . "','" . $penguji2[0]->nip . "','penguji 2','80')");
                        }


                        $cek_nilai_penguji3 = $db->query("SELECT * FROM tb_nilai where nim ='" . $nim_mhs . "' AND nip ='" . $penguji3[0]->nip . "' AND sebagai = 'penguji 3' ")->getResult();
                        if (empty($cek_nilai_penguji3)) {

                            // ttd berita acara
                            $berita_acara_penguji3 = $db->query("SELECT * FROM tb_berita_acara WHERE nim='" . $nim_mhs . "' AND nip='" . $penguji3[0]->nip . "' AND sebagai='" . 'penguji 3' . "' AND status='ditandatangani' AND jenis_sidang='skripsi' AND id_pendaftar='$id_pendaftar'")->getResult();
                            if (empty($berita_acara_penguji3)) {
                                $db->query("INSERT INTO tb_berita_acara (nim,nip,sebagai,status,jenis_sidang,id_pendaftar) VALUES ('" . $nim_mhs . "','" . $penguji3[0]->nip . "','penguji 3','ditandatangani','skripsi','$id_pendaftar')");
                            } else {
                                $db->query("UPDATE tb_berita_acara SET status= 'ditandatangani' WHERE nim='" . $nim_mhs . "' AND nip='" . $penguji3[0]->nip . "' AND sebagai='" . 'penguji 3' . "' AND jenis_sidang='skripsi' AND id_pendaftar='$id_pendaftar'");
                            }

                            // acc revisi
                            $cek_acc_penguji_3 = $db->query("SELECT * FROM `tb_acc_revisi` WHERE nim ='" . $nim_mhs . "' AND nip = '" . $penguji3[0]->nip . "' AND sebagai = 'Penguji 3' AND jenis_sidang='skripsi'")->getResult();
                            if (empty($cek_acc_penguji_3)) {
                                $db->query("INSERT INTO tb_acc_revisi (nip,nim,jenis_sidang,sebagai) VALUES ('" . $penguji3[0]->nip . "','" . $nim_mhs . "','skripsi','Penguji 3')");
                            }

                            $db->query("INSERT INTO tb_nilai (nim,nip,sebagai,nilai_ujian) VALUES ('" . $nim_mhs . "','" . $penguji3[0]->nip . "','penguji 3','80')");
                        } elseif (empty($cek_nilai_penguji3[0]->nilai_ujian)) {

                            // ttd berita acara
                            $berita_acara_penguji3 = $db->query("SELECT * FROM tb_berita_acara WHERE nim='" . $nim_mhs . "' AND nip='" . $penguji3[0]->nip . "' AND sebagai='" . 'penguji 3' . "' AND status='ditandatangani' AND jenis_sidang='skripsi' AND id_pendaftar='$id_pendaftar'")->getResult();
                            if (empty($berita_acara_penguji3)) {
                                $db->query("INSERT INTO tb_berita_acara (nim,nip,sebagai,status,jenis_sidang,id_pendaftar) VALUES ('" . $nim_mhs . "','" . $penguji3[0]->nip . "','penguji 3','ditandatangani','skripsi','$id_pendaftar')");
                            } else {
                                $db->query("UPDATE tb_berita_acara SET status= 'ditandatangani' WHERE nim='" . $nim_mhs . "' AND nip='" . $penguji3[0]->nip . "' AND sebagai='" . 'penguji 3' . "' AND jenis_sidang='skripsi' AND id_pendaftar='$id_pendaftar'");
                            }

                            // acc revisi
                            $cek_acc_penguji_3 = $db->query("SELECT * FROM `tb_acc_revisi` WHERE nim ='" . $nim_mhs . "' AND nip = '" . $penguji3[0]->nip . "' AND sebagai = 'Penguji 3' AND jenis_sidang='skripsi'")->getResult();
                            if (empty($cek_acc_penguji_3)) {
                                $db->query("INSERT INTO tb_acc_revisi (nip,nim,jenis_sidang,sebagai) VALUES ('" . $penguji3[0]->nip . "','" . $nim_mhs . "','skripsi','Penguji 3')");
                            }

                            $db->query("UPDATE `tb_nilai` SET nilai_ujian='80' where id_nilai='" . $cek_nilai_penguji3[0]->id_nilai . "'");
                            // $db->query("INSERT INTO tb_nilai (nim,nip,sebagai,nilai_ujian) VALUES ('" . $nim_mhs . "','" . $penguji3[0]->nip . "','penguji 3','80')");
                        }


                        $cek_nilai_pembimbing1 =  $db->query("SELECT * FROM tb_nilai where nim ='" . $nim_mhs . "' AND sebagai = 'pembimbing 1' ")->getResult();
                        if (empty($cek_nilai_pembimbing1)) {
                            $berita_acara_pem1 = $db->query("SELECT * FROM tb_berita_acara WHERE nim='" . $nim_mhs . "' AND nip='" . $dosen_pembimbing[0]->nip . "' AND sebagai='" . 'pembimbing 1' . "' AND status='ditandatangani' AND jenis_sidang='skripsi' AND id_pendaftar='$id_pendaftar'")->getResult();
                            if (empty($berita_acara_pem1)) {
                                $db->query("INSERT INTO tb_berita_acara (nim,nip,sebagai,status,jenis_sidang,id_pendaftar) VALUES ('" . $nim_mhs . "','" . $dosen_pembimbing[0]->nip . "','pembimbing 1','ditandatangani','skripsi','$id_pendaftar')");
                            } else {
                                $db->query("UPDATE tb_berita_acara SET status= 'ditandatangani' WHERE nim='" . $nim_mhs . "' AND nip='" . $dosen_pembimbing[0]->nip . "' AND sebagai='" . 'pembimbing 1' . "' AND jenis_sidang='skripsi' AND id_pendaftar='$id_pendaftar'");
                            }
                            $db->query("UPDATE tb_pendaftar_sidang SET hasil_sidang='2' WHERE id_pendaftar='$id_pendaftar'");
                            $db->query("INSERT INTO tb_nilai (nim,nip,sebagai,nilai_ujian,nilai_bimbingan) VALUES ('" . $nim_mhs . "','" . $dosen_pembimbing[0]->nip . "','pembimbing 1','80','80')  ");
                        } elseif (empty($cek_nilai_pembimbing1[0]->nilai_bimbingan) || empty($cek_nilai_pembimbing1[0]->nilai_ujian)) {
                            $berita_acara_pem1 = $db->query("SELECT * FROM tb_berita_acara WHERE nim='" . $nim_mhs . "' AND nip='" . $dosen_pembimbing[0]->nip . "' AND sebagai='" . 'pembimbing 1' . "' AND status='ditandatangani' AND jenis_sidang='skripsi' AND id_pendaftar='$id_pendaftar'")->getResult();
                            if (empty($berita_acara_pem1)) {
                                $db->query("INSERT INTO tb_berita_acara (nim,nip,sebagai,status,jenis_sidang,id_pendaftar) VALUES ('" . $nim_mhs . "','" . $dosen_pembimbing[0]->nip . "','pembimbing 1','ditandatangani','skripsi','$id_pendaftar')");
                            } else {
                                $db->query("UPDATE tb_berita_acara SET status= 'ditandatangani' WHERE nim='" . $nim_mhs . "' AND nip='" . $dosen_pembimbing[0]->nip . "' AND sebagai='" . 'pembimbing 1' . "' AND jenis_sidang='skripsi' AND id_pendaftar='$id_pendaftar'");
                            }
                            $db->query("UPDATE tb_pendaftar_sidang SET hasil_sidang='2' WHERE id_pendaftar='$id_pendaftar'");
                            $db->query("UPDATE `tb_nilai` SET nilai_ujian='80', nilai_bimbingan='80' where id_nilai='" . $cek_nilai_pembimbing1[0]->id_nilai . "'");
                            // $db->query("INSERT INTO tb_nilai (nim,nip,sebagai,nilai_ujian,nilai_bimbingan) VALUES ('" . $nim_mhs . "','" . $dosen_pembimbing[0]->nip . "','pembimbing 1','80','80')");
                        }


                        $cek_nilai_pembimbing2 =  $db->query("SELECT * FROM tb_nilai where nim ='" . $nim_mhs . "' AND sebagai = 'pembimbing 2' ")->getResult();
                        if (empty($cek_nilai_pembimbing2)) {
                            // ttd berita acara
                            $berita_acara_pem2 = $db->query("SELECT * FROM tb_berita_acara WHERE nim='" . $nim_mhs . "' AND nip='" . $dosen_pembimbing[1]->nip . "' AND sebagai='" . 'pembimbing 2' . "' AND status='ditandatangani' AND jenis_sidang='skripsi' AND id_pendaftar='$id_pendaftar'")->getResult();
                            if (empty($berita_acara_pem2)) {
                                $db->query("INSERT INTO tb_berita_acara (nim,nip,sebagai,status,jenis_sidang,id_pendaftar) VALUES ('" . $nim_mhs . "','" . $dosen_pembimbing[1]->nip . "','pembimbing 2','ditandatangani','skripsi','$id_pendaftar')");
                            } else {
                                $db->query("UPDATE tb_berita_acara SET status= 'ditandatangani' WHERE nim='" . $nim_mhs . "' AND nip='" . $dosen_pembimbing[1]->nip . "' AND sebagai='" . 'pembimbing 2' . "' AND jenis_sidang='skripsi' AND id_pendaftar='$id_pendaftar'");
                            }
                            // input nilai default 80 ke tb_nilai
                            $db->query("INSERT INTO tb_nilai (nim,nip,sebagai,nilai_ujian,nilai_bimbingan) VALUES ('" . $nim_mhs . "','" . $dosen_pembimbing[1]->nip . "','pembimbing 2','80','80')  ");
                        } elseif (empty($cek_nilai_pembimbing2[0]->nilai_bimbingan) || empty($cek_nilai_pembimbing2[0]->nilai_ujian)) {
                            $berita_acara_pem2 = $db->query("SELECT * FROM tb_berita_acara WHERE nim='" . $nim_mhs . "' AND nip='" . $dosen_pembimbing[1]->nip . "' AND sebagai='" . 'pembimbing 2' . "' AND status='ditandatangani' AND jenis_sidang='skripsi' AND id_pendaftar='$id_pendaftar'")->getResult();
                            if (empty($berita_acara_pem2)) {
                                $db->query("INSERT INTO tb_berita_acara (nim,nip,sebagai,status,jenis_sidang,id_pendaftar) VALUES ('" . $nim_mhs . "','" . $dosen_pembimbing[1]->nip . "','pembimbing 2','ditandatangani','skripsi','$id_pendaftar')");
                            } else {
                                $db->query("UPDATE tb_berita_acara SET status= 'ditandatangani' WHERE nim='" . $nim_mhs . "' AND nip='" . $dosen_pembimbing[1]->nip . "' AND sebagai='" . 'pembimbing 2' . "' AND jenis_sidang='skripsi' AND id_pendaftar='$id_pendaftar'");
                            }

                            $db->query("UPDATE `tb_nilai` SET nilai_ujian='80', nilai_bimbingan='80' where id_nilai='" . $cek_nilai_pembimbing2[0]->id_nilai . "'");

                            // $db->query("INSERT INTO tb_nilai (nim,nip,sebagai,nilai_ujian,nilai_bimbingan) VALUES ('" . $nim_mhs . "','" . $dosen_pembimbing[1]->nip . "','pembimbing 2','80','80')");
                        }
                    }
                }
            }
        }

        foreach ($data_mhs_uji_dosen as $key) {
            $nim_mhs = $key->nim;

            $id_pendaftar = $db->query("SELECT * FROM tb_pendaftar_sidang a LEFT JOIN tb_jadwal_sidang b ON a.`id_jadwal`=b.`id_jadwal` WHERE b.`jenis_sidang`='sidang skripsi' AND nim='" . $nim_mhs . "' ORDER BY create_at DESC LIMIT 1")->getResult();
            if (!empty($id_pendaftar)) {
                $id_pendaftar = $id_pendaftar[0]->id_pendaftar;
            } else {
                $id_pendaftar = 0;
            }
            $jadwal_sidang = $db->query("SELECT * FROM tb_pendaftar_sidang WHERE id_pendaftar='$id_pendaftar' AND (hasil_sidang is null or hasil_sidang < 3 )")->getResult();

            // Revisi TB_NILAI jika lebih dari 24 jam otomatis nilai 80
            if (!empty($jadwal_sidang)) {
                // Dosen Pembimbing
                $dosen_pembimbing = $db->query("SELECT * FROM tb_pengajuan_pembimbing a WHERE a.nim='" . $nim_mhs . "' AND a.status_pengajuan='diterima' ORDER BY a.`sebagai` ASC ")->getResult();

                // penguji 
                $penguji1  = $db->query("SELECT * FROM tb_penguji a WHERE a.nim='" . $nim_mhs  . "' AND a.status='aktif' AND a.jenis_sidang = 'sidang skripsi' AND a.sebagai=1")->getResult();
                if (empty($penguji1)) {
                    $penguji1  = $db->query("SELECT * FROM tb_penguji a WHERE a.nim='" . $nim_mhs  . "' AND a.status='aktif' AND a.jenis_sidang = '' AND a.sebagai=1")->getResult();
                }
                $penguji2  = $db->query("SELECT * FROM tb_penguji a WHERE a.nim='" . $nim_mhs  . "' AND a.status='aktif' AND a.jenis_sidang = 'sidang skripsi' AND a.sebagai=2")->getResult();
                if (empty($penguji2)) {
                    $penguji2  = $db->query("SELECT * FROM tb_penguji a WHERE a.nim='" . $nim_mhs  . "' AND a.status='aktif' AND a.jenis_sidang = '' AND a.sebagai=2")->getResult();
                }
                $penguji3  = $db->query("SELECT * FROM tb_penguji a WHERE a.nim='" . $nim_mhs  . "' AND a.status='aktif' AND a.jenis_sidang = 'sidang skripsi' AND a.sebagai=3")->getResult();
                if (empty($penguji3)) {
                    $penguji3  = $db->query("SELECT * FROM tb_penguji a WHERE a.nim='" . $nim_mhs  . "' AND a.status='aktif' AND a.jenis_sidang = '' AND a.sebagai=3")->getResult();
                }

                $d_now = new Datetime();
                $d_sidang = new Datetime($jadwal_sidang[0]->waktu_sidang);

                if ($d_now > $d_sidang) {
                    $selisih = date_diff($d_now, $d_sidang);
                    $interval = $selisih->days * 24;
                    $interval += $selisih->h;
                    // karena $d_now utc+00 sedangkan $d_sidang utc+7
                    if ($interval >= 18) {
                        // Update keterangan lulus
                        $db->query("UPDATE `tb_pengajuan_pembimbing` SET pesan='Sudah Lulus' WHERE status_pengajuan='diterima' and nim='$nim_mhs' ");

                        $cek_nilai_penguji1 = $db->query("SELECT * FROM tb_nilai where nim ='" . $nim_mhs . "' AND nip ='" . $penguji1[0]->nip . "' AND sebagai = 'penguji 1' ")->getResult();
                        if (empty($cek_nilai_penguji1)) {
                            // ttd berita acara
                            $berita_acara_penguji1 = $db->query("SELECT * FROM tb_berita_acara WHERE nim='" . $nim_mhs . "' AND nip='" . $penguji1[0]->nip . "' AND sebagai='" . 'penguji 1' . "' AND status='ditandatangani' AND jenis_sidang='skripsi' AND id_pendaftar='$id_pendaftar' ")->getResult();
                            if (empty($berita_acara_penguji1)) {
                                $db->query("INSERT INTO tb_berita_acara (nim,nip,sebagai,status,jenis_sidang,id_pendaftar) VALUES ('" . $nim_mhs . "','" . $penguji1[0]->nip . "','penguji 1','ditandatangani','skripsi','$id_pendaftar')");
                            } else {
                                $db->query("UPDATE tb_berita_acara SET status= 'ditandatangani' WHERE nim='" . $nim_mhs . "' AND nip='" . $penguji1[0]->nip . "' AND sebagai='" . 'penguji 1' . "' AND jenis_sidang='skripsi' AND id_pendaftar='$id_pendaftar'");
                            }

                            // acc revisi
                            $cek_acc_penguji_1 = $db->query("SELECT * FROM `tb_acc_revisi` WHERE nim ='" . $nim_mhs . "' AND nip = '" . $penguji1[0]->nip . "' AND sebagai = 'Penguji 1' AND jenis_sidang='skripsi'")->getResult();
                            if (empty($cek_acc_penguji_1)) {
                                $db->query("INSERT INTO tb_acc_revisi (nip,nim,jenis_sidang,sebagai) VALUES ('" . $penguji1[0]->nip . "','" . $nim_mhs . "','skripsi','Penguji 1')");
                            }



                            $db->query("INSERT INTO tb_nilai (nim,nip,sebagai,nilai_ujian) VALUES ('" . $nim_mhs . "','" . $penguji1[0]->nip . "','penguji 1','80')  ");
                        } elseif (empty($cek_nilai_penguji1[0]->nilai_ujian)) {

                            // ttd berita acara
                            $berita_acara_penguji1 = $db->query("SELECT * FROM tb_berita_acara WHERE nim='" . $nim_mhs . "' AND nip='" . $penguji1[0]->nip . "' AND sebagai='" . 'penguji 1' . "' AND status='ditandatangani' AND jenis_sidang='skripsi' AND id_pendaftar='$id_pendaftar' ")->getResult();
                            if (empty($berita_acara_penguji1)) {
                                $db->query("INSERT INTO tb_berita_acara (nim,nip,sebagai,status,jenis_sidang,id_pendaftar) VALUES ('" . $nim_mhs . "','" . $penguji1[0]->nip . "','penguji 1','ditandatangani','skripsi','$id_pendaftar')");
                            } else {
                                $db->query("UPDATE tb_berita_acara SET status= 'ditandatangani' WHERE nim='" . $nim_mhs . "' AND nip='" . $penguji1[0]->nip . "' AND sebagai='" . 'penguji 1' . "' AND jenis_sidang='skripsi' AND id_pendaftar='$id_pendaftar'");
                            }

                            // acc revisi
                            $cek_acc_penguji_1 = $db->query("SELECT * FROM `tb_acc_revisi` WHERE nim ='" . $nim_mhs . "' AND nip = '" . $penguji1[0]->nip . "' AND sebagai = 'Penguji 1' AND jenis_sidang='skripsi'")->getResult();
                            if (empty($cek_acc_penguji_1)) {
                                $db->query("INSERT INTO tb_acc_revisi (nip,nim,jenis_sidang,sebagai) VALUES ('" . $penguji1[0]->nip . "','" . $nim_mhs . "','skripsi','Penguji 1')");
                            }

                            $db->query("UPDATE `tb_nilai` SET nilai_ujian='80' where id_nilai='" . $cek_nilai_penguji1[0]->id_nilai . "'");
                        }


                        $cek_nilai_penguji2 = $db->query("SELECT * FROM tb_nilai where nim ='" . $nim_mhs . "' AND nip ='" . $penguji2[0]->nip . "' AND sebagai = 'penguji 2' ")->getResult();
                        if (empty($cek_nilai_penguji2)) {

                            // ttd berita acara
                            $berita_acara_penguji2 = $db->query("SELECT * FROM tb_berita_acara WHERE nim='" . $nim_mhs . "' AND nip='" . $penguji2[0]->nip . "' AND sebagai='" . 'penguji 2' . "' AND status='ditandatangani' AND jenis_sidang='skripsi' AND id_pendaftar='$id_pendaftar' ")->getResult();
                            if (empty($berita_acara_penguji2)) {
                                $db->query("INSERT INTO tb_berita_acara (nim,nip,sebagai,status,jenis_sidang,id_pendaftar) VALUES ('" . $nim_mhs . "','" . $penguji2[0]->nip . "','penguji 2','ditandatangani','skripsi','$id_pendaftar')");
                            } else {
                                $db->query("UPDATE tb_berita_acara SET status= 'ditandatangani' WHERE nim='" . $nim_mhs . "' AND nip='" . $penguji2[0]->nip . "' AND sebagai='" . 'penguji 2' . "' AND jenis_sidang='skripsi' AND id_pendaftar='$id_pendaftar'");
                            }

                            // acc revisi
                            $cek_acc_penguji_2 = $db->query("SELECT * FROM `tb_acc_revisi` WHERE nim ='" . $nim_mhs . "' AND nip = '" . $penguji2[0]->nip . "' AND sebagai = 'Penguji 2' AND jenis_sidang='skripsi'")->getResult();
                            if (empty($cek_acc_penguji_2)) {
                                $db->query("INSERT INTO tb_acc_revisi (nip,nim,jenis_sidang,sebagai) VALUES ('" . $penguji2[0]->nip . "','" . $nim_mhs . "','skripsi','Penguji 2')");
                            }

                            $db->query("INSERT INTO tb_nilai (nim,nip,sebagai,nilai_ujian) VALUES ('" . $nim_mhs . "','" . $penguji2[0]->nip . "','penguji 2','80')");
                        } elseif (empty($cek_nilai_penguji2[0]->nilai_ujian)) {
                            // ttd berita acara
                            $berita_acara_penguji2 = $db->query("SELECT * FROM tb_berita_acara WHERE nim='" . $nim_mhs . "' AND nip='" . $penguji2[0]->nip . "' AND sebagai='" . 'penguji 2' . "' AND status='ditandatangani' AND jenis_sidang='skripsi' AND id_pendaftar='$id_pendaftar'")->getResult();
                            if (empty($berita_acara_penguji2)) {
                                $db->query("INSERT INTO tb_berita_acara (nim,nip,sebagai,status,jenis_sidang,id_pendaftar) VALUES ('" . $nim_mhs . "','" . $penguji2[0]->nip . "','penguji 2','ditandatangani','skripsi','$id_pendaftar')");
                            } else {
                                $db->query("UPDATE tb_berita_acara SET status= 'ditandatangani' WHERE nim='" . $nim_mhs . "' AND nip='" . $penguji2[0]->nip . "' AND sebagai='" . 'penguji 2' . "' AND jenis_sidang='skripsi' AND id_pendaftar='$id_pendaftar'");
                            }

                            // acc revisi
                            $cek_acc_penguji_2 = $db->query("SELECT * FROM `tb_acc_revisi` WHERE nim ='" . $nim_mhs . "' AND nip = '" . $penguji2[0]->nip . "' AND sebagai = 'Penguji 2' AND jenis_sidang='skripsi'")->getResult();
                            if (empty($cek_acc_penguji_2)) {
                                $db->query("INSERT INTO tb_acc_revisi (nip,nim,jenis_sidang,sebagai) VALUES ('" . $penguji2[0]->nip . "','" . $nim_mhs . "','skripsi','Penguji 2')");
                            }

                            $db->query("UPDATE `tb_nilai` SET nilai_ujian='80' where id_nilai='" . $cek_nilai_penguji2[0]->id_nilai . "'");
                            // $db->query("INSERT INTO tb_nilai (nim,nip,sebagai,nilai_ujian) VALUES ('" . $nim_mhs . "','" . $penguji2[0]->nip . "','penguji 2','80')");
                        }


                        $cek_nilai_penguji3 = $db->query("SELECT * FROM tb_nilai where nim ='" . $nim_mhs . "' AND nip ='" . $penguji3[0]->nip . "' AND sebagai = 'penguji 3' ")->getResult();
                        if (empty($cek_nilai_penguji3)) {

                            // ttd berita acara
                            $berita_acara_penguji3 = $db->query("SELECT * FROM tb_berita_acara WHERE nim='" . $nim_mhs . "' AND nip='" . $penguji3[0]->nip . "' AND sebagai='" . 'penguji 3' . "' AND status='ditandatangani' AND jenis_sidang='skripsi' AND id_pendaftar='$id_pendaftar'")->getResult();
                            if (empty($berita_acara_penguji3)) {
                                $db->query("INSERT INTO tb_berita_acara (nim,nip,sebagai,status,jenis_sidang,id_pendaftar) VALUES ('" . $nim_mhs . "','" . $penguji3[0]->nip . "','penguji 3','ditandatangani','skripsi','$id_pendaftar')");
                            } else {
                                $db->query("UPDATE tb_berita_acara SET status= 'ditandatangani' WHERE nim='" . $nim_mhs . "' AND nip='" . $penguji3[0]->nip . "' AND sebagai='" . 'penguji 3' . "' AND jenis_sidang='skripsi' AND id_pendaftar='$id_pendaftar'");
                            }

                            // acc revisi
                            $cek_acc_penguji_3 = $db->query("SELECT * FROM `tb_acc_revisi` WHERE nim ='" . $nim_mhs . "' AND nip = '" . $penguji3[0]->nip . "' AND sebagai = 'Penguji 3' AND jenis_sidang='skripsi'")->getResult();
                            if (empty($cek_acc_penguji_3)) {
                                $db->query("INSERT INTO tb_acc_revisi (nip,nim,jenis_sidang,sebagai) VALUES ('" . $penguji3[0]->nip . "','" . $nim_mhs . "','skripsi','Penguji 3')");
                            }

                            $db->query("INSERT INTO tb_nilai (nim,nip,sebagai,nilai_ujian) VALUES ('" . $nim_mhs . "','" . $penguji3[0]->nip . "','penguji 3','80')");
                        } elseif (empty($cek_nilai_penguji3[0]->nilai_ujian)) {

                            // ttd berita acara
                            $berita_acara_penguji3 = $db->query("SELECT * FROM tb_berita_acara WHERE nim='" . $nim_mhs . "' AND nip='" . $penguji3[0]->nip . "' AND sebagai='" . 'penguji 3' . "' AND status='ditandatangani' AND jenis_sidang='skripsi' AND id_pendaftar='$id_pendaftar'")->getResult();
                            if (empty($berita_acara_penguji3)) {
                                $db->query("INSERT INTO tb_berita_acara (nim,nip,sebagai,status,jenis_sidang,id_pendaftar) VALUES ('" . $nim_mhs . "','" . $penguji3[0]->nip . "','penguji 3','ditandatangani','skripsi','$id_pendaftar')");
                            } else {
                                $db->query("UPDATE tb_berita_acara SET status= 'ditandatangani' WHERE nim='" . $nim_mhs . "' AND nip='" . $penguji3[0]->nip . "' AND sebagai='" . 'penguji 3' . "' AND jenis_sidang='skripsi' AND id_pendaftar='$id_pendaftar'");
                            }

                            // acc revisi
                            $cek_acc_penguji_3 = $db->query("SELECT * FROM `tb_acc_revisi` WHERE nim ='" . $nim_mhs . "' AND nip = '" . $penguji3[0]->nip . "' AND sebagai = 'Penguji 3' AND jenis_sidang='skripsi'")->getResult();
                            if (empty($cek_acc_penguji_3)) {
                                $db->query("INSERT INTO tb_acc_revisi (nip,nim,jenis_sidang,sebagai) VALUES ('" . $penguji3[0]->nip . "','" . $nim_mhs . "','skripsi','Penguji 3')");
                            }

                            $db->query("UPDATE `tb_nilai` SET nilai_ujian='80' where id_nilai='" . $cek_nilai_penguji3[0]->id_nilai . "'");
                            // $db->query("INSERT INTO tb_nilai (nim,nip,sebagai,nilai_ujian) VALUES ('" . $nim_mhs . "','" . $penguji3[0]->nip . "','penguji 3','80')");
                        }


                        $cek_nilai_pembimbing1 =  $db->query("SELECT * FROM tb_nilai where nim ='" . $nim_mhs . "' AND sebagai = 'pembimbing 1' ")->getResult();
                        if (empty($cek_nilai_pembimbing1)) {
                            $berita_acara_pem1 = $db->query("SELECT * FROM tb_berita_acara WHERE nim='" . $nim_mhs . "' AND nip='" . $dosen_pembimbing[0]->nip . "' AND sebagai='" . 'pembimbing 1' . "' AND status='ditandatangani' AND jenis_sidang='skripsi' AND id_pendaftar='$id_pendaftar'")->getResult();
                            if (empty($berita_acara_pem1)) {
                                $db->query("INSERT INTO tb_berita_acara (nim,nip,sebagai,status,jenis_sidang,id_pendaftar) VALUES ('" . $nim_mhs . "','" . $dosen_pembimbing[0]->nip . "','pembimbing 1','ditandatangani','skripsi','$id_pendaftar')");
                            } else {
                                $db->query("UPDATE tb_berita_acara SET status= 'ditandatangani' WHERE nim='" . $nim_mhs . "' AND nip='" . $dosen_pembimbing[0]->nip . "' AND sebagai='" . 'pembimbing 1' . "' AND jenis_sidang='skripsi' AND id_pendaftar='$id_pendaftar'");
                            }
                            $db->query("UPDATE tb_pendaftar_sidang SET hasil_sidang='2' WHERE id_pendaftar='$id_pendaftar'");
                            $db->query("INSERT INTO tb_nilai (nim,nip,sebagai,nilai_ujian,nilai_bimbingan) VALUES ('" . $nim_mhs . "','" . $dosen_pembimbing[0]->nip . "','pembimbing 1','80','80')  ");
                        } elseif (empty($cek_nilai_pembimbing1[0]->nilai_bimbingan) || empty($cek_nilai_pembimbing1[0]->nilai_ujian)) {
                            $berita_acara_pem1 = $db->query("SELECT * FROM tb_berita_acara WHERE nim='" . $nim_mhs . "' AND nip='" . $dosen_pembimbing[0]->nip . "' AND sebagai='" . 'pembimbing 1' . "' AND status='ditandatangani' AND jenis_sidang='skripsi' AND id_pendaftar='$id_pendaftar'")->getResult();
                            if (empty($berita_acara_pem1)) {
                                $db->query("INSERT INTO tb_berita_acara (nim,nip,sebagai,status,jenis_sidang,id_pendaftar) VALUES ('" . $nim_mhs . "','" . $dosen_pembimbing[0]->nip . "','pembimbing 1','ditandatangani','skripsi','$id_pendaftar')");
                            } else {
                                $db->query("UPDATE tb_berita_acara SET status= 'ditandatangani' WHERE nim='" . $nim_mhs . "' AND nip='" . $dosen_pembimbing[0]->nip . "' AND sebagai='" . 'pembimbing 1' . "' AND jenis_sidang='skripsi' AND id_pendaftar='$id_pendaftar'");
                            }
                            $db->query("UPDATE tb_pendaftar_sidang SET hasil_sidang='2' WHERE id_pendaftar='$id_pendaftar'");
                            $db->query("UPDATE `tb_nilai` SET nilai_ujian='80', nilai_bimbingan='80' where id_nilai='" . $cek_nilai_pembimbing1[0]->id_nilai . "'");
                            // $db->query("INSERT INTO tb_nilai (nim,nip,sebagai,nilai_ujian,nilai_bimbingan) VALUES ('" . $nim_mhs . "','" . $dosen_pembimbing[0]->nip . "','pembimbing 1','80','80')");
                        }


                        $cek_nilai_pembimbing2 =  $db->query("SELECT * FROM tb_nilai where nim ='" . $nim_mhs . "' AND sebagai = 'pembimbing 2' ")->getResult();
                        if (empty($cek_nilai_pembimbing2)) {
                            // ttd berita acara
                            $berita_acara_pem2 = $db->query("SELECT * FROM tb_berita_acara WHERE nim='" . $nim_mhs . "' AND nip='" . $dosen_pembimbing[1]->nip . "' AND sebagai='" . 'pembimbing 2' . "' AND status='ditandatangani' AND jenis_sidang='skripsi' AND id_pendaftar='$id_pendaftar'")->getResult();
                            if (empty($berita_acara_pem2)) {
                                $db->query("INSERT INTO tb_berita_acara (nim,nip,sebagai,status,jenis_sidang,id_pendaftar) VALUES ('" . $nim_mhs . "','" . $dosen_pembimbing[1]->nip . "','pembimbing 2','ditandatangani','skripsi','$id_pendaftar')");
                            } else {
                                $db->query("UPDATE tb_berita_acara SET status= 'ditandatangani' WHERE nim='" . $nim_mhs . "' AND nip='" . $dosen_pembimbing[1]->nip . "' AND sebagai='" . 'pembimbing 2' . "' AND jenis_sidang='skripsi' AND id_pendaftar='$id_pendaftar'");
                            }
                            // input nilai default 80 ke tb_nilai
                            $db->query("INSERT INTO tb_nilai (nim,nip,sebagai,nilai_ujian,nilai_bimbingan) VALUES ('" . $nim_mhs . "','" . $dosen_pembimbing[1]->nip . "','pembimbing 2','80','80')  ");
                        } elseif (empty($cek_nilai_pembimbing2[0]->nilai_bimbingan) || empty($cek_nilai_pembimbing2[0]->nilai_ujian)) {
                            $berita_acara_pem2 = $db->query("SELECT * FROM tb_berita_acara WHERE nim='" . $nim_mhs . "' AND nip='" . $dosen_pembimbing[1]->nip . "' AND sebagai='" . 'pembimbing 2' . "' AND status='ditandatangani' AND jenis_sidang='skripsi' AND id_pendaftar='$id_pendaftar'")->getResult();
                            if (empty($berita_acara_pem2)) {
                                $db->query("INSERT INTO tb_berita_acara (nim,nip,sebagai,status,jenis_sidang,id_pendaftar) VALUES ('" . $nim_mhs . "','" . $dosen_pembimbing[1]->nip . "','pembimbing 2','ditandatangani','skripsi','$id_pendaftar')");
                            } else {
                                $db->query("UPDATE tb_berita_acara SET status= 'ditandatangani' WHERE nim='" . $nim_mhs . "' AND nip='" . $dosen_pembimbing[1]->nip . "' AND sebagai='" . 'pembimbing 2' . "' AND jenis_sidang='skripsi' AND id_pendaftar='$id_pendaftar'");
                            }

                            $db->query("UPDATE `tb_nilai` SET nilai_ujian='80', nilai_bimbingan='80' where id_nilai='" . $cek_nilai_pembimbing2[0]->id_nilai . "'");

                            // $db->query("INSERT INTO tb_nilai (nim,nip,sebagai,nilai_ujian,nilai_bimbingan) VALUES ('" . $nim_mhs . "','" . $dosen_pembimbing[1]->nip . "','pembimbing 2','80','80')");
                        }
                    }
                }
            }
        }
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return mixed
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
