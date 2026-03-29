<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Order;
use App\Models\Referral;
use Illuminate\Support\Str;

class FrontController extends Controller
{
    public function index()
    {
        $services = Service::where('is_active', true)->get();
        return view('welcome', compact('services'));
    }

    public function dashboard()
    {
        $user = auth()->user();
        
        // Ensure user has a referral code
        if (!$user->referral_code) {
            $referralCode = 'REF-' . strtoupper(Str::random(6));
            while (\App\Models\User::where('referral_code', $referralCode)->exists()) {
                $referralCode = 'REF-' . strtoupper(Str::random(6));
            }
            $user->referral_code = $referralCode;
            $user->save();
        }

        $referrals = Referral::with('referredUser')->where('referrer_id', $user->id)->get();
        $orders = Order::with('service', 'imeis')->where('user_id', $user->id)->latest()->get();

        return view('dashboard', compact('user', 'referrals', 'orders'));
    }

    public function claimReward()
    {
        $user = auth()->user();

        if ($user->point < 20) {
            return back()->with('error', 'Poin tidak mencukupi.');
        }

        // Deduct points
        $user->decrement('point', 20);

        // Check or recreate "Unblock IMEI 3 Bulan" service free if doesn't exist just get the first one
        $service = Service::firstOrCreate(
            ['name' => 'Unblock IMEI 3 Bulan (Gratis 1x)'],
            ['price' => 0, 'description' => 'Reward Voucher', 'is_active' => false]
        );

        $orderCode = 'IME-' . strtoupper(Str::random(6)) . '-' . rand(1000, 9999);

        // Create free order ticket
        Order::create([
            'order_code' => $orderCode,
            'user_id' => $user->id,
            'service_id' => $service->id,
            'brand' => 'Reward',
            'device' => 'Reward',
            'price' => 0,
            'status' => 'pesanan selesai',
            'whatsapp' => $user->whatsapp ?? '-',
            'admin_note' => 'Diklaim dari Reward Point'
        ]);

        return back()->with('success', 'Reward berhasil ditukar! Cek riwayat pesanan Anda.');
    }
}
