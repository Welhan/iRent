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

function generateMenu($user_id)
{
    $db = \config\Database::connect();

    $builder = $db->table('user_access_menu');
    $builder->select('DISTINCT(user_access_menu.menu_id) AS id, mst_menu.menu');
    $builder->join('mst_menu', 'mst_menu.id = user_access_menu.menu_id');
    $builder->join('mst_submenu', 'mst_submenu.id = user_access_menu.submenu_id');
    $builder->where(['user_access_menu.user_id' => $user_id]);
    $builder->where(['user_access_menu.flag_view' => 1]);
    $builder->where(['mst_submenu.active' => 1]);
    $builder->orderBy('mst_menu.id', 'ASC');

    return $builder->get()->getResultObject();
}

function generateSubmenu($menu_id, $user_id = '')
{
    $db = \config\Database::connect();

    $builder = $db->table('mst_submenu');
    $builder->select('mst_submenu.*');
    if ($user_id) {
        $builder->join('user_access_menu', 'mst_submenu.id = user_access_menu.submenu_id');
        $builder->where(['mst_submenu.menu_id' => $menu_id, 'user_access_menu.flag_view' => 1, 'user_id' => $user_id]);
    } else {
        $builder->where('menu_id', $menu_id);
    }

    return $builder->get()->getResultObject();
}
