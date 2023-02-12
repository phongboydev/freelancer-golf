<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Constants\BaseConstants;
use App\Models\Admin;
use App\Models\Role;
use App\Tasks\Admin\ManagerTask;

class ManagerController extends Controller
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
     * @return Renderable
     */

    public function listManagers(Request $request)
    {
        if ($request->user_role['role_id'] != BaseConstants::SUPER_ADMIN_ROLE_ID) {
            return redirect()->route('admin.dashboard')->with(['permission_error' => "You don't have permission"]);
        }
        $list_managers = app(ManagerTask::class)->listManagers();
        return view('admin.manager.index')->with(['list_managers' => $list_managers]);
    }

    public function managerDetail(Request $request, $id)
    {
        if ($request->user_role['role_id'] != BaseConstants::SUPER_ADMIN_ROLE_ID) {
            return redirect()->route('admin.dashboard')->with(['permission_error' => "You don't have permission"]);
        }
        $manager_detail = app(ManagerTask::class)->managerDetail($id);
        $list_roles = Role::orderBy('name', 'ASC')->get();
        if ($manager_detail) {
            return view('admin.manager.single')
                ->with(
                    [
                        'manager_detail' => $manager_detail,
                        'list_roles' => $list_roles
                    ]
                );
        } else {
            return redirect()->route('admin.listManagers');
        }
    }

    public function createManager(Request $request)
    {
        if ($request->user_role['role_id'] != BaseConstants::SUPER_ADMIN_ROLE_ID) {
            return redirect()->route('admin.dashboard')->with(['permission_error' => "You don't have permission"]);
        }
        $list_roles = Role::orderBy('name', 'ASC')->get();
        return view('admin.manager.single')->with(['list_roles' => $list_roles]);
    }

    public function postManagerDetail(Request $request)
    {
        if ($request->user_role['role_id'] != BaseConstants::SUPER_ADMIN_ROLE_ID) {
            return redirect()->route('admin.dashboard')->with(['permission_error' => "You don't have permission"]);
        }
        $id = $request->id;
        $response = app(ManagerTask::class)->postManager();
        if ($id == 0) {
            $msg = trans('messages.create_msg', ['model' => 'Account']);
            return redirect()->route('admin.managerDetail', array($response))->with('success_msg', $msg);
        } else {
            $msg = trans('messages.update_msg', ['model' => 'Account']);
            return redirect()->route('admin.managerDetail', array($id))->with('success_msg', $msg);
        }
    }

    public function deleteManager(Request $request)
    {
        if ($request->user_role['role_id'] != BaseConstants::SUPER_ADMIN_ROLE_ID) {
            return false;
        }
        $data = app(ManagerTask::class)->deleteItems();
        return $data;
    }

    public function accountInformation()
    {
        return view('admin.account-information');
    }

    public function storeAccountInformation(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'phone' => 'required',
                'address' => 'required',
                'avatar' => 'mimes:jpg,jpeg,png'
            ],
            [
                'name.required' => 'Enter your name',
                'phone.required' => 'Enter your phone',
                'address' => 'Enter your address',
                'avatar.mimes' => 'Just input file .jpg, png, jpeg'
            ]
        );
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $data = app(ManagerTask::class)->updateAccountInformation();
        Admin::where("id", $request->admin_info->id)->update($data);
        $msg = trans('messages.update_msg', ['model' => 'Account']);
        return redirect()->route('admin.accountInformation')
            ->with('success_msg', $msg);
    }
}
