<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\CompanyInfo;
use Illuminate\View\View;

class CompanyInfoController extends Controller
{
    public function index(): View
    {
        $info = CompanyInfo::first();

        return view('company.show', compact('info'));
    }
}
