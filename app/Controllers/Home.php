<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        return view('welcome_message');
    }

    public function perbaikan()
    {
        return view('perbaikan');
    }
    public function phpinfo()
    {
        return phpinfo();
    }
}
