<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanyInfo;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CompanyInfoController extends Controller
{
    use AuthorizesRequests;

    public function index(): View
    {
        $info = CompanyInfo::first() ?? new CompanyInfo;

        return view('admin.company.index', compact('info'));
    }

    public function update(Request $request): RedirectResponse
    {
        $this->authorize('create', CompanyInfo::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'nip' => 'nullable|string|max:20',
            'regon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'description' => 'nullable|string',
        ]);

        $info = CompanyInfo::first() ?? new CompanyInfo;
        $info->fill($validated);
        $info->save();

        return back()->with('success', 'Informacje o firmie zostały zaktualizowane.');
    }
}
