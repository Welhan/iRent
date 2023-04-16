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

    public function formNew()
    {
        // if ($this->request->isAJAX()) {
        //     if (!cek_login(session('userID'))) {
        //         $msg = [
        //             'error' => ['logout' => base_url('logout')]
        //         ];
        //         echo json_encode($msg);
        //         return;
        //     }
        //     $data = [
        //         'vehicles' => $this->vehicleModel->vehicleBrand()
        //     ];

        //     $msg = [
        //         'data' => view('vehicle/modals/newModal', $data)
        //     ];

        //     echo json_encode($msg);
        // } else {
        //     return redirect()->to('vehicle');
        // }

        if (!cek_login(session('userID'))) return redirect()->to('/login');
        if (!check_access(session('userID'), 5, 'add')) return redirect()->to('vehicle');

        $data = [
            'vehicles' => $this->vehicleModel->vehicleBrand()
        ];

        return view('vehicle/new', $data);
    }

    public function newVehicle()
    {
        if ($this->request->isAJAX()) {
            if (!cek_login(session('userID'))) {
                $msg = [
                    'error' => ['logout' => base_url('logout')]
                ];
                echo json_encode($msg);
                return;
            }

            // START VALIDATION
            $validation = \Config\Services::validation();

            $valid = $this->validate([
                'brand' => [
                    'label' => 'Name',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} is required.',
                    ]
                ],
                'phone' => [
                    'label' => 'Phone',
                    'rules' => 'required|numeric',
                    'errors' => [
                        'numeric' => '{field} must contain number',
                        'required' => '{field} is required'
                    ]
                ],
                'address' => [
                    'label' => 'Address',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} is required'
                    ]
                ],

                'username' => [
                    'label' => 'Username',
                    'rules' => '',
                    'errors' => [
                        'required' => '{field} is required',
                        'is_unique' => '{field} already used'
                    ]
                ],
                'pic' => [
                    'label' => 'Profile Picture',
                    'rules' => 'mime_in[pic,image/png,image/jpeg,image/jpg]|is_image[pic]|max_size[pic,2048]',
                    'errors' => [
                        'max_size' => '{field} too large (max 2mb)',
                        'mime_in' => '{field} type not allowed',
                        'is_image' => '{field} type not allowed',
                    ]
                ]
            ]);

            if (!$valid) {
                $msg = [
                    'error' => [
                        'name' => $validation->getError('name'),
                        'phone' => $validation->getError('phone'),
                        'address' => $validation->getError('address'),
                        'username' => $validation->getError('username'),
                        'pic' => $validation->getError('pic'),
                    ]
                ];

                echo json_encode($msg);
                return;
            }
        } else {
            return redirect()->to('vehicle');
        }
    }
}
