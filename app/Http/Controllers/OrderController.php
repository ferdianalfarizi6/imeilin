<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Order;
use App\Models\Imei;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function create(Request $request)
    {
        $services = Service::where('is_active', true)->get();
        // pre-select service if passed
        $selectedService = $request->query('service_id');
        return view('order.create', compact('services', 'selectedService'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'brand' => 'required|string|max:255',
            'device' => 'required|string|max:255',
            'imeis' => 'required|array|min:1',
            'imeis.*' => 'required|string|max:50',
            'whatsapp' => 'required|string|max:20',
            'screenshot_imei' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $service = Service::findOrFail($request->service_id);

        $screenshotPath = $request->file('screenshot_imei')->store('screenshots', 'public');
        $paymentProofPath = $request->file('payment_proof')->store('payments', 'public');

        $orderCode = 'IME-' . strtoupper(Str::random(6)) . '-' . rand(1000, 9999);

        // calculate total price based on service * quantity?
        // Wait, does service have a fixed price per IMEI? Assuming price per IMEI.
        $totalImeis = count($request->imeis);
        $totalPrice = $service->price * $totalImeis;

        $order = Order::create([
            'order_code' => $orderCode,
            'user_id' => auth()->id(),
            'service_id' => $service->id,
            'brand' => $request->brand,
            'device' => $request->device,
            'price' => $totalPrice,
            'status' => 'verifikasi pembayaran',
            'whatsapp' => $request->whatsapp,
            'screenshot_imei' => $screenshotPath,
            'payment_proof' => $paymentProofPath,
        ]);

        foreach ($request->imeis as $imeiInput) {
            Imei::create([
                'order_id' => $order->id,
                'imei' => $imeiInput
            ]);
        }

        return redirect()->route('order.success', $order->id);
    }

    public function success(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $adminPhone = \App\Models\Setting::get('admin_whatsapp', '6288706553307');
        $order->load(['service', 'imeis']);
        
        $imeiList = collect($order->imeis)->pluck('imei')->implode("\n   ");
        $totalImeis = $order->imeis->count();
        $date = $order->created_at->format('d/m/Y H:i') . ' WIB';
        
        $message = "━━━━━━━━━━━━━━━━━━━━\n📦 PESANAN BARU MASUK\n━━━━━━━━━━━━━━━━━━━━\n\n📄 Nomor Pesanan: {$order->order_code}\n⏰ Waktu: {$date}\n\n📱 Detail Perangkat:\n   Merek: {$order->brand}\n   Model: {$order->device}\n\n📟 IMEI ({$totalImeis} unit):\n   {$imeiList}\n\n🛠️ Layanan: {$order->service->name}\n💰 Total Bayar: Php " . number_format($order->price, 0, ',', '.') . "\n💳 Metode Bayar: Transfer Bank\n\n📞 WhatsApp Customer: {$order->whatsapp}\n\n📎 Bukti pembayaran sudah di-upload.\n📌 Silakan cek & proses di Admin Dashboard.";
        
        $waLink = "https://wa.me/{$adminPhone}?text=" . urlencode($message);

        $statuses = [
            'verifikasi pembayaran',
            'pembayaran di terima',
            'proses unblok imei',
            'imei berhasil di unblok',
            'pesanan selesai'
        ];

        return view('order.success', compact('order', 'waLink', 'statuses'));
    }
}
