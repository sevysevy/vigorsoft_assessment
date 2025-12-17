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
        
        $tradesQuery = Trade::forUser($user->id);
        
        // Add symbol filter if provided
        if ($request->filled('symbol')) {
            $tradesQuery->forSymbol($request->symbol);
        }
        
        $trades = $tradesQuery->with(['buyOrder', 'sellOrder'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return response()->json($trades);
    }
}