<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ClientModel;
use CodeIgniter\I18n\Time;
use Exception;

class Client extends BaseController
{
    protected $clientModel;
    protected $submenuModel;

    public function __construct()
    {
        $this->clientModel = new ClientModel();
    }
    public function index()
    {
        if (!cek_login(session('userID'))) return redirect()->to('/login');

        // dd(date('Y-m-d', strtotime(14 . ' ' . 'days', strtotime(date('Y-m-d')))));

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

            $id = (($this->request->getPost('id')) ? $this->request->getPost('id') : 0);
            $client = (($this->request->getPost('client')) ? $this->request->getPost('client') : '');
            $validClient = (($this->request->getPost('valid')) ? $this->request->getPost('valid') : 0);
            $aktif = (($this->request->getPost('active')) ? $this->request->getPost('active') : 0);

            if ($validClient) {
                $expDate = date('Y-m-d', strtotime($validClient . ' ' . 'month', strtotime(date('Y-m-d'))));
            } else {
                $expDate = date('Y-m-d', strtotime(14 . ' ' . 'days', strtotime(date('Y-m-d'))));
            }

            if ($id) {
                $data = [
                    'id' => $id,
                    'nama' => htmlspecialchars($client, true),
                    'valid_until' => $expDate,
                    'active' => $aktif,
                    'userUpdate' => session('userID'),
                    'dateUpdated' => Time::now()
                ];
            } else {
                $data = [
                    'nama' => htmlspecialchars($client, true),
                    'valid_until' => $expDate,
                    'active' => $aktif,
                    'userAdded' => session('userID'),
                    'dateAdded' => Time::now()
                ];
            }
            try {
                if ($this->clientModel->save($data)) {
                    $alert = [
                        'message' => 'Client Saved',
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
}
