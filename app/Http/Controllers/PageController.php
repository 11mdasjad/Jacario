<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class PageController extends Controller
{
    public function about()
    {
        return view('pages.about');
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function postContact(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        return back()->with('success', 'Thank you for reaching out to the JACARIO Concierge. Our atelier team will respond within 24 hours.');
    }

    public function privacy()
    {
        $content = Setting::get('privacy_policy');
        return view('pages.policy', [
            'title' => 'Privacy Policy',
            'subtitle' => 'Our commitment to data protection and client confidentiality',
            'content' => $content,
        ]);
    }

    public function terms()
    {
        $content = Setting::get('terms_conditions');
        return view('pages.policy', [
            'title' => 'Terms & Conditions',
            'subtitle' => 'Guidelines governing your shopping journey with JACARIO',
            'content' => $content,
        ]);
    }

    public function shipping()
    {
        $content = Setting::get('shipping_policy');
        return view('pages.policy', [
            'title' => 'Shipping & Delivery',
            'subtitle' => 'Bespoke packaging, express dispatch, and white-glove logistics',
            'content' => $content,
        ]);
    }

    public function returns()
    {
        $content = Setting::get('return_policy');
        return view('pages.policy', [
            'title' => 'Returns & Exchanges',
            'subtitle' => '15-day complimentary doorstep exchanges and seamless returns',
            'content' => $content,
        ]);
    }

    public function faqs()
    {
        return view('pages.faqs');
    }

    public function sitemap()
    {
        $products = Product::active()->latest()->get();
        $categories = Category::active()->get();

        $content = view('sitemap', compact('products', 'categories'))->render();

        return Response::make($content, 200, ['Content-Type' => 'text/xml']);
    }

    public function robots()
    {
        $sitemapUrl = url('/sitemap.xml');
        $robots = "User-agent: *\nDisallow: /admin/\nDisallow: /checkout/\nDisallow: /account/\nAllow: /\n\nSitemap: {$sitemapUrl}\n";
        return Response::make($robots, 200, ['Content-Type' => 'text/plain']);
    }
}
