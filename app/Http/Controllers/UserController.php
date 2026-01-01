<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RoleModel;
use App\Models\UserModel;

class UserController extends Controller
{
    public function index()
    {
        $users = UserModel::with('role')->orderBy('id', 'desc')->get();

        $roles = RoleModel::where('is_active', 1)
                      ->orderBy('id', 'asc')
                      ->get();

        return view('Admin.user', compact('users', 'roles'));
    }

    public function store(Request $request) 
    { 
        $request->validate([ 'name' => 'required|string|max:255|unique:users,name', 
        'email' => 'required|email|unique:users,email',
         'password' => 'required|string|confirmed|min:6', 
         'role_id' => 'required|exists:roles,id', ]); 

        UserModel::create([ 'name' => $request->name, 'email' => $request->email, 'password' => bcrypt($request->password), 'role_id' => $request->role_id, ]); 
      return redirect()
        ->route('users.index')
        ->with('success', 'User added successfully!');
    }
}
