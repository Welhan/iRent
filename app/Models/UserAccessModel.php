<?php

namespace App\Models;

use CodeIgniter\Model;

class UserAccessModel extends Model
{
    protected $table            = 'user_access_menu';
    protected $primaryKey       = 'id';
    protected $returnType       = 'object';
    protected $allowedFields    = ['user_id', 'menu_id', 'submenu_id', 'flag_view', 'flag_add', 'flag_update', 'flag_delete', 'flag_control'];

    public function userAccess($userID, $subID)
    {
        return $this->table($this->table)->getWhere(['user_id' => $userID, 'submenu_id' => $subID])->getResultObject();
    }
}
