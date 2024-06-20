<?php

namespace Config;

use DeepCopy\Filter\Filter;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (is_file(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Login');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
$routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
// -----------------------------------------AUTH---------------------------------------
//Route Khusus Controller Auth-Login
// $routes->get('/', 'Welcome::google');
// $routes->add('/testapi', 'Welcome::testapi');

// $routes->add('/', 'Home::perbaikan');

$routes->add('/', 'Auth\Login::index');
$routes->add('/php-info', 'Home::phpinfo');
$routes->add('/loginkhusus/(:any)', 'Auth\Login::khusus/$1');
$routes->add('/proses_login', 'Auth\Login::proses_login');
$routes->add('/redirect', 'Auth\Login::redirect');
$routes->add('/success_redirect', 'Auth\Login::success');
//Route Khusus Controller Auth-Logout
$routes->add('/logout', 'Auth\Logout::index');
// -----------------------------------------AKUN---------------------------------------
$routes->add('/profil', 'Akun\Profil::index');
$routes->add('/edit_profil', 'Akun\Edit_Profil::index');
$routes->add('/proses_edit_profil', 'Akun\Edit_Profil::proses');
$routes->add('/setting', 'Akun\Setting::index');
$routes->add('/update_pass', 'Akun\Setting::update_pass');
$routes->add('/update_universal_pass', 'Akun\Setting::update_universal_pass');
$routes->add('/clear_recycle_bin', 'Akun\Setting::clear_recycle_bin');
$routes->add('/delete_data_mhs', 'Akun\Setting::delete_data_mhs');
// ---------------------------------------ADMIN-------------------------------------------
//Route Khusus Controller Admin-Beranda
$routes->add('/beranda_admin', 'Admin\Beranda::index');
//Route Khusus Controller Admin-Mahasiswa
$routes->add('/data_mahasiswa', 'Admin\Mahasiswa::index');
$routes->add('/data_mahasiswa2', 'Admin\Mahasiswa::index2');
$routes->add('/update_data_mhs', 'Admin\Mahasiswa::update_data_mhs');
// $routes->add('/update_data_mhs/(:any)', 'Admin\Mahasiswa::update_data_mhs/$1');
$routes->get('/jurusan_mhs/(:any)', 'Admin\Mahasiswa::jurusan_mhs/$1');
$routes->get('/prodi_mhs/(:any)', 'Admin\Mahasiswa::prodi_mhs/$1');
$routes->get('/detail_data_mhs/(:any)/(:any)', 'Admin\Mahasiswa::detail_data_mhs/$1/$2');
$routes->get('/angkatan_mhs/(:any)', 'Admin\Mahasiswa::angkatan_mhs/$1');
//Route Khusus Controller Admin-Dosen
$routes->add('/data_dosen', 'Admin\Dosen::index');
$routes->add('/update_data_dosen', 'Admin\Dosen::update_data_dosen');
$routes->add('/update_data_dosen_perprodi/(:any)', 'Admin\Dosen::update_data_dosen_perprodi/$1');
$routes->get('/jurusan_dosen/(:any)', 'Admin\Dosen::jurusan_dosen/$1');
$routes->get('/prodi_dosen/(:any)', 'Admin\Dosen::prodi_dosen/$1');
$routes->add('/detail_data_dosen/(:any)', 'Admin\Dosen::detail_data_dosen/$1');
$routes->add('/delete_dosen', 'Admin\Dosen::delete');
$routes->add('/edit_dosen', 'Admin\Dosen::edit');
$routes->add('/add_dosen', 'Admin\Dosen::add');
//Route Khusus Controller Admin-unit
$routes->add('/data_unit', 'Admin\Unit::index');
$routes->add('/update_data_unit', 'Admin\Unit::update_data_unit');
//Route Khusus Controller Admin-Periode
$routes->add('/data_periode', 'Admin\Periode::index');
$routes->add('/update_data_periode', 'Admin\Periode::update_data_periode');
//ROute Khusus Controller Admin-Koorprodi
$routes->add('/data_korprodi', 'Admin\Korprodi::index');
$routes->add('/add_korprodi', 'Admin\Korprodi::add');
$routes->add('/delete_korprodi', 'Admin\Korprodi::delete');
//ROute Khusus Controller Admin-Akun Khusus
$routes->add('/data_akun_khusus', 'Admin\Akun_Khusus::index');
$routes->add('/add_akun_khusus', 'Admin\Akun_Khusus::add');
$routes->add('/delete_akun_khusus', 'Admin\Akun_Khusus::delete');
//ROute Khusus Controller Admin-Akademik
$routes->add('/data_admin_akademik', 'Admin\Admin_Akademik::index');
$routes->add('/add_admin_akademik', 'Admin\Admin_Akademik::add');
// $routes->add('/edits_admin_akademik', 'Admin\Admin_Akademik::edit');
$routes->add('/edit_admin_akademik', 'Admin\Admin_Akademik::edit');
$routes->add('/delete_admin_akademik', 'Admin\Admin_Akademik::delete');

// -------------------------------------MAHASISWA-----------------------------------------
//Route Khusus Controller Mahasiswa-Beranda
$routes->add('/beranda_mahasiswa', 'Mahasiswa\Beranda::index', ['filter' => 'updateNilai']);
// $routes->add('/beranda_mahasiswa', 'Mahasiswa\Beranda::index');
//Route Khusus Controller Mahasiswa-Ajukan_Topik
$routes->add('/ajukan_topik_mahasiswa', 'Mahasiswa\Ajukan_Topik::index');
$routes->add('/ajukan_dospem_1', 'Mahasiswa\Ajukan_Topik::ajukan_dospem_1');
$routes->add('/batal_ajukan_dospem_1/(:any)', 'Mahasiswa\Ajukan_Topik::batal_ajukan_dospem_1/$1');
$routes->add('/ajukan_dospem_2', 'Mahasiswa\Ajukan_Topik::ajukan_dospem_2');
$routes->add('/batal_ajukan_dospem_2/(:any)', 'Mahasiswa\Ajukan_Topik::batal_ajukan_dospem_2/$1');
$routes->add('/proses_ajukan_topik', 'Mahasiswa\Ajukan_Topik::proses_ajukan_topik');
//Route Khusus Controller Mahasiswa-Bimbingan Proposal
$routes->get('/bimbingan_proposal/(:any)', 'Mahasiswa\Proposal\Bimbingan::index/$1');
$routes->get('/reload_m_bimbingan_proposal/(:any)', 'Mahasiswa\Proposal\Bimbingan::reload_m/$1');
$routes->add('/tambah_bimbingan_proposal', 'Mahasiswa\Proposal\Bimbingan::tambah');
$routes->add('/hapus_bimbingan', 'Mahasiswa\Proposal\Bimbingan::hapus');
$routes->add('/download_berkas_bimbingan', 'Mahasiswa\Proposal\Bimbingan::download_berkas');
//Route Khusus Controller Mahasiswa-DaftarSeminar
$routes->add('/daftar_seminar', 'Mahasiswa\Proposal\Daftar_Seminar::index');
$routes->add('/izin_seminar', 'Mahasiswa\Proposal\Daftar_Seminar::izin');
$routes->add('/mendaftar_seminar', 'Mahasiswa\Proposal\Daftar_Seminar::mendaftar');
//Route Khusus Controller Mahasiswa-Bimbingan Revisi Proposal
$routes->get('/bimbingan_revisi_proposal/(:any)', 'Mahasiswa\Proposal\Revisi::index/$1');
$routes->get('/reload_m_bimbingan_revisi_proposal/(:any)', 'Mahasiswa\Proposal\Revisi::reload_m/$1');
$routes->add('/tambah_bimbingan_revisi_proposal', 'Mahasiswa\Proposal\Revisi::tambah');
$routes->add('/hapus_bimbingan_revisi_proposal', 'Mahasiswa\Proposal\Revisi::hapus');
//Route Khusus Controller Mahasiswa-Bimbingan Skripsi
$routes->get('/bimbingan_skripsi/(:any)', 'Mahasiswa\Skripsi\Bimbingan::index/$1');
$routes->get('/reload_m_bimbingan_skripsi/(:any)', 'Mahasiswa\Skripsi\Bimbingan::reload_m/$1');
$routes->add('/tambah_bimbingan_skripsi', 'Mahasiswa\Skripsi\Bimbingan::tambah');
$routes->add('/hapus_bimbingan_skripsi', 'Mahasiswa\Skripsi\Bimbingan::hapus');
//Route Khusus Controller Mahasiswa-Bimbingan Revisi Proposal
$routes->get('/bimbingan_revisi_skripsi/(:any)', 'Mahasiswa\Skripsi\Revisi::index/$1');
$routes->get('/reload_m_bimbingan_revisi_skripsi/(:any)', 'Mahasiswa\Skripsi\Revisi::reload_m/$1');
$routes->add('/tambah_bimbingan_revisi_skripsi', 'Mahasiswa\Skripsi\Revisi::tambah');
$routes->add('/hapus_bimbingan_revisi_skripsi', 'Mahasiswa\Skripsi\Revisi::hapus');
//Route Khusus Controller Mahasiswa-DaftarSidangSkripsi
$routes->add('/daftar_sidang', 'Mahasiswa\Skripsi\Daftar_Sidang::index');
$routes->add('/izin_sidang', 'Mahasiswa\Skripsi\Daftar_Sidang::izin');
$routes->add('/mendaftar_sidang', 'Mahasiswa\Skripsi\Daftar_Sidang::mendaftar');
//Route Khusus Controller Mahasiswa-InformasiNilai
$routes->add('/informasi_nilai', 'Mahasiswa\Informasi_nilai::index');
// --------------------------------------DOSEN-------------------------------------------
//Route Khusus Controller Dosen-Beranda
$routes->add('/beranda_dosen', 'Dosen\Beranda::index', ['filter' => 'updateNilaiDosen']);
// $routes->add('/beranda_dosen', 'Dosen\Beranda::index');
//Route Khusus Controller Dosen-Proposal-Validasi_Usulan
$routes->add('/validasi_usulan', 'Dosen\Proposal\Validasi_Usulan::index');
$routes->get('/setujui_validasi_usulan/(:any)', 'Dosen\Proposal\Validasi_Usulan::setujui_validasi/$1');
$routes->add('/tolak_validasi_usulan', 'Dosen\Proposal\Validasi_Usulan::tolak_validasi');
$routes->get('/download_proposal/(:any)', 'Dosen\Proposal\Validasi_Usulan::download/$1');
//Route Khusus Controller Dosen-Bimbingan Proposal
$routes->add('/data_mahasiswa_bimbingan_proposal', 'Dosen\Proposal\Bimbingan::index');
$routes->get('/bimbingan_proposal_dosen/(:any)', 'Dosen\Proposal\Bimbingan::bimbingan_proposal_dosen/$1');
$routes->get('/reload_m_bimbingan_proposal_dosen/(:any)', 'Dosen\Proposal\Bimbingan::reload_m_dosen/$1');
$routes->add('/hapus_bimbingan_dosen', 'Dosen\Proposal\Bimbingan::hapus');
$routes->add('/tambah_bimbingan_proposal_dosen', 'Dosen\Proposal\Bimbingan::tambah');
//Route Khusus Controller Dosen-Berita Acara
$routes->add('/berita_acara_seminar', 'Dosen\Proposal\Berita_Acara::index');
$routes->add('/tandatangani_proposal', 'Dosen\Proposal\Berita_Acara::ttd');
$routes->add('/berita_acara_skripsi', 'Dosen\Skripsi\Berita_Acara::index');
$routes->add('/tandatangani_skripsi', 'Dosen\Skripsi\Berita_Acara::ttd');
$routes->add('/berita_acara_proposal_download_file/(:any)/(:any)', 'Dosen\Proposal\Berita_Acara::download/$1/$2');
//Route Khusus Controller Dosen-Validasi Daftar Seminar
$routes->add('/validasi_daftar_seminar', 'Dosen\Proposal\Validasi_Daftar_Seminar::index');
$routes->add('/proses_validasi_daftar_seminar', 'Dosen\Proposal\Validasi_Daftar_Seminar::validasi');
//Route Khusus Controller Dosen-Bimbingan Revisi Proposal
$routes->add('/data_mahasiswa_bimbingan_revisi_proposal', 'Dosen\Proposal\Revisi::index');
$routes->get('/bimbingan_revisi_proposal_dosen/(:any)', 'Dosen\Proposal\Revisi::bimbingan_proposal_dosen/$1');
$routes->get('/reload_m_bimbingan_revisi_proposal_dosen/(:any)', 'Dosen\Proposal\Revisi::reload_m_dosen/$1');
$routes->add('/hapus_bimbingan_revisi_proposal_dosen', 'Dosen\Proposal\Revisi::hapus');
$routes->add('/tambah_bimbingan_revisi_proposal_dosen', 'Dosen\Proposal\Revisi::tambah');
$routes->add('/acc_revisi_proposal_dosen', 'Dosen\Proposal\Revisi::acc');
//Route Khusus Controller Dosen-Bimbingan Skripsi
$routes->add('/data_mahasiswa_bimbingan_skripsi', 'Dosen\Skripsi\Bimbingan::index');
$routes->get('/bimbingan_skripsi_dosen/(:any)', 'Dosen\Skripsi\Bimbingan::bimbingan_skripsi_dosen/$1');
$routes->get('/reload_m_bimbingan_skripsi_dosen/(:any)', 'Dosen\Skripsi\Bimbingan::reload_m_dosen/$1');
$routes->add('/hapus_bimbingan_skripsi_dosen', 'Dosen\Skripsi\Bimbingan::hapus');
$routes->add('/tambah_bimbingan_skripsi_dosen', 'Dosen\Skripsi\Bimbingan::tambah');
//Route Khusus Controller Dosen-Validasi Daftar Sidang
$routes->add('/validasi_daftar_sidang', 'Dosen\Skripsi\Validasi_Daftar_Sidang::index');
$routes->add('/proses_validasi_daftar_sidang', 'Dosen\Skripsi\Validasi_Daftar_Sidang::validasi');
//Route Khusus Controller Dosen-Bimbingan Revisi Skripsi
$routes->add('/data_mahasiswa_bimbingan_revisi_skripsi', 'Dosen\Skripsi\Revisi::index');
$routes->get('/bimbingan_revisi_skripsi_dosen/(:any)', 'Dosen\Skripsi\Revisi::bimbingan_skripsi_dosen/$1');
$routes->get('/reload_m_bimbingan_revisi_skripsi_dosen/(:any)', 'Dosen\Skripsi\Revisi::reload_m_dosen/$1');
$routes->add('/hapus_bimbingan_revisi_skripsi_dosen', 'Dosen\Skripsi\Revisi::hapus');
$routes->add('/tambah_bimbingan_revisi_skripsi_dosen', 'Dosen\Skripsi\Revisi::tambah');
$routes->add('/acc_revisi_skripsi_dosen', 'Dosen\Skripsi\Revisi::acc');
//Route Khusus Controller Dosen-Nilai
$routes->add('/input_nilai_bimbingan', 'Dosen\Nilai::index');
$routes->add('/save_nilai_bimbingan', 'Dosen\Nilai::save_nilai_bimbingan');
$routes->add('/input_nilai_skripsi', 'Dosen\Nilai::nilai_skripsi');
$routes->add('/save_nilai_skripsi', 'Dosen\Nilai::save_nilai_skripsi');
// Routes riwayat bimbingan
$routes->add('/riwayat_bimbingan', 'Dosen\Riwayat_bimbingan::index');
$routes->add('/direct_riwayat_bimbingan', 'Dosen\Riwayat_bimbingan::direct_cetak');
$routes->add('/riwayat_bimbingan_cetak/(:any)/(:any)', 'Dosen\Riwayat_bimbingan::cetak/$1/$2');

// --------------------------------------KORPRODI-------------------------------------------
//Route Khusus Controller Korprodi-Penjadwalan Sidang
$routes->add('/bidang_minat', 'Korprodi\Bidang_Minat::index');
$routes->add('/add_bidang_minat', 'Korprodi\Bidang_Minat::add');
$routes->add('/del_bidang_minat', 'Korprodi\Bidang_Minat::del');
$routes->add('/upd_bidang_minat', 'Korprodi\Bidang_Minat::upd');
//Route Khusus Controller Korprodi-Penjadwalan Sidang
$routes->add('/penjadwalan_sidang', 'Korprodi\Penjadwalan_Sidang::index');
$routes->add('/add_jadwal_sidang', 'Korprodi\Penjadwalan_Sidang::add');
$routes->add('/del_jadwal_sidang', 'Korprodi\Penjadwalan_Sidang::del');
$routes->add('/upd_jadwal_sidang', 'Korprodi\Penjadwalan_Sidang::upd');
$routes->add('/data_pendaftar', 'Korprodi\Penjadwalan_Sidang::data_pendaftar');
$routes->add('/edit_data_pendaftar/(:any)/(:any)/(:any)/(:any)/(:any)', 'Korprodi\Penjadwalan_Sidang::edit_data_pendaftar/$1/$2/$3/$4/$5');

$routes->add('/direct_cetak_pendaftar', 'Korprodi\Penjadwalan_Sidang::direct_cetak_pendaftar');
//Route Khusus Controller Korprodi-Validasi Daftar Seminar
$routes->add('/validasi_daftar_seminar_koor', 'Korprodi\Validasi_Daftar_Seminar::index');
$routes->add('/proses_validasi_daftar_seminar_koor', 'Korprodi\Validasi_Daftar_Seminar::validasi');
//Route Khusus Controller Koorprodi-Nilai
$routes->add('/daftar_nilai', 'Korprodi\Nilai::index');
$routes->add('/daftar_belum_dinilai', 'Korprodi\Nilai::belum_dinilai');
$routes->add('/daftar_sudah_dinilai', 'Korprodi\Nilai::sudah_dinilai');
$routes->add('/daftar_nilai_default', 'Korprodi\Nilai::nilai_default');
$routes->add('/update_nilai_korprodi', 'Korprodi\Nilai::update_nilai');
$routes->add('/export_nilai', 'Korprodi\Nilai::export');
$routes->get('/export_nilai_pdf/(:any)/(:any)', 'Korprodi\Nilai::export_pdf/$1/$2');
$routes->get('/export_sudah_nilai_pdf/(:any)/(:any)', 'Korprodi\Nilai::export_sudah_dinilai_pdf/$1/$2');
//Route Khusus Controller Koorprodi-Data dosesn
$routes->add('/data_dosen_koorprodi', 'Korprodi\Data_Dosen::index');
$routes->add('/update_kuota_dosen', 'Korprodi\Data_Dosen::update');

// --------------------------------------Admin Akademik-------------------------------------------
$routes->add('/admin_akademik_data_nilai', 'Admin_akademik\Data_Nilai::index');
$routes->add('/admin_akademik_data_skripsi', 'Admin_akademik\Data_Skripsi::index');
$routes->add('/export_nilai_admin_akademik', 'Admin_akademik\Data_Nilai::export');
$routes->add('/beranda_admin_akademik', 'Admin_akademik\Beranda::index');
$routes->add('/validasi_daftar_sidang_admin_akademik', 'Admin_akademik\Perizinan_sidang::index');
$routes->add('/validasi_admin_akademik', 'Admin_akademik\Perizinan_sidang::validasi');
$routes->add('/jadwal_sidang_akademik', 'Admin_akademik\Penjadwalan_Sidang::index');
$routes->add('/data_pendaftar_akademik', 'Admin_akademik\Penjadwalan_Sidang::data_pendaftar');




// --------------------------------------CETAK-------------------------------------------
$routes->add('/berkas_mhs_proposal', 'Cetak::berkas_mhs_proposal');
$routes->add('/berkas_mhs_skripsi', 'Cetak::berkas_mhs_skripsi');
$routes->get('/form_bimbingan_proposal/(:any)/(:any)', 'Cetak::form_bimbingan_proposal/$1/$2');
$routes->get('/berita_acara_proposal/(:any)', 'Cetak::berita_acara_proposal/$1');
$routes->get('/form_bimbingan_skripsi/(:any)/(:any)', 'Cetak::form_bimbingan_skripsi/$1/$2');
$routes->get('/berita_acara_skripsi/(:any)', 'Cetak::berita_acara_skripsi/$1');
$routes->get('/nilai_akhir_skripsi/(:any)', 'Cetak::nilai_akhir_skripsi/$1');
// $routes->get('/cetak_daftar_sudah_dinilai/(:any)', 'Cetak::export_pdf/$1');

$routes->get('/cetak_pendaftar/(:any)/(:any)/(:any)', 'Cetak::pendaftar/$1/$2/$3');

$routes->add('/direct_hasil_dosen', 'Cetak::direct_hasil_dosen');
$routes->get('/hasil_dosen/(:any)/(:any)', 'Cetak::hasil_dosen/$1/$2');
$routes->get('/hasil_dosen_pdf/(:any)/(:any)', 'Cetak::hasil_dosen_new/$1/$2');
$routes->get('/hasil_dosen_excel/(:any)/(:any)', 'Cetak::hasil_dosen_excel/$1/$2');
$routes->get('/hasil_dosen_excels/(:any)/(:any)', 'Cetak::hasil_dosen_excel_new/$1/$2');


$routes->get('/kasdi2udahljfaiosifkaksldkjakljflkjalkjljiwuoiureajkdajdfjajfdlaajflk', function () {
    return view('Login/login_new');
});

// ============================================================================================

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
