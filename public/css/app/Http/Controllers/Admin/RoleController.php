<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Constants\BaseConstants;
use App\Models\Module;
use App\Models\Role;
use App\Tasks\Admin\ModuleTask;
use App\Tasks\Admin\RoleTask;

class RoleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function listModules(Request $request)
    {
        if ($request->user_role['role_id'] != BaseConstants::SUPER_ADMIN_ROLE_ID) {
            return redirect()->route('admin.dashboard')->with(['permission_error' => "You don't have permission"]);
        }
        $list_modules = Module::where('id', '<>', BaseConstants::SUPER_ADMIN_ROLE_ID)->get();
        return view('admin.module.index')->with(['list_modules' => $list_modules]);
    }

    public function createModule(Request $request)
    {
        if ($request->user_role['role_id'] != BaseConstants::SUPER_ADMIN_ROLE_ID) {
            return redirect()->route('admin.dashboard')->with(['permission_error' => "You don't have permission"]);
        }
        return view('admin.module.single');
    }

    public function moduleDetail(Request $request, $id)
    {
        if ($request->user_role['role_id'] != BaseConstants::SUPER_ADMIN_ROLE_ID) {
            return redirect()->route('admin.dashboard')->with(['permission_error' => "You don't have permission"]);
        }
        $module = app(ModuleTask::class)->getModuleDetail($id);
        if ($module) {
            return view('admin.module.single')->with(['module' => $module]);
        } else {
            return redirect()->route('admin.listModules');
        }
    }

    public function storeModuleDetail(Request $request)
    {
        if ($request->user_role['role_id'] != BaseConstants::SUPER_ADMIN_ROLE_ID) {
            return redirect()->route('admin.dashboard')->with(['permission_error' => "You don't have permission"]);
        }

        //id module
        $module_id = $request->id;
        $validator = Validator::make(
            $request->all(),
            [
                'title' => 'required|max:191'
            ],
            [
                'title.required' => 'Enter module name'
            ]
        );
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        app(ModuleTask::class)->storeModule();

        if ($module_id == 0) {
            $msg = trans('messages.create_msg', ['model' => 'Module']);
            return redirect()->route('admin.moduleDetail', array($module_id))->with('success_msg', $msg);
        } else {
            $msg = trans('messages.update_msg', ['model' => 'Module']);
            return redirect()->route('admin.moduleDetail', array($module_id))->with('success_msg', $msg);
        }
    }

    public function deleteModule(Request $request)
    {
        if ($request->user_role['role_id'] != BaseConstants::SUPER_ADMIN_ROLE_ID) {
            return false;
        }
        return app(ModuleTask::class)->deleteItems();
    }
    // ROLE
    public function listRoles(Request $request)
    {
        if ($request->user_role['role_id'] != BaseConstants::SUPER_ADMIN_ROLE_ID) {
            return redirect()->route('admin.dashboard')->with(['permission_error' => "You don't have permission"]);
        }
        $list_roles = Role::where('name', '<>', 'Super Admin')->get();
        return view('admin.role.index')->with(['list_roles' => $list_roles]);
    }

    public function createRole(Request $request)
    {
        if ($request->user_role['role_id'] != BaseConstants::SUPER_ADMIN_ROLE_ID) {
            return redirect()->route('admin.dashboard')->with(['permission_error' => "You don't have permission"]);
        }
        $list_module = Module::where('title', '<>', 'Super Admin')->orderBy('title', 'ASC')->get();
        return view('admin.role.single')->with(['list_module' => $list_module]);
    }

    public function roleDetail(Request $request, $id)
    {
        if ($request->user_role['role_id'] != BaseConstants::SUPER_ADMIN_ROLE_ID) {
            return redirect()->route('admin.dashboard')->with(['permission_error' => "You don't have permission"]);
        }
        $role = app(RoleTask::class)->getRole($id);
        $role_detail = app(RoleTask::class)->getRoleDetail($id);
        $list_module = Module::where('title', '<>', 'Super Admin')->orderBy('title', 'ASC')->get();
        if ($role) {
            return view('admin.role.single')->with(
                [
                    'role' => $role,
                    'role_detail' => $role_detail,
                    'list_module' => $list_module
                ]
            );
        } else {
            return redirect()->route('admin.listRoles');
        }
    }

    public function storeRoleDetail(Request $request)
    {
        if ($request->user_role['role_id'] != BaseConstants::SUPER_ADMIN_ROLE_ID) {
            return redirect()->route('admin.dashboard')->with(['permission_error' => "You don't have permission"]);
        }

        //id role
        $role_id = $request->id;
        $validator = Validator::make(
            $request->all(),
            [
                'title' => 'required|max:191'
            ],
            [
                'title.required' => 'Enter module name'
            ]
        );
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        app(RoleTask::class)->storeRole();

        if ($role_id == 0) {
            $msg = trans('messages.create_msg', ['model' => 'Role']);
            return redirect()->route('admin.roleDetail', array($role_id))->with('success_msg', $msg);
        } else {
            $msg = trans('messages.update_msg', ['model' => 'Role']);
            return redirect()->route('admin.roleDetail', array($role_id))->with('success_msg', $msg);
        }
    }

    public function deleteRole(Request $request)
    {
        if ($request->user_role['role_id'] != BaseConstants::SUPER_ADMIN_ROLE_ID) {
            return false;
        }
        return app(RoleTask::class)->deleteItems();
    }
}
