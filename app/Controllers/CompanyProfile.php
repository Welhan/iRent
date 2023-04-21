<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ClientModel;

class CompanyProfile extends BaseController
{
    protected $clientModel;

    public function __construct()
    {
        $this->clientModel = new ClientModel();
    }

    public function index()
    {
        if (!cek_login(session('userID'))) return redirect()->to('/login');
        if (!check_access(session('userID'), 6, 'view')) return redirect()->to('/');
        $data = [
            'client' => $this->clientModel->find(session('clientID'))
        ];
        return view('company_profile/index', $data);
    }
}
