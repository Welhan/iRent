<?php

namespace App\Models;

use CodeIgniter\Model;

class ClientModel extends Model
{
    protected $table            = 'client';
    protected $primaryKey       = 'id';
    protected $returnType       = 'object';
    protected $allowedFields    = [
        'nama', 'kota', 'provinsi', 'active', 'valid_until', 'userAdded', 'dateAdded',
        'userUpdate', 'dateUpdated'
    ];
}
