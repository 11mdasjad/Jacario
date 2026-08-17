<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = [
            'store_name' => Setting::get('store_name', 'JACARIO'),
            'store_tagline' => Setting::get('store_tagline', 'Premium Polo T-Shirts, Made for Every Move'),
            'contact_email' => Setting::get('contact_email', 'concierge@jacario.com'),
            'contact_phone' => Setting::get('contact_phone', '+91 (0) 22 8900 1200'),
            'support_hours' => Setting::get('support_hours', 'Mon – Sat: 9:00 AM – 8:00 PM IST'),
            'store_address' => Setting::get('store_address', 'JACARIO Flagship Atelier, Bandra West, Mumbai, MH 400050'),
            'free_shipping_threshold' => Setting::get('free_shipping_threshold', '1999'),
            'standard_shipping_rate' => Setting::get('standard_shipping_rate', '150'),
            'instagram_url' => Setting::get('instagram_url', 'https://instagram.com/jacario.official'),
            'twitter_url' => Setting::get('twitter_url', 'https://twitter.com/jacario_brand'),
            'shipping_policy' => Setting::get('shipping_policy', ''),
            'return_policy' => Setting::get('return_policy', ''),
            'privacy_policy' => Setting::get('privacy_policy', ''),
            'terms_conditions' => Setting::get('terms_conditions', ''),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $inputs = $request->except(['_token', '_method']);

        foreach ($inputs as $key => $value) {
            Setting::set($key, $value);
        }

        return back()->with('success', 'Store configuration and policy contents updated successfully.');
    }
}
