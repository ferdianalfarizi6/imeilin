<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use App\Models\Service;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalOrders = Order::count();
        $totalUsers = User::where('role', 'user')->count();
        $totalIncome = Order::whereNotIn('status', ['pending', 'gagal', 'dibatalkan'])->sum('price');
        
        // Simple graph data - daily income for the last 7 days
        $graphData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $income = Order::whereNotIn('status', ['pending', 'gagal', 'dibatalkan'])
                ->whereDate('created_at', $date)
                ->sum('price');
            $graphData[] = ['date' => $date, 'income' => $income];
        }

        return view('admin.dashboard', compact('totalOrders', 'totalUsers', 'totalIncome', 'graphData'));
    }

    public function users()
    {
        $users = User::where('role', 'user')->withCount('referrals')->paginate(20);
        return view('admin.users', compact('users'));
    }
}
