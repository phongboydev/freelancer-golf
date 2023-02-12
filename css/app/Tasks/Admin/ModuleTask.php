<?php

namespace App\Tasks\Admin;

use App\Models\Module;
use Illuminate\Support\Str;

class ModuleTask
{
    /**
     * @param $name
     * @return string
     */

    public function getModuleDetail($id)
    {
        return Module::where('id', $id)->first();
    }

    public function storeModule()
    {
        $request = Request();
        //id module
        $module_id = $request->id;
        $module_name = $request->title;
        $slug = Str::slug($module_name);

        $data = array(
            'title' => $module_name,
            'slug' => $slug
        );
        if ($module_id == 0) {
            $response = Module::create($data);
            return $response->id;
        } else {
            Module::where("id", $module_id)->update($data);
            return $module_id;
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

        Module::whereIn('id', $arr)->delete();
        return true;
    }
}
