<?php

namespace App\Http\Controllers;

use App\Models\Setup;
use App\Models\Analytic;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $setup = Setup::init();
        // return $setup->monitorings;
        return view("dashboard.index", compact('setup'));
    }
}
