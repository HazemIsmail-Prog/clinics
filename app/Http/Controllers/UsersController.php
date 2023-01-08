<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Gate;

class UsersController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('Users_Access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if (auth()->id() == 1){
            $users = User::loggedClinic()->get();
        }else{
            $users = User::loggedClinic()->where('id','!=',1)->get();
        }

        return view('pages.users.index', compact('users'));
    }

    public function create()
    {
        abort_if(Gate::denies('Users_Create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        abort_if(auth()->id() > 1 , Response::HTTP_FORBIDDEN, '403 Forbidden');

        $clinics = Clinic::loggedClinic()->get();
        $permissions = config('global.permissions');
        return view('pages.users.create', compact('clinics', 'permissions'));
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('Users_Create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        abort_if(auth()->id() > 1 , Response::HTTP_FORBIDDEN, '403 Forbidden');

        $rules = [
            'name' => 'required',
            'username' => 'required|unique:users',
            'password' => 'required',
            'clinic_id' => 'required',
            'permissions'=>'required',
        ];
        $customMessages = [
            'name.unique' => 'Department Already Exists',
        ];
        $this->validate($request, $rules, $customMessages);
        $data = [
            'name' => $request->name,
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'clinic_id' => $request->clinic_id,
            'permissions' => implode(',',$request->permissions),
            'active' => $request->active ? 1 : 0,

        ];

        User::create($data);
        session()->flash('success','User Added Successfully');
        return redirect()->route('users.index');
    }

    public function edit($id)
    {
        abort_if(Gate::denies('Users_Update'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        abort_if($id == 1 && auth()->id() != 1, Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user = User::loggedClinic()->findOrFail($id);
        $clinics = Clinic::loggedClinic()->get();
        $permissions = config('global.permissions');
        return view('pages.users.edit', compact('user', 'clinics','permissions'));
    }

    public function update(Request $request, $id)
    {
        abort_if(Gate::denies('Users_Update'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        abort_if($id == 1 && auth()->id() != 1, Response::HTTP_FORBIDDEN, '403 Forbidden');


        $validator = \Validator::make(request()->all(), [
            'name' => 'required',
            'username' => 'required|unique:users,username,'.$id,
            'clinic_id' => 'required',
            'permissions'=>'required',
        ]);
        $validator->validate();
        $data = [
            'name' => $request->name,
            'username' => $request->username,
            'clinic_id' => $request->clinic_id,
            'permissions' => implode(',',$request->permissions),
            'active' => $request->active ? 1 : 0,

        ];
        if (!$request['password'] == null) {
            $data['password'] = bcrypt($request['password']);
        }
        $user = User::loggedClinic()->findOrFail($id);
        $user->update($data);
//        $user->permissions()->sync($request->permissions);
        session()->flash('success','User Updated Successfully');
        return redirect()->route('users.index');
    }

    public function destroy($id)
    {
        abort_if(Gate::denies('Users_Delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
    }
}
