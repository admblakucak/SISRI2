<?php

namespace App\Controllers\Mahasiswa;

use App\Controllers\BaseController;

use App\Libraries\Access_API; // Import library

class Beranda extends BaseController
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
        $data = [
            'title' => 'Beranda Mahasiswa',
            'db' => $this->db
        ];
        return view('Mahasiswa/beranda_mahasiswa', $data);
        // return view('kosong', $data);
    }
}
