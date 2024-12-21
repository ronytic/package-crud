<?php

namespace ProcessMaker\Package\PackageCrud\Http\Controllers;

use ProcessMaker\Http\Controllers\Controller;
use ProcessMaker\Http\Resources\ApiCollection;
use RBAC;
use Illuminate\Http\Request;
use URL;
use Illuminate\Support\Facades\Auth;


class PackageCrudController extends Controller
{
    public function index()
    {
        // Check if the user is an administrator, only administrators can access
        if (!Auth::user()->is_administrator) {
            return view('errors.404');
        }

        return view('package-crud::index');
    }
}
