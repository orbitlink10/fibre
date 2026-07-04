<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FiberToolController as PublicFiberToolController;

class FiberToolController extends Controller
{
    public function index()
    {
        return view('admin.fiber-tools.index', [
            'tools' => PublicFiberToolController::links(),
        ]);
    }
}
