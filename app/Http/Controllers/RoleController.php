<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RoleModel;

class RoleController extends Controller
{
    public function index()
    {
    
        $roles = RoleModel::where('is_active', 1)
                 ->orderBy('id', 'asc')
                 ->get();

        return view('roles.index', compact('roles'));
    }
}
