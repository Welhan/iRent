<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Dashboard extends BaseController
{
    public function index()
    {
        if (!cek_login(session('userID'))) return redirect()->to('/login');
        // dd(user_profile(session('userID'))->nama);
        return view('dashboard/index');
    }
}
