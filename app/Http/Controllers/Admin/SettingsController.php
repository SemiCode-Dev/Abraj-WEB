<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * Display the settings page
     */
    public function index()
    {
        $commissionPercentage = Setting::get('commission_percentage', 0);
        
        return view('Admin.settings', compact('commissionPercentage'));
    }

    /**
     * Update settings
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'commission_percentage' => 'required|numeric|min:0|max:100',
        ]);

        Setting::set('commission_percentage', $validated['commission_percentage'], 'float');

        return redirect()->back()->with('success', 'Settings updated successfully');
    }
}
