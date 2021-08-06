<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\RedirectRespons;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    protected $viewData = [];
    public function __construct()
    {
        $this->viewData['RouteGroup'] = 'dashboard';
        $this->viewData['RouteName'] = Route::currentRouteName();
    }

    public function index(Request $request)
    {
        return view('backend.dashboard_index')->with($this->viewData);
    }
}
