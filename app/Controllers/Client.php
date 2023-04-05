<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ClientModel;
use App\Models\KotaModel;
use CodeIgniter\I18n\Time;
use Exception;

class Client extends BaseController
{
    protected $clientModel;
    protected $submenuModel;
    protected $kotaModel;

    public function __construct()
    {
        $this->clientModel = new ClientModel();
        $this->kotaModel = new KotaModel();
    }
    public function index()
    {
        if (!cek_login(session('userID'))) return redirect()->to('/login');
        if (!check_access(session('userID'), 2, 'view')) return redirect()->to('/');

        // dd(date('Y-m-d', strtotime(14 . ' ' . 'days', strtotime(date('Y-m-d')))));
        // dd(check_Expired(1));

        $data = [
            'active'
        ];
        return view('client/index', $data);
    }

    public function clientData()
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
                'clients' => $this->clientModel->orderby('id', 'desc')->find()
            ];

            $msg = [
                'data' => view('client/tableData', $data)
            ];

            echo json_encode($msg);
        } else {
            return redirect()->to('client');
        }
    }

    public function newClient()
    {
        if (!cek_login(session('userID'))) return redirect()->to('/login');
        if (!check_access(session('userID'), 2, 'view')) return redirect()->to('/');

        $data = [
            'kota' => $this->kotaModel->find()
        ];

        return view('client/new', $data);
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
                'data' => view('client/modals/newModal')
            ];

            echo json_encode($msg);
        } else {
            return redirect()->to('client');
        }
    }

    public function formEdit()
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
                'client' => $this->clientModel->find($id)
            ];

            $msg = [
                'data' => view('client/modals/editModal', $data)
            ];

            echo json_encode($msg);
        } else {
            return redirect()->to('client');
        }
    }

    public function saveClient()
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
                'client' => [
                    'label' => 'Client',
                    'rules' => 'required|is_unique[client.nama]',
                    'errors' => [
                        'required' => '{field} harus diisi.',
                        'is_unique' => '{field} sudah terdaftar.',
                    ]
                ],
                'valid' => [
                    'label' => 'Valid',
                    'rules' => 'numeric',
                    'errors' => [
                        'numeric' => '{field} harus diisi dengan angka'
                    ]
                ]
            ]);

            if (!$valid) {
                $msg = [
                    'error' => [
                        'client' => $validation->getError('client'),
                        'valid' => $validation->getError('valid'),
                    ]
                ];

                echo json_encode($msg);
                return;
            }

            $client = (($this->request->getPost('client')) ? $this->request->getPost('client') : '');
            $provinsi = (($this->request->getPost('provinsi')) ? $this->request->getPost('provinsi') : '');
            $kota = (($this->request->getPost('kota')) ? $this->request->getPost('kota') : '');
            $validClient = (($this->request->getPost('valid')) ? $this->request->getPost('valid') : 0);
            $aktif = (($this->request->getPost('active')) ? $this->request->getPost('active') : 0);

            if ($validClient) {
                $expDate = date('Y-m-d', strtotime($validClient . ' ' . 'month', strtotime(date('Y-m-d'))));
            } else {
                $expDate = date('Y-m-d', strtotime(14 . ' ' . 'days', strtotime(date('Y-m-d'))));
            }

            $data = [
                'nama' => htmlspecialchars($client, true),
                'kota' => htmlspecialchars($kota, true),
                'provinsi' => htmlspecialchars($provinsi, true),
                'valid_until' => $expDate,
                'active' => $aktif,
                'userAdded' => session('userID'),
                'dateAdded' => Time::now()
            ];

            try {
                if ($this->clientModel->save($data)) {
                    $alert = [
                        'message' => 'Client Saved',
                        'type' => 'alert-success'
                    ];

                    $msg = [
                        'process' => 'success',
                        'url' => '/client'
                    ];
                    session()->setFlashdata($alert);
                }
            } catch (Exception $e) {
                $msg = [
                    'error' => [
                        'global' => 'Client Not Saved<br>' . $e->getMessage(),
                    ]
                ];
            } finally {

                echo json_encode($msg);
            }
        } else {
            return redirect()->to('client');
        }
    }

    public function updateClient()
    {
        if (!cek_login(session('userID'))) return redirect()->to('/login');
        if (!check_access(session('userID'), 2, 'view')) return redirect()->to('/');

        $id = $this->request->getVar('id');

        $data = [
            'client' => $this->clientModel->find($id),
            'kota' => $this->kotaModel->find()
        ];

        return view('client/edit', $data);
    }

    public function editClient()
    {
        if ($this->request->isAJAX()) {
            if (!cek_login(session('userID'))) {
                $msg = [
                    'error' => ['logout' => base_url('logout')]
                ];
                echo json_encode($msg);
                return;
            }

            $id = (($this->request->getPost('id')) ? $this->request->getPost('id') : 0);
            $client = (($this->request->getPost('client')) ? $this->request->getPost('client') : '');
            $provinsi = (($this->request->getPost('provinsi')) ? $this->request->getPost('provinsi') : '');
            $kota = (($this->request->getPost('kota')) ? $this->request->getPost('kota') : '');
            $validClient = (($this->request->getPost('valid')) ? $this->request->getPost('valid') : 0);
            $aktif = (($this->request->getPost('active')) ? $this->request->getPost('active') : 0);
            $validDate = ($this->request->getPost('expDate') ? $this->request->getPost('expDate') : 0);

            $oldClient = $this->clientModel->find($id);

            if ($oldClient->nama == $client) {
                $rulesClient = "required";
            } else {
                $rulesClient = "required|is_unique[client.nama]";
            }

            // START VALIDATION
            $validation = \Config\Services::validation();
            $valid = $this->validate([
                'client' => [
                    'label' => 'Client',
                    'rules' => $rulesClient,
                    'errors' => [
                        'required' => '{field} harus diisi.',
                        'is_unique' => '{field} sudah terdaftar.',
                    ]
                ],
                'valid' => [
                    'label' => 'Valid',
                    'rules' => 'numeric',
                    'errors' => [
                        'numeric' => '{field} harus diisi dengan angka'
                    ]
                ]
            ]);

            if (!$valid) {
                $msg = [
                    'error' => [
                        'client' => $validation->getError('client'),
                        'valid' => $validation->getError('valid'),
                    ]
                ];

                echo json_encode($msg);
                return;
            }

            if ($validClient) {
                $expDate = date('Y-m-d', strtotime($validClient . ' ' . 'month', strtotime($validDate)));
            } else {
                $expDate = date('Y-m-d', strtotime(14 . ' ' . 'days', strtotime(date('Y-m-d'))));
            }

            $data = [
                'id' => $id,
                'nama' => htmlspecialchars($client, true),
                'kota' => htmlspecialchars($kota, true),
                'provinsi' => htmlspecialchars($provinsi, true),
                'valid_until' => ($validClient) ? $expDate : $validDate,
                'active' => $aktif,
                'userUpdate' => session('userID'),
                'dateUpdated' => Time::now()
            ];

            try {
                if ($this->clientModel->save($data)) {
                    $alert = [
                        'message' => 'Client Saved',
                        'type' => 'alert-success'
                    ];

                    $msg = [
                        'process' => 'success',
                        'url' => '/client'
                    ];
                    session()->setFlashdata($alert);
                }
            } catch (Exception $e) {
                $msg = [
                    'error' => [
                        'global' => 'Client Not Saved<br>' . $e->getMessage(),
                    ]
                ];
            } finally {
                echo json_encode($msg);
            }
        } else {
            return redirect()->to('client');
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
                'client' => $this->clientModel->find($id)
            ];

            $msg = [
                'data' => view('client/modals/deleteModal', $data)
            ];

            echo json_encode($msg);
        } else {
            return redirect()->to('client');
        }
    }

    public function deleteClient()
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

            try {
                if ($this->clientModel->delete($id)) {
                    $alert = [
                        'message' => 'Client Deleted',
                        'type' => 'alert-success'
                    ];

                    $msg = [
                        'process' => 'success'
                    ];
                    session()->setFlashdata($alert);
                }
            } catch (Exception $e) {
                $msg = [
                    'error' => [
                        'global' => 'Client Not Deleted<br>' . $e->getMessage(),
                    ]
                ];
            } finally {
                echo json_encode($msg);
            }
        } else {
            return redirect()->to('client');
        }
    }
}
