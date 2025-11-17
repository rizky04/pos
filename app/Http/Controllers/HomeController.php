<?php

namespace App\Http\Controllers;

use App\Models\Mechanic;
use App\Models\Sales;
use App\Models\SalesItem;
use App\Models\SalesPayment;
use App\Models\Service;
use App\Models\ServiceSparepart;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */


    public function index()
{


return view('home');

}

}
