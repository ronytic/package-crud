<?php

namespace ProcessMaker\Package\PackageCrud\Http\Controllers;

use ProcessMaker\Http\Controllers\Controller;
use ProcessMaker\Http\Resources\ApiCollection;
use ProcessMaker\Package\PackageCrud\Models\Sample;
use RBAC;
use Illuminate\Http\Request;
use URL;


class PackageCrudController extends Controller
{
    public function index()
    {
        return view('package-crud::index');
    }
}
