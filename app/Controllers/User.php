<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class User extends BaseController
{

    protected $userModel;
    protected $clientModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        if (!cek_login(session('userID'))) return redirect()->to('/login');

        $data = [];
        return view('user/index', $data);
    }

    public function userData()
    {
        if ($this->request->isAJAX()) {
            if (!cek_login(session('userID'))) {
                $msg = [
                    'error' => ['logout' => base_url('logout')]
                ];
                echo json_encode($msg);
                return;
            }

            $data = [
                'users' => $this->userModel->user()
            ];

            $msg = [
                'data' => view('user/tableData', $data)
            ];

            echo json_encode($msg);
        } else {
            return redirect()->to('user');
        }
    }

    public function formNew()
    {
        if ($this->request->isAJAX()) {
            if (!cek_login(session('userID'))) {
                $msg = [
                    'error' => ['logout' => base_url('logout')]
                ];
                echo json_encode($msg);
                return;
            }

            $msg = [
                'data' => view('user/modal/newModal')
            ];

            echo json_encode($msg);
        } else {
            return redirect()->to('user');
        }
    }
}
