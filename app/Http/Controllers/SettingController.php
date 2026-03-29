<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    public function index()
    {
        $settings = [
            'bank_account' => Setting::get('bank_account', 'BCA 123456789 a/n Admin'),
            'admin_email' => Setting::get('admin_email', 'admin@example.com'),
            'admin_whatsapp' => Setting::get('admin_whatsapp', '088706553307'),
        ];
        
        return view('admin.settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'settings' => 'required|array',
            'settings.bank_account' => 'required|string',
            'settings.admin_email' => 'required|email',
            'settings.admin_whatsapp' => 'required|string|max:20',
        ]);

        foreach ($request->settings as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return redirect()->back()->with('success', 'Pengaturan berhasil diperbarui.');
    }
}
