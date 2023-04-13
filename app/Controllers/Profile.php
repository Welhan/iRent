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
                if ($oldData->img) {
                    unlink('assets/img/profile/' . $oldImg);
                }

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

    public function getPassword()
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
                'id' => $this->request->getPost('id')
            ];

            $msg = [
                'data' => view('profile/modals/changePassModal', $data)
            ];

            echo json_encode($msg);
        } else {
            return redirect()->to('profile');
        }
    }

    public function editPassword()
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
                'oldPass' => [
                    'label' => 'Old Password',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} is required.',
                    ]
                ],
                'newPass' => [
                    'label' => 'New Password',
                    'rules' => 'required|matches[confirmPass]',
                    'errors' => [
                        'required' => '{field} is required',
                        'matches' => '{field} and Confirm Password not match'
                    ]
                ],
                'confirmPass' => [
                    'label' => 'Confirm Password',
                    'rules' => 'required|matches[newPass]',
                    'errors' => [
                        'required' => '{field} is required',
                        'matches' => '{field} and New Password not match'
                    ]
                ],
            ]);

            if (!$valid) {
                $msg = [
                    'error' => [
                        'oldPass' => $validation->getError('oldPass'),
                        'newPass' => $validation->getError('newPass'),
                        'confirmPass' => $validation->getError('confirmPass')
                    ]
                ];

                echo json_encode($msg);
                return;
            }

            $id = $this->request->getPost('id');
            $oldPass = (string) $this->request->getPost('oldPass');
            $newPass = (string) $this->request->getPost('newPass');

            $user = [
                'username' => $this->userModel->find($id)->username,
                'password' => $oldPass
            ];

            if ($this->userModel->getUserLogin($user)) {
                $data = [
                    'id' => $id,
                    'password' => password_hash($newPass, PASSWORD_DEFAULT)
                ];

                try {
                    if ($this->userModel->save($data)) {
                        $alert = [
                            'message' => 'Password Changed',
                            'alert' => 'alert-success'
                        ];

                        session()->setFlashdata($alert);
                        $msg = ['process' => 'success'];
                    }
                } catch (Exception $e) {
                    $msg = [
                        'error' => [
                            'global' => 'Password Not Changed<br>' . $e->getMessage(),
                        ]
                    ];
                } finally {
                    echo json_encode($msg);
                }
            } else {
                $msg = [
                    'error' => [
                        'global' => 'Invalid Password',
                    ]
                ];

                echo json_encode($msg);
            }
        } else {
            return redirect()->to('profile');
        }
    }

    public function removePP()
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
                'id' => $this->request->getPost('id')
            ];

            $msg = [
                'data' => view('profile/modals/removePicModal', $data)
            ];

            echo json_encode($msg);
        } else {
            return redirect()->to('profile');
        }
    }

    public function removeProfPic()
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

            $user = $this->userModel->find($id);

            unlink('assets/img/profile/' . $user->img);

            $data = [
                'id' => $id,
                'img' => ''
            ];

            $this->userModel->save($data);

            $alert = [
                'message' => 'Profile Picture Removed',
                'alert' => 'alert-success'
            ];

            session()->setFlashdata($alert);

            $msg = ['process' => 'success'];

            echo json_encode($msg);
        } else {
            return redirect()->to('profile');
        }
    }
}
