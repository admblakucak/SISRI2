<?php

namespace App\Controllers\Dosen;

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
        if (session()->get('ses_id') == '' || session()->get('ses_login') == 'mahasiswa') {
            return redirect()->to('/');
        }
        if (session()->get('ses_login') == 'dosen') {
            $title = 'Beranda Dosen';
        } else {
            $title = 'Beranda Koorprodi';
        }
        $data = [
            'title' => $title,
            'db' => $this->db,
        ];
        return view('Dosen/beranda_dosen', $data);
        // return view('kosong', $data);
    }
}
