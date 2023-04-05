<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\KotaModel;
use App\Models\ProvinsiModel;

class Provinsi extends BaseController
{
    protected $kotaModel;

    public function __construct()
    {
        $this->kotaModel = new KotaModel();
    }

    public function index()
    {
        if (!cek_login(session('userID'))) return redirect()->to('/login');
        if (!check_access(session('userID'), 4, 'view')) return redirect()->to('/');
        return view('provinsi/index');
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
                'kota' => $this->kotaModel->find()
            ];

            $msg = [
                'data' => view('provinsi/tableData', $data)
            ];

            echo json_encode($msg);
        } else {
            return redirect()->to('provinsi');
        }
    }

    public function refreshData()
    {
        if ($this->request->isAJAX()) {
            if (!cek_login(session('userID'))) {
                $msg = [
                    'error' => ['logout' => base_url('logout')]
                ];
                echo json_encode($msg);
                return;
            }

            $allKota = $this->kotaModel->find();
            $apiKota = $this->kotaModel->where('flag', 'API')->find();
            $countKota = 0;

            if (count($allKota) === count($apiKota)) {
                $this->kotaModel->truncate();
            } else {
                $this->kotaModel->where('flag', 'API')->delete();
            }

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.rajaongkir.com/starter/city",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "key: " . API_KEY
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                echo "cURL Error #:" . $err;
            } else {
                $provinsi = json_decode($response);
                // echo $provinsi[0]->rajaongkir;

                foreach ($provinsi->rajaongkir->results as $kota) {
                    $data = [
                        'provinsi' => $kota->province,
                        'kota' => $kota->type . ' ' . $kota->city_name,
                        'flag' => 'API'
                    ];

                    if ($this->kotaModel->save($data)) {
                        $countKota++;
                    }
                }
            }

            $alert = [
                'message' => $countKota . ' Kota Berhasil Disimpan'
            ];

            session()->setFlashdata($alert);

            $msg = ['process' => 'success'];

            echo json_encode($msg);
        } else {
            return redirect()->to('provinsi');
        }
    }
}
