<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        if (isAppContext()) {
            if ($request->user()) {
                return app(DashboardController::class)->index($request);
            }
            return redirect()->route('login');
        }

        return app(MarketingController::class)->home();
    }
}
