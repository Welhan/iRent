<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ClientModel;
use App\Models\RoleModel;
use App\Models\UserModel;
use CodeIgniter\I18n\Time;
use Exception;

class User extends BaseController
{

    protected $userModel;
    protected $clientModel;
    protected $roleModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->clientModel = new ClientModel();
        $this->roleModel = new RoleModel();
        helper('text');
    }

    public function index()
    {
        if (!cek_login(session('userID'))) return redirect()->to('/login');
        if (!check_access(session('userID'), 3)) return redirect()->to('/');

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

            $data = [
                'clients' => $this->clientModel->where('active', 1)->orderBy('id', 'desc')->find(),
                'levels' => $this->roleModel->find(),
                'pass' => random_string('alnum', 8)
            ];

            $msg = [
                'data' => view('user/modals/newModal', $data)
            ];

            echo json_encode($msg);
        } else {
            return redirect()->to('user');
        }
    }

    public function saveUser()
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
                'name' => [
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
                'client' => [
                    'label' => 'Client',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} is required'
                    ]
                ],
                'level' => [
                    'label' => 'Level',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} is required'
                    ]
                ],
                'username' => [
                    'label' => 'Username',
                    'rules' => 'required|is_unique[user.username]',
                    'errors' => [
                        'required' => '{field} is required',
                        'is_unique' => '{field} already used'
                    ]
                ],
                'password' => [
                    'label' => 'Password',
                    'rules' => 'required|min_length[8]',
                    'errors' => [
                        'required' => '{field} is required',
                        'min_length' => '{field} must atleast 8 character'
                    ]
                ],
            ]);

            if (!$valid) {
                $msg = [
                    'error' => [
                        'name' => $validation->getError('name'),
                        'phone' => $validation->getError('phone'),
                        'address' => $validation->getError('address'),
                        'client' => $validation->getError('client'),
                        'level' => $validation->getError('level'),
                        'username' => $validation->getError('username'),
                        'password' => $validation->getError('password'),
                    ]
                ];

                echo json_encode($msg);
                return;
            }

            $name = ($this->request->getPost('name') ? $this->request->getPost('name') : '');
            $phone = ($this->request->getPost('phone') ? $this->request->getPost('phone') : '');
            $address = ($this->request->getPost('address') ? $this->request->getPost('address') : '');
            $client = ($this->request->getPost('client') ? $this->request->getPost('client') : '');
            $level = ($this->request->getPost('level') ? $this->request->getPost('level') : '');
            $username = ($this->request->getPost('username') ? $this->request->getPost('username') : '');
            $password = ($this->request->getPost('password') ? $this->request->getPost('password') : '');
            $aktif = ($this->request->getPost('active') ? $this->request->getPost('active') : 0);

            $data = [
                'nama' => htmlspecialchars($name, true),
                'telp' => htmlspecialchars($phone, true),
                'alamat' => htmlspecialchars($address, true),
                'clientID' => htmlspecialchars($client, true),
                'roleID' => htmlspecialchars($level, true),
                'username' => htmlspecialchars($username, true),
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'active' => htmlspecialchars($aktif, true),
                'userAdded' => session('userID'),
                'dateAdded' => Time::now()
            ];

            try {
                if ($this->userModel->save($data)) {
                    $alert = [
                        'message' => 'User Saved',
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
                        'global' => 'User Not Saved<br>' . $e->getMessage(),
                    ]
                ];
            } finally {
                echo json_encode($msg);
            }
        } else {
            return redirect()->to('user');
        }
    }

    public function editUser()
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
                'user' => $this->userModel->find($this->request->getPost('id')),
                'clients' => $this->clientModel->where('active', 1)->orderBy('id', 'desc')->find(),
                'levels' => $this->roleModel->find(),
            ];

            $msg = [
                'data' => view('user/modals/editModal', $data)
            ];

            echo json_encode($msg);
        } else {
            return redirect()->to('user');
        }
    }

    public function updateUser()
    {
        if ($this->request->isAJAX()) {
            if (!cek_login(session('userID'))) {
                $msg = [
                    'error' => ['logout' => base_url('logout')]
                ];
                echo json_encode($msg);
                return;
            }

            $id = ($this->request->getPost('id') ? $this->request->getPost('id') : 0);
            $name = ($this->request->getPost('name') ? $this->request->getPost('name') : '');
            $phone = ($this->request->getPost('phone') ? $this->request->getPost('phone') : '');
            $address = ($this->request->getPost('address') ? $this->request->getPost('address') : '');
            $client = ($this->request->getPost('client') ? $this->request->getPost('client') : '');
            $level = ($this->request->getPost('level') ? $this->request->getPost('level') : '');
            $username = ($this->request->getPost('username') ? $this->request->getPost('username') : '');
            $password = ($this->request->getPost('password') ? $this->request->getPost('password') : '');
            $aktif = ($this->request->getPost('active') ? $this->request->getPost('active') : 0);

            $oldUsername = $this->userModel->find($id);

            if ($oldUsername->username == $username) {
                $usernameRules = 'required';
            } else {
                $usernameRules = 'required|is_unique[user.username]';
            }

            // START VALIDATION
            $validation = \Config\Services::validation();
            $valid = $this->validate([
                'name' => [
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
                'client' => [
                    'label' => 'Client',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} is required'
                    ]
                ],
                'level' => [
                    'label' => 'Level',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} is required'
                    ]
                ],
                'username' => [
                    'label' => 'Username',
                    'rules' => $usernameRules,
                    'errors' => [
                        'required' => '{field} is required',
                        'is_unique' => '{field} already used'
                    ]
                ]
            ]);

            if (!$valid) {
                $msg = [
                    'error' => [
                        'name' => $validation->getError('name'),
                        'phone' => $validation->getError('phone'),
                        'address' => $validation->getError('address'),
                        'client' => $validation->getError('client'),
                        'level' => $validation->getError('level'),
                        'username' => $validation->getError('username'),
                    ]
                ];

                echo json_encode($msg);
                return;
            }

            $data = [
                'id' => $id,
                'nama' => htmlspecialchars($name, true),
                'telp' => htmlspecialchars($phone, true),
                'alamat' => htmlspecialchars($address, true),
                'clientID' => htmlspecialchars($client, true),
                'roleID' => htmlspecialchars($level, true),
                'username' => htmlspecialchars($username, true),
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'active' => htmlspecialchars($aktif, true),
                'userUpdated' => session('userID'),
                'dateUpdated' => Time::now()
            ];

            try {
                if ($this->userModel->save($data)) {
                    $alert = [
                        'message' => 'User Saved',
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
                        'global' => 'User Not Saved<br>' . $e->getMessage(),
                    ]
                ];
            } finally {
                echo json_encode($msg);
            }
        } else {
            return redirect()->to('user');
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

            $data = [
                'user' => $this->userModel->find($this->request->getPost('id')),
            ];

            $msg = [
                'data' => view('user/modals/deleteModal', $data)
            ];

            echo json_encode($msg);
        } else {
            return redirect()->to('user');
        }
    }

    public function deleteUser()
    {
        if ($this->request->isAJAX()) {
            if (!cek_login(session('userID'))) {
                $msg = [
                    'error' => ['logout' => base_url('logout')]
                ];
                echo json_encode($msg);
                return;
            }

            $id = ($this->request->getPost('id') ? $this->request->getPost('id') : '');

            try {
                if ($this->userModel->delete($id)) {
                    $alert = [
                        'message' => 'User Deleted',
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
                        'global' => 'User Not Deleted<br>' . $e->getMessage(),
                    ]
                ];
            } finally {
                echo json_encode($msg);
            }
        } else {
            return redirect()->to('user');
        }
    }
}
