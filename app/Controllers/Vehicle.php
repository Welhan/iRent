<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\VehicleModel;
use CodeIgniter\I18n\Time;
use Exception;
use PhpParser\Node\Stmt\TryCatch;

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
                    'label' => 'Brand',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} is required.',
                    ]
                ],
                'type' => [
                    'label' => 'Type',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} is required'
                    ]
                ],
                'capacity' => [
                    'label' => 'Capacity',
                    'rules' => 'required|numeric',
                    'errors' => [
                        'required' => '{field} is required',
                        'numeric' => '{field} must contain number',
                    ]
                ],
                'fuel' => [
                    'label' => 'Fuel',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} is required',
                    ]
                ],
                'transmition' => [
                    'label' => 'Transmition',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} is required',
                    ]
                ],
                'price' => [
                    'label' => 'Rental Price',
                    'rules' => 'required|numeric',
                    'errors' => [
                        'required' => '{field} is required',
                        'numeric' => '{field} must contain number',
                    ]
                ],
                'year' => [
                    'label' => 'Year of Car',
                    'rules' => 'required|numeric',
                    'errors' => [
                        'required' => 'Year is required',
                        'numeric' => 'must contain number',
                    ]
                ],
                'pic' => [
                    'label' => 'Picture',
                    'rules' => 'mime_in[pic,image/png,image/jpeg,image/jpg]|is_image[pic]|uploaded[pic]',
                    'errors' => [
                        'mime_in' => '{field} type not allowed',
                        'is_image' => '{field} type not allowed',
                        'uploaded' => '{field} required',
                    ]
                ]
            ]);

            if (!$valid) {
                $msg = [
                    'error' => [
                        'brand' => $validation->getError('brand'),
                        'type' => $validation->getError('type'),
                        'capacity' => $validation->getError('capacity'),
                        'transmition' => $validation->getError('transmition'),
                        'fuel' => $validation->getError('fuel'),
                        'price' => $validation->getError('price'),
                        'year' => $validation->getError('year'),
                        'pic' => $validation->getError('pic'),
                    ]
                ];

                echo json_encode($msg);
                return;
            }

            $brand = (string) $this->request->getPost('brand');
            $type = (string) $this->request->getPost('type');
            $type = (string) $this->request->getPost('type');
            $capacity = (int) $this->request->getPost('capacity');
            $transmition = (string) $this->request->getPost('transmition');
            $fuel = (string) $this->request->getPost('fuel');
            $description = (string) $this->request->getPost('description');
            $price = (string) $this->request->getPost('price');
            $year = (string) $this->request->getPost('year');
            $active = (($this->request->getPost('active')) ? $this->request->getPost('active') : 0);
            $pic = $this->request->getFile('pic');

            if ($this->vehicleModel->duplicateVehicle($brand, $type, $year, session('clientID'))) {
                $msg = [
                    'error' => [
                        'global' => 'Car Already Registered'
                    ]
                ];

                echo json_encode($msg);
                return;
            }

            $fileName = $pic->getRandomName();

            $pic->move('assets/img/vehicle/', $fileName);

            $data = [
                'clientID' => session('clientID'),
                'brand' => htmlspecialchars($brand, true),
                'type' => htmlspecialchars($type, true),
                'transmition' => htmlspecialchars($transmition, true),
                'fuel' => htmlspecialchars($fuel, true),
                'capacity' => htmlspecialchars($capacity, true),
                'year' => htmlspecialchars($year, true),
                'description' => htmlspecialchars($description, true),
                'img' => $fileName,
                'price' => htmlspecialchars($price, true),
                'active' => htmlspecialchars($active, true),
                'userAdded' => session('userID'),
                'dateAdded' => Time::now()
            ];

            try {
                if ($this->vehicleModel->save($data)) {
                    $alert = [
                        'message' => 'Vehicle Data Saved',
                        'alert' => 'alert-success'
                    ];

                    session()->setFlashdata($alert);

                    $msg = ['success' => 'Process Done'];
                }
            } catch (Exception $e) {
                $msg = [
                    'error' => [
                        'global' => 'Vehicle Not Saved<br>' . $e->getMessage()
                    ]
                ];
            } finally {
                echo json_encode($msg);
            }
        } else {
            return redirect()->to('vehicle');
        }
    }

    public function getListVechicle()
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
                'vehicles' => $this->vehicleModel->vehicleBrand()
            ];

            $msg = [
                'data' => view('vehicle/modals/listVehicle', $data)
            ];

            echo json_encode($msg);
        } else {
            return redirect()->to('vehicle');
        }
    }
}
