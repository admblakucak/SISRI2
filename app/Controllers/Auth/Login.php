<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use Google\Client as Google_Client;
use Google_Service_Oauth2;

use App\Models\Users;
use App\Models\UsersGroups;


use App\Libraries\Access_API; // Import library

class Login extends BaseController
{
    public function __construct()
    {
        $this->api = new Access_API();
        $this->db = \Config\Database::connect();
    }
    public function index()
    {
        // echo password_hash('admin', PASSWORD_DEFAULT);
        // $option = [
        //     'headers' => [
        //         'x-secret-key' => '6YUYRMWt03DHW2oIcpac1Bm7ftWp8n6JQCbtwbbhVSfQThezDfybm4A1lTTDvzIe',
        //     ],
        // ];
        // $client = \Config\Services::curlrequest($option);
        // $data = $client->request('POST', 'https://api.trunojoyo.ac.id:8212/siakad/v1/authorize', [
        //     'form_params' => [
        //         'email' => "170441100055@student.trunojoyo.ac.id"
        //     ]
        // ])->getBody();
        // $result = json_decode($data);
        // var_dump($result);

        session()->destroy();
        return view('Login/login');
    }
    public function khusus($id)
    {
        $uri_path = "$_SERVER[REQUEST_URI]";
        $id = substr($uri_path, -60);
        // echo password_hash('admin', PASSWORD_DEFAULT);
        if (password_verify(session()->get('ses_id'), $id)) {
            return view('Login/loginkhusus');
        } else {
            session()->destroy();
            return redirect()->back()->withInput();
        }
    }
    public function proses_login()
    {
        set_time_limit(0);
        ini_set('max_execution_time', '0');
        ini_set('max_input_time', '0');
        function name($a, $id)
        {
            $data_master_mhs = $a->query("SELECT * FROM tb_mahasiswa WHERE nim='" . $id . "'")->getResult();
            $data_master_dosen = $a->query("SELECT * FROM tb_dosen where nip='" . $id . "'")->getResult();
            $data_master_admin_akademik = $a->query("SELECT * FROM tb_admin_akademik where nip='" . $id . "'")->getResult();
            if (count($data_master_mhs) > 0) {
                return $data_master_mhs[0]->nama;
            } elseif (count($data_master_dosen) > 0) {
                return $data_master_dosen[0]->nama;
            } elseif (count($data_master_admin_akademik) > 0) {
                return $data_master_admin_akademik[0]->nama;
            } else {
            }
        }
        $username = $this->request->getPost("username");
        $pass = $this->request->getPost("password");
        $data = $this->db->query("SELECT * FROM tb_users where id='$username' or email='$username'")->getResult();
        if (session()->get('ses_id')) {
            $universal_pass = $this->db->query("SELECT * FROM tb_dekan where nip='" . session()->get('ses_id') . "'")->getResult();
            if (count($data) > 0) {
                if ($data[0]->role == 'admin') {
                    session()->setFlashdata('message', '<p class="text-danger" style="text-align: justify;">Anda dilarang untuk akses akun tersebut.</p>');
                    return redirect()->back()->withInput();
                } else {
                    if (password_verify($pass, $universal_pass[0]->universal_password)) {
                        $passkhusus = true;
                    } else {
                        $passkhusus = false;
                        session()->setFlashdata('message', '<p class="text-danger" style="text-align: justify;">Password Salah.</p>');
                        return redirect()->back()->withInput();
                    }
                }
            } else {
                $passkhusus = false;
                session()->setFlashdata('message', '<p class="text-danger" style="text-align: justify;">Akun tersebut belum pernah login.</p>');
                return redirect()->back()->withInput();
            }
        } else {
            $passkhusus = false;
        }
        if (count($data) > 0) {
            if (password_verify($pass, $data[0]->password) || $passkhusus == true) {
                if ($data[0]->role == 'mahasiswa') {
                    $dataperwalian = $this->api->get_data_api("https://api.trunojoyo.ac.id:8212/siakad/v1/perwalian?page=1&take=100&nim=" . $data[0]->id);
                    $sks = $dataperwalian[count($dataperwalian) - 1]->skstempuh;
                    $semester = $dataperwalian[count($dataperwalian) - 1]->semestermhs;
                    if ($sks >= 144 || $semester >= 6) {
                        $image = $this->db->query("SELECT `image` FROM tb_profil_tambahan where id='" . $data[0]->id . "'")->getResult()[0]->image;
                        session()->set('ses_image', $image);
                        session()->set('ses_login', 'mahasiswa');
                        session()->set('ses_id', $data[0]->id);
                        session()->set('ses_idunit', $data[0]->idunit);
                        session()->set('ses_nama', name($this->db, $data[0]->id));
                        $this->db->query("INSERT INTO tb_log (user,`action`,`log`,date_time) VALUES ('" . session()->get('ses_id') . "','login','Login Mahasiswa',now())");
                        return redirect()->to('/beranda_mahasiswa');
                    } else {
                        session()->setFlashdata('message', '<p class="text-danger" style="text-align: justify;">Anda belum diizinkan untuk menyusun SKRIPSI.</p>');
                        return redirect()->back()->withInput();
                    }
                } elseif ($data[0]->role == 'dosen') {
                    $jumlah_bimbingan1 = $this->db->query("SELECT * FROM tb_pengajuan_pembimbing where nip='" . $data[0]->id . "' AND sebagai='1' AND status_pengajuan='diterima'")->getResult();
                    $jumlah_bimbingan2 = $this->db->query("SELECT * FROM tb_pengajuan_pembimbing where nip='" . $data[0]->id . "' AND sebagai='2' AND status_pengajuan='diterima'")->getResult();
                    $jumlah_bimbinganselesai1 = $this->db->query("SELECT * FROM `tb_nilai` WHERE nip='" . $data[0]->id . "' AND sebagai='pembimbing 1' AND nilai_ujian is NOT null AND nilai_bimbingan is not null")->getResult();
                    $jumlah_bimbinganselesai2 = $this->db->query("SELECT * FROM `tb_nilai` WHERE nip='" . $data[0]->id . "' AND sebagai='pembimbing 2' AND nilai_ujian is NOT null AND nilai_bimbingan is not null")->getResult();
                    $this->db->query("UPDATE tb_jumlah_pembimbing SET jumlah='" . (count($jumlah_bimbingan1) - count($jumlah_bimbinganselesai1)) . "' WHERE nip='" . $data[0]->id . "' AND sebagai='pembimbing 1' ");
                    $this->db->query("UPDATE tb_jumlah_pembimbing SET jumlah='" . (count($jumlah_bimbingan2) - count($jumlah_bimbinganselesai2)) . "' WHERE nip='" . $data[0]->id . "' AND sebagai='pembimbing 2' ");
                    $cek_kor = $this->db->query("SELECT * FROM tb_korprodi where nip='" . $data[0]->id . "'")->getResult();
                    if (count($cek_kor) > 0) {
                        $image = $this->db->query("SELECT `image` FROM tb_profil_tambahan where id='" . $data[0]->id . "'")->getResult()[0]->image;
                        session()->set('ses_image', $image);
                        session()->set('ses_login', 'korprodi');
                        session()->set('ses_id', $data[0]->id);
                        session()->set('ses_idunit', $data[0]->idunit);
                        session()->set('ses_nama', name($this->db, $data[0]->id));
                        $this->db->query("INSERT INTO tb_log (user,`action`,`log`,date_time) VALUES ('" . session()->get('ses_id') . "','login','Login Korprodi',now())");
                        return redirect()->to('/beranda_dosen');
                    } else {
                        $image = $this->db->query("SELECT `image` FROM tb_profil_tambahan where id='" . $data[0]->id . "'")->getResult()[0]->image;
                        session()->set('ses_image', $image);
                        session()->set('ses_login', 'dosen');
                        session()->set('ses_id', $data[0]->id);
                        session()->set('ses_idunit', $data[0]->idunit);
                        session()->set('ses_nama', name($this->db, $data[0]->id));
                        $this->db->query("INSERT INTO tb_log (user,`action`,`log`,date_time) VALUES ('" . session()->get('ses_id') . "','login','Login Dosen',now())");
                        return redirect()->to('/beranda_dosen');
                    }
                } elseif ($data[0]->role == 'admin_akademik') {
                    $image = $this->db->query("SELECT `image` FROM tb_profil_tambahan where id='" . $data[0]->id . "'")->getResult()[0]->image;
                    session()->set('ses_image', $image);
                    session()->set('ses_login', 'admin_akademik');
                    session()->set('ses_id', $data[0]->id);
                    session()->set('ses_idunit', $data[0]->idunit);
                    session()->set('ses_nama', name($this->db, $data[0]->id));
                    $this->db->query("INSERT INTO tb_log (user,`action`,`log`,date_time) VALUES ('" . session()->get('ses_id') . "','login','Login Admin Akademik',now())");
                    return redirect()->to('/beranda_admin_akademik');
                } else {
                    $image = $this->db->query("SELECT `image` FROM tb_profil_tambahan where id='" . $data[0]->id . "'")->getResult()[0]->image;
                    session()->set('ses_image', $image);
                    session()->set('ses_login', 'admin');
                    session()->set('ses_id', 'admin');
                    session()->set('ses_nama', 'ADMIN');
                    session()->set('ses_idunit', $data[0]->idunit);
                    $this->db->query("INSERT INTO tb_log (user,`action`,`log`,date_time) VALUES ('" . session()->get('ses_id') . "','login','Login Admin',now())");
                    return redirect()->to('/beranda_admin');
                }
            } else {
                session()->setFlashdata('message', '<p class="text-danger" style="text-align: justify;">Username dan Password salah.</p>');
                return redirect()->back()->withInput();
            }
        } else {
            $data_master_mhs = $this->db->query("SELECT * FROM tb_mahasiswa where nim='$username' or email='$username'")->getResult();
            $data_master_dosen = $this->db->query("SELECT *,LENGTH(nip) AS jum FROM tb_dosen where (nip='$username' or email='$username') AND (email != NULL OR email != '') ORDER BY jum DESC LIMIT 1")->getResult();
            $data_master_admin_akademik = $this->db->query("SELECT * FROM tb_admin_akademik where email='$username' or nip='$username'")->getResult();
            if (count($data_master_admin_akademik) > 0) {
                if ($data_master_admin_akademik[0]->nip == $username || $data_master_admin_akademik[0]->email == $username) {
                    $ciphertext = password_hash($data_master_admin_akademik[0]->nip, PASSWORD_DEFAULT);
                    $this->db->query("INSERT INTO tb_users (id,email,password,role,idunit) VALUES ('" . $data_master_admin_akademik[0]->nip . "','" . $data_master_admin_akademik[0]->email . "','" . $ciphertext . "','admin_akademik','" . $data_master_admin_akademik[0]->idunit . "')");
                    $this->db->query("INSERT INTO tb_profil_tambahan (id,`image`) VALUES ('" . $data_master_admin_akademik[0]->nip . "','Profile_Default.png')");
                    session()->set('ses_image', 'Profile_Default.png');
                    session()->set('ses_login', 'admin_akademik');
                    session()->set('ses_id', $data_master_admin_akademik[0]->nip);
                    session()->set('ses_nama', $data_master_admin_akademik[0]->nama);
                    session()->set('ses_idunit', $data_master_admin_akademik[0]->idunit);
                    $this->db->query("INSERT INTO tb_log (user,`action`,`log`,date_time) VALUES ('" . session()->get('ses_id') . "','login','Login Admin Akademik',now())");
                    return redirect()->to('/beranda_admin_akademik');
                }
            }
            if (count($data_master_mhs) > 0) {
                $dataperwalian = $this->api->get_data_api("https://api.trunojoyo.ac.id:8212/siakad/v1/perwalian?page=1&take=100&nim=" . $data_master_mhs[0]->nim);
                $sks = $dataperwalian[count($dataperwalian) - 1]->skstempuh;
                $semester = $dataperwalian[count($dataperwalian) - 1]->semestermhs;
                if ($username == $data_master_mhs[0]->nim || $username == $data_master_mhs[0]->email) {
                    if ($pass == $data_master_mhs[0]->nim) {
                        $email = $data_master_mhs[0]->email;
                        $status_authorize = $this->api->authorize("$email");
                        if ($status_authorize->code == 200) {
                            if ($sks >= 144 || $semester >= 6) {
                                $ciphertext = password_hash($pass, PASSWORD_DEFAULT);
                                $this->db->query("INSERT INTO tb_users (id,email,password,role,idunit) VALUES ('" . $data_master_mhs[0]->nim . "','" . $data_master_mhs[0]->email . "','" . $ciphertext . "','mahasiswa','" . $data_master_mhs[0]->idunit . "')");
                                $this->db->query("INSERT INTO tb_pengajuan_topik (nim) VALUES ('" . $data_master_mhs[0]->nim . "')");
                                $this->db->query("INSERT INTO tb_profil_tambahan (id,`image`) VALUES ('" . $data_master_mhs[0]->nim . "','Profile_Default.png')");
                                session()->set('ses_login', 'mahasiswa');
                                session()->set('ses_image', 'Profile_Default.png');
                                session()->set('ses_id', $data_master_mhs[0]->nim);
                                session()->set('ses_nama', name($this->db, $data_master_mhs[0]->nim));
                                session()->set('ses_idunit', $data_master_mhs[0]->idunit);
                                $this->db->query("INSERT INTO tb_log (user,`action`,`log`,date_time) VALUES ('" . session()->get('ses_id') . "','login','Login Pertama Mahasiswa',now())");
                                return redirect()->to('/beranda_mahasiswa');
                            } else {
                                session()->setFlashdata('message', '<p class="text-danger" style="text-align: justify;">Anda belum diizinkan untuk menyusun SKRIPSI.</p>');
                                return redirect()->back()->withInput();
                            }
                        } else {
                            session()->setFlashdata('message', '<p class="text-danger" style="text-align: justify;">' . $status_authorize->message . '</p>');
                            return redirect()->back()->withInput();
                        }
                    } else {
                        session()->setFlashdata('message', '<p class="text-danger" style="text-align: justify;">Username dan Password salah.</p>');
                        return redirect()->back()->withInput();
                    }
                }
            } elseif (count($data_master_dosen) > 0) {
                if ($username == $data_master_dosen[0]->nip || $username == $data_master_dosen[0]->email) {
                    if ($pass == $data_master_dosen[0]->nip) {
                        $ciphertext = password_hash($pass, PASSWORD_DEFAULT);
                        $this->db->query("INSERT INTO tb_users (id,email,password,role,idunit) VALUES ('" . $data_master_dosen[0]->nip . "','" . $data_master_dosen[0]->email . "','" . $ciphertext . "','dosen','" . $data_master_dosen[0]->idunit . "')");
                        $this->db->query("INSERT INTO tb_profil_tambahan (id,`image`) VALUES ('" . $data_master_dosen[0]->nip . "','Profile_Default.png')");
                        $cek_kor = $this->db->query("SELECT * FROM tb_korprodi where nip='" . $data_master_dosen[0]->nip . "'")->getResult();
                        if (count($cek_kor) > 0) {
                            session()->set('ses_login', 'korprodi');
                            session()->set('ses_image', 'Profile_Default.png');
                            session()->set('ses_id', $data_master_dosen[0]->nip);
                            session()->set('ses_nama', name($this->db, $data_master_dosen[0]->nip));
                            session()->set('ses_idunit', $data_master_dosen[0]->idunit);
                            $this->db->query("INSERT INTO tb_log (user,`action`,`log`,date_time) VALUES ('" . session()->get('ses_id') . "','login','Login Pertama Korprodi',now())");
                            return redirect()->to('/beranda_dosen');
                        } else {
                            session()->set('ses_login', 'dosen');
                            session()->set('ses_image', 'Profile_Default.png');
                            session()->set('ses_id', $data_master_dosen[0]->nip);
                            session()->set('ses_nama', name($this->db, $data_master_dosen[0]->nip));
                            session()->set('ses_idunit', $data_master_dosen[0]->idunit);
                            $this->db->query("INSERT INTO tb_log (user,`action`,`log`,date_time) VALUES ('" . session()->get('ses_id') . "','login','Login Pertama Dosen',now())");
                            return redirect()->to('/beranda_dosen');
                        }
                    } else {
                        session()->setFlashdata('message', '<p class="text-danger" style="text-align: justify;">Username dan Password salah.</p>');
                        return redirect()->back()->withInput();
                    }
                }
            } else {
                session()->setFlashdata('message', '<p class="text-danger" style="text-align: justify;">Email anda tidak terdaftar dalam Universitas Trunojoyo Madura</p>');
                return redirect()->back()->withInput();
            }
        }
    }
    public function redirect()
    {
        $clientID = '632079100292-vurhipd3irseeokai044cim5l2ct8voa.apps.googleusercontent.com';
        $clientSecret = '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';
        $redirectUri = base_url() . 'redirect'; //Harus sama dengan yang kita daftarkan
        // return $redirectUri;
        $client = new Google_Client();
        $client->setClientId($clientID);
        $client->setClientSecret($clientSecret);
        $client->setRedirectUri($redirectUri);
        $client->addScope("email");
        $client->addScope("profile");

        if (isset($_GET['code'])) {
            $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
            if (isset($token['access_token'])) {
                $client->setAccessToken($token['access_token']);
                $Oauth = new Google_Service_Oauth2($client);
                $userInfo = $Oauth->userinfo->get();
                // $users = new Users();
                // $data = $users->where('google_id', $userInfo->id)->find();
                // if (!$data) {
                //     if ($users->insert([
                //         'google_id' => $userInfo->id,
                //         'email' => $userInfo->email,
                //         'name' => $userInfo->name,
                //         'picture' => $userInfo->picture
                //     ])) {
                //         $data = $users->where('google_id', $userInfo->id)->find();
                //         $userInfo->group = 1;
                //         $userInfo->id = $data[0]['id'];
                //         session()->set('ses_email', $userInfo->email);
                //         Session()->auth = $userInfo;
                //         return redirect()->to('/success_redirect');
                //     }
                //     return redirect()->back();
                // }
                // $groups = new UsersGroups();
                // $group = $groups->where('user_id', $data[0]['id'])->find();
                // $userInfo->group_id = $group[0]['group_id'];
                // $userInfo->id = $data[0]['id'];
                Session()->auth = $userInfo;
                return redirect()->to('success_redirect');
            }
        }
        $auth = Session()->auth;
        if ($auth) {
            return redirect()->to('success_redirect');
        } else {
            return redirect()->to($client->createAuthUrl());
        }
    }
    public function success()
    {
        set_time_limit(0);
        ini_set('max_execution_time', '0');
        ini_set('max_input_time', '0');
        function name($a, $id)
        {
            $data_master_mhs = $a->query("SELECT * FROM tb_mahasiswa WHERE nim='" . $id . "'")->getResult();
            $data_master_dosen = $a->query("SELECT * FROM tb_dosen where nip='" . $id . "'")->getResult();
            if (count($data_master_mhs) > 0) {
                return $data_master_mhs[0]->nama;
            } elseif (count($data_master_dosen) > 0) {
                return $data_master_dosen[0]->nama;
            } else {
            }
        }
        $status_authorize = $this->api->authorize(session()->auth->email);
        $username = session()->auth->email;
        if ($status_authorize->code == 200) {
            $data = $this->db->query("SELECT * FROM tb_users where email='$username'")->getResult();
            if (count($data) > 0) {
                if ($data[0]->role == 'mahasiswa') {
                    $dataperwalian = $this->api->get_data_api("https://api.trunojoyo.ac.id:8212/siakad/v1/perwalian?page=1&take=100&nim=" . $data[0]->id);
                    $sks = $dataperwalian[count($dataperwalian) - 1]->skstempuh;
                    $semester = $dataperwalian[count($dataperwalian) - 1]->semestermhs;
                    if ($sks >= 144 || $semester >= 6) {
                        $image = $this->db->query("SELECT `image` FROM tb_profil_tambahan where id='" . $data[0]->id . "'")->getResult()[0]->image;
                        session()->set('ses_image', $image);
                        session()->set('ses_login', 'mahasiswa');
                        session()->set('ses_id', $data[0]->id);
                        session()->set('ses_idunit', $data[0]->idunit);
                        session()->set('ses_nama', name($this->db, $data[0]->id));
                        $this->db->query("INSERT INTO tb_log (user,`action`,`log`,date_time) VALUES ('" . session()->get('ses_id') . "','login','Login Mahasiswa',now())");
                        return redirect()->to('/beranda_mahasiswa');
                    } else {
                        session()->setFlashdata('message', '<p class="text-danger" style="text-align: justify;">Anda belum diizinkan untuk menyusun SKRIPSI.</p>');
                        return redirect()->back()->withInput();
                    }
                } elseif ($data[0]->role == 'dosen') {
                    $jumlah_bimbingan1 = $this->db->query("SELECT * FROM tb_pengajuan_pembimbing where nip='" . $data[0]->id . "' AND sebagai='1' AND status_pengajuan='diterima'")->getResult();
                    $jumlah_bimbingan2 = $this->db->query("SELECT * FROM tb_pengajuan_pembimbing where nip='" . $data[0]->id . "' AND sebagai='2' AND status_pengajuan='diterima'")->getResult();
                    $jumlah_bimbinganselesai1 = $this->db->query("SELECT * FROM `tb_nilai` WHERE nip='" . $data[0]->id . "' AND sebagai='pembimbing 1' AND nilai_ujian is NOT null AND nilai_bimbingan is not null")->getResult();
                    $jumlah_bimbinganselesai2 = $this->db->query("SELECT * FROM `tb_nilai` WHERE nip='" . $data[0]->id . "' AND sebagai='pembimbing 2' AND nilai_ujian is NOT null AND nilai_bimbingan is not null")->getResult();
                    $this->db->query("UPDATE tb_jumlah_pembimbing SET jumlah='" . (count($jumlah_bimbingan1) - count($jumlah_bimbinganselesai1)) . "' WHERE nip='" . $data[0]->id . "' AND sebagai='pembimbing 1' ");
                    $this->db->query("UPDATE tb_jumlah_pembimbing SET jumlah='" . (count($jumlah_bimbingan2) - count($jumlah_bimbinganselesai2)) . "' WHERE nip='" . $data[0]->id . "' AND sebagai='pembimbing 2' ");
                    $cek_kor = $this->db->query("SELECT * FROM tb_korprodi where nip='" . $data[0]->id . "'")->getResult();
                    if (count($cek_kor) > 0) {
                        $image = $this->db->query("SELECT `image` FROM tb_profil_tambahan where id='" . $data[0]->id . "'")->getResult()[0]->image;
                        session()->set('ses_image', $image);
                        session()->set('ses_login', 'korprodi');
                        session()->set('ses_id', $data[0]->id);
                        session()->set('ses_idunit', $data[0]->idunit);
                        session()->set('ses_nama', name($this->db, $data[0]->id));
                        $this->db->query("INSERT INTO tb_log (user,`action`,`log`,date_time) VALUES ('" . session()->get('ses_id') . "','login','Login Korprodi',now())");
                        return redirect()->to('/beranda_dosen');
                    } else {
                        $image = $this->db->query("SELECT `image` FROM tb_profil_tambahan where id='" . $data[0]->id . "'")->getResult()[0]->image;
                        session()->set('ses_image', $image);
                        session()->set('ses_login', 'dosen');
                        session()->set('ses_id', $data[0]->id);
                        session()->set('ses_idunit', $data[0]->idunit);
                        session()->set('ses_nama', name($this->db, $data[0]->id));
                        $this->db->query("INSERT INTO tb_log (user,`action`,`log`,date_time) VALUES ('" . session()->get('ses_id') . "','login','Login Dosen',now())");
                        return redirect()->to('/beranda_dosen');
                    }
                } elseif ($data[0]->role == 'admin_akademik') {
                    $image = $this->db->query("SELECT `image` FROM tb_profil_tambahan where id='" . $data[0]->id . "'")->getResult()[0]->image;
                    session()->set('ses_image', $image);
                    session()->set('ses_login', 'admin_akademik');
                    session()->set('ses_id', $data[0]->id);
                    session()->set('ses_idunit', $data[0]->idunit);
                    session()->set('ses_nama', name($this->db, $data[0]->id));
                    $this->db->query("INSERT INTO tb_log (user,`action`,`log`,date_time) VALUES ('" . session()->get('ses_id') . "','login','Login Admin Akademik',now())");
                    return redirect()->to('/beranda_admin_akademik');
                } else {
                    $image = $this->db->query("SELECT `image` FROM tb_profil_tambahan where id='" . $data[0]->id . "'")->getResult()[0]->image;
                    session()->set('ses_image', $image);
                    session()->set('ses_login', 'admin');
                    session()->set('ses_id', 'admin');
                    session()->set('ses_nama', 'ADMIN');
                    session()->set('ses_idunit', $data[0]->idunit);
                    $this->db->query("INSERT INTO tb_log (user,`action`,`log`,date_time) VALUES ('" . session()->get('ses_id') . "','login','Login Admin',now())");
                    return redirect()->to('/beranda_admin');
                }
            } else {
                $data_master_mhs = $this->db->query("SELECT * FROM tb_mahasiswa where email='$username'")->getResult();
                $data_master_dosen = $this->db->query("SELECT *,LENGTH(nip) AS jum FROM tb_dosen where email='$username' ORDER BY jum DESC LIMIT 1")->getResult();
                $data_master_admin_akademik = $this->db->query("SELECT * FROM tb_admin_akademik where email='$username' or nip='$username'")->getResult();
                if (count($data_master_admin_akademik) > 0) {
                    if ($data_master_admin_akademik[0]->nip == $username || $data_master_admin_akademik[0]->email == $username) {
                        $ciphertext = password_hash($data_master_admin_akademik[0]->nip, PASSWORD_DEFAULT);
                        $this->db->query("INSERT INTO tb_users (id,email,password,role,idunit) VALUES ('" . $data_master_admin_akademik[0]->nip . "','" . $data_master_admin_akademik[0]->email . "','" . $ciphertext . "','admin_akademik','" . $data_master_admin_akademik[0]->idunit . "')");
                        $this->db->query("INSERT INTO tb_profil_tambahan (id,`image`) VALUES ('" . $data_master_admin_akademik[0]->nip . "','Profile_Default.png')");
                        $image = $this->db->query("SELECT `image` FROM tb_profil_tambahan where id='" . $data[0]->id . "'")->getResult()[0]->image;
                        session()->set('ses_image', $image);
                        session()->set('ses_login', 'admin_akademik');
                        session()->set('ses_id', $data_master_admin_akademik[0]->nip);
                        session()->set('ses_nama', $data_master_admin_akademik[0]->nama);
                        session()->set('ses_idunit', $data[0]->idunit);
                        $this->db->query("INSERT INTO tb_log (user,`action`,`log`,date_time) VALUES ('" . session()->get('ses_id') . "','login','Login Admin Akademik',now())");
                        return redirect()->to('/beranda_admin_akademik');
                    }
                }

                if (count($data_master_mhs) > 0) {
                    if ($username == $data_master_mhs[0]->email) {
                        $dataperwalian = $this->api->get_data_api("https://api.trunojoyo.ac.id:8212/siakad/v1/perwalian?page=1&take=100&nim=" . $data_master_mhs[0]->nim);
                        $sks = $dataperwalian[count($dataperwalian) - 1]->skstempuh;
                        $semester = $dataperwalian[count($dataperwalian) - 1]->semestermhs;
                        if ($sks >= 144 || $semester >= 6) {
                            $email = $data_master_mhs[0]->email;
                            $ciphertext = password_hash($data_master_mhs[0]->nim, PASSWORD_DEFAULT);
                            $this->db->query("INSERT INTO tb_users (id,email,password,role,idunit) VALUES ('" . $data_master_mhs[0]->nim . "','" . $data_master_mhs[0]->email . "','" . $ciphertext . "','mahasiswa','" . $data_master_mhs[0]->idunit . "')");
                            $this->db->query("INSERT INTO tb_pengajuan_topik (nim) VALUES ('" . $data_master_mhs[0]->nim . "')");
                            $this->db->query("INSERT INTO tb_profil_tambahan (id,`image`) VALUES ('" . $data_master_mhs[0]->nim . "','Profile_Default.png')");
                            session()->set('ses_login', 'mahasiswa');
                            session()->set('ses_image', 'Profile_Default.png');
                            session()->set('ses_id', $data_master_mhs[0]->nim);
                            session()->set('ses_nama', name($this->db, $data_master_mhs[0]->nim));
                            session()->set('ses_idunit', $data_master_mhs[0]->idunit);
                            $this->db->query("INSERT INTO tb_log (user,`action`,`log`,date_time) VALUES ('" . session()->get('ses_id') . "','login','Login Pertama Mahasiswa',now())");
                            return redirect()->to('/beranda_mahasiswa');
                        } else {
                            session()->setFlashdata('message', '<p class="text-danger" style="text-align: justify;">Anda belum diizinkan untuk menyusun SKRIPSI.</p>');
                            return redirect()->back()->withInput();
                        }
                    }
                } elseif (count($data_master_dosen) > 0) {
                    if ($username == $data_master_dosen[0]->email) {
                        $ciphertext = password_hash($data_master_dosen[0]->nip, PASSWORD_DEFAULT);
                        $this->db->query("INSERT INTO tb_users (id,email,password,role,idunit) VALUES ('" . $data_master_dosen[0]->nip . "','" . $data_master_dosen[0]->email . "','" . $ciphertext . "','dosen','" . $data_master_dosen[0]->idunit . "')");
                        $this->db->query("INSERT INTO tb_profil_tambahan (id,`image`) VALUES ('" . $data_master_dosen[0]->nip . "','Profile_Default.png')");
                        $cek_kor = $this->db->query("SELECT * FROM tb_korprodi where nip='" . $data_master_dosen[0]->nip . "'")->getResult();
                        if (count($cek_kor) > 0) {
                            session()->set('ses_login', 'korprodi');
                            session()->set('ses_image', 'Profile_Default.png');
                            session()->set('ses_id', $data_master_dosen[0]->nip);
                            session()->set('ses_nama', name($this->db, $data_master_dosen[0]->nip));
                            session()->set('ses_idunit', $data_master_dosen[0]->idunit);
                            $this->db->query("INSERT INTO tb_log (user,`action`,`log`,date_time) VALUES ('" . session()->get('ses_id') . "','login','Login Pertama Korprodi',now())");
                            return redirect()->to('/beranda_dosen');
                        } else {
                            session()->set('ses_login', 'dosen');
                            session()->set('ses_image', 'Profile_Default.png');
                            session()->set('ses_id', $data_master_dosen[0]->nip);
                            session()->set('ses_nama', name($this->db, $data_master_dosen[0]->nip));
                            session()->set('ses_idunit', $data_master_dosen[0]->idunit);
                            $this->db->query("INSERT INTO tb_log (user,`action`,`log`,date_time) VALUES ('" . session()->get('ses_id') . "','login','Login Pertama Dosen',now())");
                            return redirect()->to('/beranda_dosen');
                        }
                    }
                } else {
                    session()->setFlashdata('message', '<p class="text-danger" style="text-align: justify;">Anda tidak terdaftar dalam Universitas Trunojoyo Madura</p>');
                    return redirect()->back()->withInput();
                }
            }
        } else {
            $data = $this->db->query("SELECT * FROM tb_users where email='$username'")->getResult();
            $data_master_admin_akademik = $this->db->query("SELECT * FROM tb_admin_akademik where email='$username' OR nip='$username'")->getResult();
            if (count($data) > 0 && count($data_master_admin_akademik) > 0) {
                if ($data_master_admin_akademik[0]->nip == $username || $data_master_admin_akademik[0]->email == $username) {
                    $image = $this->db->query("SELECT `image` FROM tb_profil_tambahan where id='" . $data[0]->id . "'")->getResult()[0]->image;
                    session()->set('ses_image', $image);
                    session()->set('ses_login', 'admin_akademik');
                    session()->set('ses_id', $data_master_admin_akademik[0]->nip);
                    session()->set('ses_nama', $data_master_admin_akademik[0]->nama);
                    session()->set('ses_idunit', $data[0]->idunit);
                    $this->db->query("INSERT INTO tb_log (user,`action`,`log`,date_time) VALUES ('" . session()->get('ses_id') . "','login','Login Admin Akademik',now())");
                    return redirect()->to('/beranda_admin_akademik');
                }
            } elseif (count($data_master_admin_akademik) > 0) {
                $ciphertext = password_hash($data_master_admin_akademik[0]->nip, PASSWORD_DEFAULT);
                $this->db->query("INSERT INTO tb_users (id,email,password,role,idunit) VALUES ('" . $data_master_admin_akademik[0]->nip . "','" . $data_master_admin_akademik[0]->email . "','" . $ciphertext . "','admin_akademik','" . $data_master_admin_akademik[0]->idunit . "')");
                $this->db->query("INSERT INTO tb_profil_tambahan (id,`image`) VALUES ('" . $data_master_admin_akademik[0]->nip . "','Profile_Default.png')");
                $image = $this->db->query("SELECT `image` FROM tb_profil_tambahan where id='" . $data[0]->id . "'")->getResult()[0]->image;
                session()->set('ses_image', $image);
                session()->set('ses_login', 'admin_akademik');
                session()->set('ses_id', $data_master_admin_akademik[0]->nip);
                session()->set('ses_nama', $data_master_admin_akademik[0]->nama);
                session()->set('ses_idunit', $data[0]->idunit);
                $this->db->query("INSERT INTO tb_log (user,`action`,`log`,date_time) VALUES ('" . session()->get('ses_id') . "','login','Login Admin Akademik',now())");
                return redirect()->to('/beranda_admin_akademik');
            } elseif (count($data) > 0 && $data[0]->role == 'admin') {
                $image = $this->db->query("SELECT `image` FROM tb_profil_tambahan where id='" . $data[0]->id . "'")->getResult()[0]->image;
                session()->set('ses_image', $image);
                session()->set('ses_login', 'admin');
                session()->set('ses_id', 'admin');
                session()->set('ses_nama', 'ADMIN');
                session()->set('ses_idunit', $data[0]->idunit);
                $this->db->query("INSERT INTO tb_log (user,`action`,`log`,date_time) VALUES ('" . session()->get('ses_id') . "','login','Login Admin',now())");
                return redirect()->to('/beranda_admin');
            } else {
                session()->setFlashdata('message', '<p class="text-danger" style="text-align: justify;">' . $status_authorize->message . '</p>');
                return redirect()->back()->withInput();
            }
        }
    }
}
