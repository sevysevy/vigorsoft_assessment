<?php 
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index(Request $request)
    {
        $query = Order::query()->where('user_id', Auth::id());
        
        if ($request->has('symbol')) {
            $query->where('symbol', $request->symbol);
        }
        
        $orders = $query->orderBy('created_at', 'desc')->get();
        
        return response()->json($orders);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'symbol' => 'required|string|in:BTC,ETH',
            'side' => 'required|string|in:buy,sell',
            'price' => 'required|numeric|min:0.00000001',
            'amount' => 'required|numeric|min:0.00000001'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $user = Auth::user();
            $order = $this->orderService->placeOrder($user, $request->all());
            
            return response()->json($order, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function cancel($id)
    {
        try {
            $order = Order::where('user_id', Auth::id())->findOrFail($id);
            $this->orderService->cancelOrder($order);
            
            return response()->json(['message' => 'Order cancelled successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function orderbook(Request $request)
    {
        $symbol = $request->get('symbol', 'BTC');

        $buyOrders = Order::open()
            ->forSymbol($symbol)
            ->buy()
            ->orderBy('price', 'desc')
            ->get()
            ->groupBy('price')
            ->map(function ($orders, $price) {
                return [
                    'price' => (float) $price,
                    'amount' => (float) $orders->sum('amount'),
                ];
            })
            ->values(); // 👈 re-index to array

        $sellOrders = Order::open()
            ->forSymbol($symbol)
            ->sell()
            ->orderBy('price', 'asc')
            ->get()
            ->groupBy('price')
            ->map(function ($orders, $price) {
                return [
                    'price' => (float) $price,
                    'amount' => (float) $orders->sum('amount'),
                ];
            })
            ->values(); // 👈 re-index to array

        return response()->json([
            'buy' => $buyOrders,
            'sell' => $sellOrders,
        ]);
    }

}