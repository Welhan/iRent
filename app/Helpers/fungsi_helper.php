<?php

function cek_login($userID)
{
    if ($userID) {
        $db = \config\Database::connect();

        $builder = $db->table('client');
        $builder->select('client.valid_until');
        $builder->join('user', 'user.clientID = client.id', 'left');
        $builder->where(['user.id' => $userID]);

        $valid = $builder->get()->getResultArray();

        $expiry_date = $valid[0]['valid_until'];
        $today = date('d-m-Y', time());
        $exp = date('d-m-Y', strtotime($expiry_date));
        $expDate =  date_create($exp);
        $todayDate = date_create($today);
        $diff =  date_diff($todayDate, $expDate);

        if ($diff->format("%R%a") >= 0) {
            return true;
        } else {
            return false;
        }
    } else
        return false;
}

function check_Expired($userID)
{
    $db = \config\Database::connect();

    $builder = $db->table('client');
    $builder->select('client.valid_until');
    $builder->join('user', 'user.clientID = client.id', 'left');
    $builder->where(['user.id' => $userID]);

    $valid = $builder->get()->getResultArray();

    $expiry_date = $valid[0]['valid_until'];
    $today = date('d-m-Y', time());
    $exp = date('d-m-Y', strtotime($expiry_date));
    $expDate =  date_create($exp);
    $todayDate = date_create($today);
    $diff =  date_diff($todayDate, $expDate);

    if ($diff->format("%R%a") <= REMINDER_EXP) {
        return true;
    } else {
        return false;
    }
}
