<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Trade;

class TradeController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $trades = Trade::whereHas('buyOrder', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->orWhereHas('sellOrder', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->with(['buyOrder', 'sellOrder'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return response()->json($trades);
    }
}