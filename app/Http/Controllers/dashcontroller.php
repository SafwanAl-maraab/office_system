<?php

namespace App\Http\Controllers;

use App\Models\Info;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class dashcontroller extends Controller
{
    //

    public function dashboard()
    {

        $user = auth()->user();
        $info = [
            'todayVisas' => 0,
            'todayRequests' => 0,
            'todayRevenue' => 0,
            'remainingAmount' => 0,
            'monthVisas' => 0,
            'monthRequests' => 0,
            'totalProfit' => 0,
            'totalClients' => 0,
            'logo' => 'vnlff',
            'latestVisas' => [],
            'latestRequests' => [],
        ];

        $branch = $user->employee->branch;
        $info = Info::where('branch_id', $branch->id)->first();


        return view('frontend.dashboard', compact('info' ));
    }
}
