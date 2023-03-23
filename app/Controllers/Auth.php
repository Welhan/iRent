<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Auth extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }
    public function index()
    {
        if (cek_login(session('userID'))) return redirect()->to('/');

        return view('login/index');
    }

    public function login()
    {
        if (cek_login(session('userID'))) return redirect()->to('/');
        $username = ($this->request->getPost('username') ? $this->request->getPost('username') : '');

        $user = [
            'username' => $username,
            'password' => $this->request->getPost('password')
        ];

        $login = $this->userModel->getUserLogin($user);

        if ($login) {
            $profile = $this->userModel->where('username', $username)->find()[0];
            $session = [
                'userID' => $profile->id
            ];

            session()->set($session);
            return redirect()->to('/');
        } else {
            $alert = [
                'message' => 'Username atau Password Salah'
            ];

            session()->setFlashdata($alert);

            return redirect()->to('login');
        }
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to('/login');
    }
}
