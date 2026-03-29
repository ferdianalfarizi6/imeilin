<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class AdminOrderController extends Controller
{
    private $statusOrder = [
        'verifikasi pembayaran',
        'pembayaran di terima',
        'proses unblok imei',
        'imei berhasil di unblok',
        'pesanan selesai'
    ];

    public function index(Request $request)
    {
        $query = Order::with('user', 'service', 'imeis')->latest();
        
        // Tab Filter: ongoing vs history
        $tab = $request->query('tab', 'ongoing');
        if ($tab === 'history') {
            $query->whereIn('status', ['imei berhasil di unblok', 'pesanan selesai']);
        } else {
            $query->whereIn('status', ['verifikasi pembayaran', 'pembayaran di terima', 'proses unblok imei']);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_code', 'LIKE', "%{$search}%")
                  ->orWhere('whatsapp', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function($u) use ($search) {
                      $u->where('name', 'LIKE', "%{$search}%");
                  })
                  ->orWhereHas('imeis', function($i) use ($search) {
                      $i->where('imei', 'LIKE', "%{$search}%");
                  });
            });
        }

        $orders = $query->paginate(20);
        
        $statuses = $this->statusOrder;

        return view('admin.orders.index', compact('orders', 'statuses', 'tab'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'service', 'imeis']);
        
        $statuses = $this->statusOrder;
        
        return view('admin.orders.show', compact('order', 'statuses'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|string|in:' . implode(',', $this->statusOrder),
            'admin_note' => 'nullable|string',
        ]);

        $currentIndex = array_search($order->status, $this->statusOrder);
        $newIndex = array_search($request->status, $this->statusOrder);

        // Validation: Status only allowed to go forward, and sequentially (no skipping) - wait, "tidak boleh loncat (harus urut)". 
        // This means newIndex MUST be exactly currentIndex + 1, OR if it's the exact same status (maybe just updating admin_note).
        if ($newIndex !== $currentIndex) {
            if ($newIndex < $currentIndex) {
                return redirect()->back()->with('error', 'Status tidak boleh mundur.');
            }
            if ($newIndex > $currentIndex + 1) {
                return redirect()->back()->with('error', 'Status tidak boleh loncat.');
            }
        }

        $order->update([
            'status' => $request->status,
            'admin_note' => $request->admin_note,
            'processed_at' => $request->status == 'pesanan selesai' ? now() : $order->processed_at,
        ]);

        if ($request->status == 'pesanan selesai') {
            $referral = \App\Models\Referral::where('referred_user_id', $order->user_id)
                ->where('is_rewarded', false)
                ->first();
                
            if ($referral) {
                // Berikan 1 poin ke referrer
                $referrer = \App\Models\User::find($referral->referrer_id);
                if ($referrer) {
                    $referrer->increment('point');
                }
                
                // Tandai sudah di-reward agar tidak dikasih poin lagi
                $referral->update(['is_rewarded' => true]);
            }
        }

        return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui.');
    }
    
    // Unused resource methods
    public function create() { abort(404); }
    public function store(Request $request) { abort(404); }
    public function edit(Order $order) { abort(404); }

    public function destroy(Order $order)
    {
        // Hapus IMEI terkait dulu
        $order->imeis()->delete();

        // Hapus orderan
        $order->delete();

        return redirect()->route('admin.orders.index')
            ->with('success', 'Pesanan berhasil dihapus.');
    }
}
