<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use CodeIgniter\I18n\Time;
use Exception;

class Profile extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }
    public function index()
    {
        if (!cek_login(session('userID'))) return redirect()->to('/login');
        $data = [
            'profile' => $this->userModel->find(session('userID'))
        ];
        return view('profile/index', $data);
    }

    public function getEdit()
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
                'user' => $this->userModel->find($id)
            ];

            $msg = [
                'data' => view('profile/modals/editModal', $data)
            ];

            echo json_encode($msg);
        } else {
            return redirect()->to('profile');
        }
    }

    public function editProfile()
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
            $email = ($this->request->getPost('email') ? $this->request->getPost('email') : '');
            $username = ($this->request->getPost('username') ? $this->request->getPost('username') : '');
            $filePic = $this->request->getFile('pic');
            $oldImg = $this->request->getPost('oldPic');

            $oldData = $this->userModel->find($id);

            if ($oldData->username == $username) {
                $usernameRules = 'required';
            } else {
                $usernameRules = 'required|is_unique[user.username]';
            }

            // START VALIDATION
            $validation = \Config\Services::validation();

            if ($filePic) {
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

                    'username' => [
                        'label' => 'Username',
                        'rules' => $usernameRules,
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
            } else {
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
                    'username' => [
                        'label' => 'Username',
                        'rules' => $usernameRules,
                        'errors' => [
                            'required' => '{field} is required',
                            'is_unique' => '{field} already used'
                        ]
                    ],
                ]);
            }


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

            $picName = '';

            if ($filePic <> '') {

                unlink('assets/img/profile/' . $oldImg);

                $picName = $filePic->getRandomName();

                $filePic->move('assets/img/profile/', $picName);

                $data = [
                    'id' => $id,
                    'nama' => htmlspecialchars($name, true),
                    'telp' => htmlspecialchars($phone, true),
                    'alamat' => htmlspecialchars($address, true),
                    'email' => htmlspecialchars($email),
                    'img' => $picName,
                    'username' => htmlspecialchars($username, true),
                    'userUpdated' => session('userID'),
                    'dateUpdated' => Time::now()
                ];
            } else {
                $data = [
                    'id' => $id,
                    'nama' => htmlspecialchars($name, true),
                    'telp' => htmlspecialchars($phone, true),
                    'alamat' => htmlspecialchars($address, true),
                    'email' => htmlspecialchars($email),
                    'username' => htmlspecialchars($username, true),
                    'userUpdated' => session('userID'),
                    'dateUpdated' => Time::now()
                ];
            }



            try {
                if ($this->userModel->save($data)) {
                    $alert = [
                        'message' => 'Profile Updated',
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
                        'global' => 'Profile Not Updated<br>' . $e->getMessage(),
                    ]
                ];
            } finally {
                echo json_encode($msg);
            }
        } else {
            return redirect()->to('profile');
        }
    }
}
