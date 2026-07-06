<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    public function switchLang($lang)
    {
        if (array_key_exists($lang, ['en' => 'English', 'si' => 'Sinhala', 'ta' => 'Tamil'])) {
            Session::put('applocale', $lang);
        }
        return redirect()->back();
    }
}
