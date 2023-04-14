<?php

namespace App\Models;

use CodeIgniter\Model;

class VehicleModel extends Model
{
    protected $table            = 'vehicles';
    protected $primaryKey       = 'id';
    protected $returnType       = 'object';
    protected $allowedFields    = [];

    public function getVehicle($id = 0)
    {
        $builder = $this->table($this->table);
        $builder->select('*');
        if (session('clientID') != 1) {
            $builder->where('clientID', session('clientID'));
        }

        if ($id) {
            $builder->where('id', $id);
        }

        $builder->orderBy('id', 'desc');

        return $builder->get()->getResultObject();
    }
}
