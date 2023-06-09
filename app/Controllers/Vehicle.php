<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ClientModel;
use App\Models\VehicleModel;
use CodeIgniter\I18n\Time;
use Exception;
use PhpParser\Node\Stmt\TryCatch;

use function PHPUnit\Framework\fileExists;

class Vehicle extends BaseController
{
    protected $vehicleModel;
    protected $clientModel;

    public function __construct()
    {
        $this->vehicleModel = new VehicleModel();
        $this->clientModel = new ClientModel();
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

            $clientName = $this->clientModel->find(session('clientID'))->nama;

            $path = "assets/img/vehicle/" . $clientName . "/" . $brand . "/" . $type . "/";

            if (!fileExists($path)) {
                mkdir($path, 0777, true);
            }

            $pic->move($path, $fileName);

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
                'data' => view('vehicle/admin/listVehicle', $data)
            ];

            echo json_encode($msg);
        } else {
            return redirect()->to('vehicle');
        }
    }

    public function getFormEdit()
    {
        if ($this->request->isAJAX()) {
            if (!cek_login(session('userID'))) {
                $msg = [
                    'error' => ['logout' => base_url('logout')]
                ];
                echo json_encode($msg);
                return;
            }
            $id = $this->request->getPost('id');

            $data = [
                'vehicle' => $this->vehicleModel->find($id),
                'client' => $this->clientModel->find(session('clientID'))->nama
            ];

            $msg = [
                'data' => view('vehicle/modals/editModal', $data)
            ];

            echo json_encode($msg);
        } else {
            return redirect()->to('vehicle');
        }
    }

    public function updateVehicle()
    {
        if ($this->request->isAJAX()) {
            if (!cek_login(session('userID'))) {
                $msg = [
                    'error' => ['logout' => base_url('logout')]
                ];
                echo json_encode($msg);
                return;
            }

            $id = $this->request->getPost('id');
            $brand = (string) $this->request->getPost('brand');
            $type = (string) $this->request->getPost('type');
            $capacity = (int) $this->request->getPost('capacity');
            $description = (string) $this->request->getPost('description');
            $price = (string) $this->request->getPost('price');
            $year = (string) $this->request->getPost('year');
            $active = (($this->request->getPost('active')) ? $this->request->getPost('active') : 0);
            $pic = $this->request->getFile('pic');
            $oldPic = $this->request->getPost('oldPic');

            // START VALIDATION
            $validation = \Config\Services::validation();

            if ($pic <> '') {
                $valid = $this->validate([

                    'capacity' => [
                        'label' => 'Capacity',
                        'rules' => 'required|numeric',
                        'errors' => [
                            'required' => '{field} is required',
                            'numeric' => '{field} must contain number',
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
                        'rules' => 'mime_in[pic,image/png,image/jpeg,image/jpg]|is_image[pic]',
                        'errors' => [
                            'mime_in' => '{field} type not allowed',
                            'is_image' => '{field} type not allowed',
                        ]
                    ]
                ]);
            } else {
                $valid = $this->validate([

                    'capacity' => [
                        'label' => 'Capacity',
                        'rules' => 'required|numeric',
                        'errors' => [
                            'required' => '{field} is required',
                            'numeric' => '{field} must contain number',
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
                ]);
            }

            if (!$valid) {
                $msg = [
                    'error' => [
                        'capacity' => $validation->getError('capacity'),
                        'price' => $validation->getError('price'),
                        'year' => $validation->getError('year'),
                        'pic' => $validation->getError('pic'),
                    ]
                ];

                echo json_encode($msg);
                return;
            }

            if ($pic <> '') {
                $filename = $pic->getRandomName();

                $clientName = $this->clientModel->find(session('clientID'))->nama;

                $path = "assets/img/vehicle/" . $clientName . "/" . $brand . "/" . $type . "/";
                $unlinkPath = "assets/img/vehicle/" . $clientName . "/" . $brand . "/" . $type . "/" . $oldPic;

                if (!fileExists($path)) {
                    mkdir($path, 0777, true);
                }

                unlink($unlinkPath);

                $pic->move($path, $filename);
            } else {
                $filename = $oldPic;
            }

            $data = [
                'id' => $id,
                'capacity' => $capacity,
                'price' => $price,
                'description' => htmlspecialchars($description, true),
                'year' => htmlspecialchars($year, true),
                'active' => htmlspecialchars($active, true),
                'img' => $filename,
                'userUpdated' => session('userID'),
                'dateUpdated' => Time::now()
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

    public function formDelete()
    {
        if ($this->request->isAJAX()) {
            if (!cek_login(session('userID'))) {
                $msg = [
                    'error' => ['logout' => base_url('logout')]
                ];
                echo json_encode($msg);
                return;
            }

            $id = $this->request->getPost('id');

            $data = [
                'vehicle' => $this->vehicleModel->find($id)
            ];

            $msg = [
                'data' => view('vehicle/modals/deleteModal', $data)
            ];

            echo json_encode($msg);
        } else {
            return redirect()->to('vehicle');
        }
    }

    public function deleteVehicle()
    {
        if ($this->request->isAJAX()) {
            if (!cek_login(session('userID'))) {
                $msg = [
                    'error' => ['logout' => base_url('logout')]
                ];
                echo json_encode($msg);
                return;
            }

            $id = $this->request->getPost('id');

            $vehicle = $this->vehicleModel->find($id);
            $client = $this->clientModel->find(session('clientID'))->nama;

            $path = "assets/img/vehicle/" . $client . "/" . $vehicle->brand . "/" . $vehicle->type;

            $delData = scandir("assets/img/vehicle/" . $client . "/" . $vehicle->brand . "/" . $vehicle->type);

            for ($i = 2; $i < count($delData); $i++) {
                $unlinkData = $path . "/" . $delData[$i];
                unlink($unlinkData);
            }

            rmdir("assets/img/vehicle/" . $client . "/" . $vehicle->brand . "/" . $vehicle->type);

            try {
                if ($this->vehicleModel->delete($id)) {
                    $alert = [
                        'message' => "Vehicle Deleted",
                        'alert' => 'alert-success'
                    ];
                    $msg = ['process' => 'success'];
                    session()->setFlashdata($alert);
                }
            } catch (Exception $e) {
                $msg = [
                    'error' => [
                        'global' => 'Vehicle Not Deleted<br>' . $e->getMessage()
                    ]
                ];
            } finally {
                echo json_encode($msg);
            }
        } else {
            return redirect()->to('vehicle');
        }
    }

    public function formAddImg()
    {
        if ($this->request->isAJAX()) {
            if (!cek_login(session('userID'))) {
                $msg = [
                    'error' => ['logout' => base_url('logout')]
                ];
                echo json_encode($msg);
                return;
            }

            $id = $this->request->getPost('id');
            $vehicle = $this->vehicleModel->find($id);
            $client = $this->clientModel->find(session('clientID'))->nama;

            $data = [
                'vehicle' => $vehicle,
                'client' => $client,
                'detailImg' => $this->vehicleModel->getDetailImg(session('clientID'), $vehicle->brand, $vehicle->type)
            ];

            $msg = [
                'data' => view('vehicle/addImage', $data)
            ];

            echo json_encode($msg);
        } else {
            return redirect()->to('vehicle');
        }
    }

    public function addImg()
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
                        'pic' => $validation->getError('pic'),
                    ]
                ];

                echo json_encode($msg);
                return;
            }

            $id = $this->request->getPost('id');
            $vehicle = $this->vehicleModel->find($id);
            $client = $this->clientModel->find(session('clientID'))->nama;
            $pic = $this->request->getFile('pic');

            $filename = $pic->getRandomName();

            $path = "assets/img/vehicle/" . $client . "/" . $vehicle->brand . "/" . $vehicle->type . "/";

            $pic->move($path, $filename);

            $data = [
                'clientID' => session('clientID'),
                'brand' => $vehicle->brand,
                'type' => $vehicle->type,
                'img' => $filename,
                'userAdded' => session('userID'),
                'dateAdded' => Time::now()
            ];

            try {
                if ($this->vehicleModel->addImg($data)) {
                    $alert = [
                        'message' => ucwords($vehicle->brand) . "-" . ucwords($vehicle->type) . " Added New Detail Images",
                        'alert' => 'alert-success'
                    ];
                    $msg = ['process' => 'success'];
                    session()->setFlashdata($alert);
                }
            } catch (Exception $e) {
                $msg = [
                    'error' => [
                        'global' => ucwords($vehicle->type) . ' Images Not Saved<br>' . $e->getMessage()
                    ]
                ];
            } finally {
                echo json_encode($msg);
            }
        } else {
            return redirect()->to('vehicle');
        }
    }

    public function delImg()
    {
        if ($this->request->isAJAX()) {
            if (!cek_login(session('userID'))) {
                $msg = [
                    'error' => ['logout' => base_url('logout')]
                ];
                echo json_encode($msg);
                return;
            }

            $id = $this->request->getPost('id');
            $client =  (string)$this->request->getPost('client');
            $brand =  (string)$this->request->getPost('brand');
            $type = (string) $this->request->getPost('type');
            $img = (string) $this->request->getPost('img');

            $path = "assets/img/vehicle/" . $client . "/" . $brand . "/" . $type . "/" . $img;

            unlink($path);

            try {
                if ($this->vehicleModel->deleteImg($id)) {
                    $alert = [
                        'message' => 'Detail Image ' . ucwords($type) . " Deleted",
                        'alert' => 'alert-success'
                    ];

                    $msg = ['process' => 'success'];
                    session()->setFlashdata($alert);
                }
            } catch (Exception $e) {
                $msg = [
                    'error' => [
                        'global' => 'Deatil Image ' . ucwords($type) . 'Not Deleted<br>' . $e->getMessage()
                    ]
                ];
            } finally {
                echo json_encode($msg);
            }
        } else {
            return redirect()->to('vehicle');
        }
    }
}
