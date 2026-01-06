<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
   public function index()
    {
        $users = Main::with('role')->orderBy('id', 'desc')->get();

        $roles = RoleModel::where('is_active', 1)
                      ->orderBy('id', 'asc')
                      ->get();

        return view('Admin.user', compact('users', 'roles'));
    }

}
