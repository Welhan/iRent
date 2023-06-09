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

function check_client($clientID)
{
    $db = \config\Database::connect();

    $builder = $db->table('client');
    $builder->select('*');
    $builder->where(['id' => $clientID]);

    $valid = $builder->get()->getResultArray();

    // return $valid[0]['valid_until'];

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

function generateSubmenu($menu_id, $user_id = '', $clientID = 0)
{
    $db = \config\Database::connect();

    $builder = $db->table('mst_submenu');
    $builder->select('mst_submenu.*');
    if ($user_id) {
        $builder->join('user_access_menu', 'mst_submenu.id = user_access_menu.submenu_id');
        $builder->where(['mst_submenu.menu_id' => $menu_id, 'user_access_menu.flag_view' => 1, 'user_id' => $user_id]);
    } else {
        $builder->where('menu_id', $menu_id);
        if ($clientID <> 0 && $clientID != 1) {
            $builder->where('accessType', 'Client');
        }
    }

    return $builder->get()->getResultObject();
}

function user_profile($user_id)
{
    $db = \config\Database::connect();

    return $db->table('user')->getWhere(['id' => $user_id])->getFirstRow();
}

function check_access($userID, $submenuID, $flag)
{
    $db = \config\Database::connect();

    if ($flag == 'view') {
        $akses = $db->table('user_access_menu')->getWhere(['user_id' => $userID, 'submenu_id' => $submenuID, 'flag_view' => 1])->getFirstRow();
    } else if ($flag == 'add') {
        $akses = $db->table('user_access_menu')->getWhere(['user_id' => $userID, 'submenu_id' => $submenuID, 'flag_add' => 1])->getFirstRow();
    } else if ($flag == 'edit') {
        $akses = $db->table('user_access_menu')->getWhere(['user_id' => $userID, 'submenu_id' => $submenuID, 'flag_update' => 1])->getFirstRow();
    } else if ($flag == 'delete') {
        $akses = $db->table('user_access_menu')->getWhere(['user_id' => $userID, 'submenu_id' => $submenuID, 'flag_delete' => 1])->getFirstRow();
    } else if ($flag == 'control') {
        $akses = $db->table('user_access_menu')->getWhere(['user_id' => $userID, 'submenu_id' => $submenuID, 'flag_control' => 1])->getFirstRow();
    }

    if ($akses) {
        return true;
    } else {
        return false;
    }

    function rupiah($angka)
    {
        $hasil_rupiah = "Rp " . number_format($angka, 2, ',', '.');
        return $hasil_rupiah;
    }
}
