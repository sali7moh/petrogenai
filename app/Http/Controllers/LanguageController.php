<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    public function switch(Request $request)
    {
        $locale = $request->input('locale', 'en');
        
        if (in_array($locale, ['en', 'ar'])) {
            Session::put('locale', $locale);
        }
        
        return response()->json([
            'success' => true,
            'locale' => $locale
        ]);
    }
}
