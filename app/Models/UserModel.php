<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'user';
    protected $primaryKey       = 'id';
    protected $returnType       = 'object';
    protected $allowedFields    = [];

    public function getUserLogin($user)
    {
        $SQL = "SELECT user.password, client.valid_until";
        $SQL = $SQL . " FROM user left join client on user.clientID = client.id";
        $SQL = $SQL . " WHERE client.active=1";
        $SQL = $SQL . " and user.active=1";
        $SQL = $SQL . " and user.username='" . $user["username"] . "'";
        $data = $this->query($SQL)->getResultArray();
        if (!empty($data)) {
            if (password_verify($user["password"], $data[0]["password"])) {
                $expiry_date = $data[0]["valid_until"];
                $today = date('d-m-Y', time());
                $exp = date('d-m-Y', strtotime($expiry_date));
                $expDate =  date_create($exp);
                $todayDate = date_create($today);
                $diff =  date_diff($todayDate, $expDate);

                // return [$expDate, $todayDate, $diff->format("%R%a")];

                if ($diff->format("%R%a") >= 0) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        };
    }
}
