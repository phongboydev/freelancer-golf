<?php

namespace App\Tasks\Admin;

use App\Constants\BaseConstants;
use App\Models\Role;
use App\Models\RoleDetail;
use Illuminate\Support\Str;

class RoleTask
{
    /**
     * @param $name
     * @return string
     */

    public function getRole($id)
    {
        return Role::where('name', '<>', 'Super Admin')->where('id', $id)->first();
    }

    public function getRoleDetail($id)
    {
        return RoleDetail::where('role_id', $id)
            ->get();
    }

    public function storeRole()
    {
        $request = Request();
        //id role
        $role_id = $request->id;
        $role_name = $request->title;
        $slug = Str::slug($role_name);
        $count_module = $request->count_module;
        $arr_module = array();
        for ($i = 1; $i <= $count_module; $i++) {
            $create = ($request->input('permission_create_' . $i) != '') ? $request->input('permission_create_' . $i) : 0;
            $read = ($request->input('permission_read_' . $i) != '') ? $request->input('permission_read_' . $i) : 0;
            $update = ($request->input('permission_update_' . $i) != '') ? $request->input('permission_update_' . $i) : 0;
            $delete = ($request->input('permission_delete_' . $i) != '') ? $request->input('permission_delete_' . $i) : 0;
            $item_module = [
                'module_id' => $request->input('module_id_' . $i),
                'permission' => $create + $read + $update + $delete
            ];
            array_push($arr_module, $item_module);
        }
        $data = array(
            'name' => $role_name,
            'slug' => $slug
        );
        if ($role_id == 0) {
            $response = Role::create($data);
            foreach ($arr_module as $module) {
                RoleDetail::updateOrCreate(
                    ['role_id' => $response->id, 'module_id' => $module['module_id']],
                    ['permission' => $module['permission']]
                );
            }
            return $response->id;
        } else {
            Role::where("id", $role_id)->update($data);
            foreach ($arr_module as $module) {
                RoleDetail::updateOrCreate(
                    ['role_id' => $role_id, 'module_id' => $module['module_id']],
                    ['permission' => $module['permission']]
                );
            }
            return $role_id;
        }
    }

    public function deleteItems()
    {
        $request = Request();
        $check_data = $request->seq_list;
        $arr = array();
        $values = "";
        for ($i = 0; $i < count($check_data); $i++){
            $values .= (int)$check_data[$i] . ",";
            $arr[] = (int)$check_data[$i];
        }

        Role::whereIn('id', $arr)->delete();
        RoleDetail::whereIn('role_id', $arr)->delete();
        return true;
    }

    public function getRoleDetailByAdminId($role_id)
    {
        $permissions = RoleDetail::where('role_id', $role_id)
            ->select('m.slug','permission')
            ->join('modules as m','m.id', 'module_id')
            ->get()
            ->toArray();
        if (!empty($permissions)) {
            $tmp = [];
            foreach($permissions as $key => $permission) {
                $tmp[$permission['slug']] = $permission['permission'];
            }
            return [
                'role_id' => $role_id,
                'permissions' => $tmp
            ];
        }
        return false;
    }

    public function checkPermission($module_name, array $permissions, $user_role)
    {
        if ($user_role['role_id'] === BaseConstants::SUPER_ADMIN_ROLE_ID) {
            return true;
        }
        $check_permission = false;
        foreach ($permissions as $permission) {
            if (!empty($user_role['permissions'][$module_name])) {
                if ($permission & $user_role['permissions'][$module_name]) {
                    $check_permission = true;
                    break;
                } else {
                    $check_permission = false;
                }
            }
        }
        return $check_permission;
    }
}
