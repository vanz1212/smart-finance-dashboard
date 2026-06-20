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
     * Halaman informasi pajak yang dapat dibaca tanpa login.
     */
    public function taxInformation()
    {
        return view('tax_information');
    }

    /**
     * Halaman pengenalan dan materi Stata yang dapat dibaca tanpa login.
     */
    public function stataInformation()
    {
        return view('stata_information');
    }

    /**
     * Halaman Smart Finance Dashboard.
     */
    public function smartFinance()
    {
        return view('smart_finance');
    }

    /**
     * Halaman Stata analysis.
     */
    public function stata()
    {
        return view('stata');
    }
}
