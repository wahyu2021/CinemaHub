<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\RedirectResponse;

/**
 * Controller for handling language switching.
 */
class LanguageController extends Controller
{
    /**
     * Switch the application locale and store it in the session.
     *
     * @param string $locale
     * @return RedirectResponse
     */
    public function switch(string $locale): RedirectResponse
    {
        if (in_array($locale, ['en', 'id'])) {
            Session::put('locale', $locale);
            App::setLocale($locale);
        }
        return back();
    }
}
