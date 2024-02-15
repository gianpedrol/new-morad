<?php

namespace App\Http\Controllers\WEB\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Property;

class StaffDashboardController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:staff');
    }

    public function dashboard(){
        return redirect()->route('staff.property.index');
    }
}
