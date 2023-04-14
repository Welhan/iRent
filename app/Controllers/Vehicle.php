<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\VehicleModel;

class Vehicle extends BaseController
{
    protected $vehicleModel;

    public function __construct()
    {
        $this->vehicleModel = new VehicleModel();
    }
    public function index()
    {
        if (!cek_login(session('userID'))) return redirect()->to('/login');
        if (!check_access(session('userID'), 5, 'view')) return redirect()->to('/');

        return view('vehicle/index');
    }

    public function getData()
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
                'vehicles' => $this->vehicleModel->getVehicle()
            ];

            $msg = [
                'data' => view('vehicle/tableData', $data)
            ];

            echo json_encode($msg);
        } else {
            return redirect()->to('vehicle');
        }
    }
}
