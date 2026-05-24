<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

class PageController extends BaseController
{
    /**
     * Halaman landing utama dengan pilihan Smart Finance ataupun Stata.
     */
    public function landing()
    {
        return view('landing');
    }

    /**
     * Halaman Smart Finance Dashboard.
     */
    public function smartFinance()
    {
        return view('smart_finance');
    }

    /**
     * Halaman Stata-like analysis.
     */
    public function stata()
    {
        return view('stata');
    }
}
