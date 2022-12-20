<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Vehicle;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth_jwt:api');
    }

    public function index()
    {
        return response()->json([
            "users" => User::count(),
            "vehicles" => Vehicle::count()
        ], 200);
    }
}
