<?php

namespace App\Tasks\Admin;

use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class ManagerTask
{
    /**
     * @param $name
     * @return string
     */

    public function listManagers()
    {
        return Admin::with(
            [
                'role' => function ($query) {
                    $query->select('id', 'name');
                }
            ]
        )
            ->where('id', '<>', 1)
            ->get();;
    }

    public function managerDetail($id)
    {
        return Admin::where('id', $id)->first();
    }

    public function postManager()
    {
        $request = Request();
        // account id
        $id = $request->id;
        $email = $request->email;
        $name = $request->name;
        $address = $request->address;
        $password = Hash::make($request->password);
        $role_id = $request->role_id;
        $phone = $request->phone;

        $data = array(
            'email' => $email,
            'name' => $name,
            'phone' => $phone,
            'address' => $address,
            'role_id' => $role_id
        );

        if ($id == 0) {
            $data['password'] = $password;
            $response = Admin::create($data);
            return $response->id;
        } else {
            Admin::where("id", $id)->update($data);
            return $id;
        }
    }

    public function deleteItems()
    {
        $request = Request();
        $check_data = $request->seq_list;
        $arr = array();
        $values = "";
        for ($i = 0; $i < count($check_data); $i++):
            $values .= (int)$check_data[$i] . ",";
            $arr[] = (int)$check_data[$i];
        endfor;

        Admin::whereIn('id', $arr)->delete();
        return true;
    }

    public function updateAccountInformation()
    {
        $request = Request();
        $datetime_now = date('Y-m-d H:i:s');
        $datetime_convert = strtotime($datetime_now);

        //id manager
        $admin_name = $request->name;
        $phone = $request->phone;
        $address = $request->address;

        //avatar
        $name_field = "avatar";
        if ($request->avatar) {
            $file = $request->file($name_field);
            $timestamp = $datetime_convert;
            $name = "avatar-" . $timestamp . '-' . $file->getClientOriginalName();
            $name = str_replace(' ', '-', $name);
            $avatar = $name;
            $url_folder_upload = "/images/avatar/";
            $file->move(public_path() . $url_folder_upload, $name);
        } elseif (isset($request->avatar_file_link) && $request->avatar_file_link != "") {
            $avatar = $request->avatar_file_link;
        } else {
            $avatar = "";
        }

        return array(
            'name' => $admin_name,
            'phone' => $phone,
            'address' => $address,
            'avatar' => $avatar
        );
    }
}
