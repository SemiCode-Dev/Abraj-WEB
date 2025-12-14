<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PackageContact;
use Illuminate\Http\Request;

class PackageContactController extends Controller
{
    public function index(Request $request): \Illuminate\Contracts\View\View
    {
        $query = PackageContact::with(['package', 'user'])->latest('created_at');

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $contacts = $query->paginate(15);

        $stats = [
            'total' => PackageContact::count(),
            'pending' => PackageContact::where('status', 'pending')->count(),
            'followup' => PackageContact::where('status', 'followup')->count(),
            'done' => PackageContact::where('status', 'done')->count(),
        ];

        return view('Admin.package-contacts', [
            'contacts' => $contacts,
            'stats' => $stats,
            'currentStatus' => $request->status ?? 'all',
        ]);
    }

    public function updateStatus(Request $request, PackageContact $packageContact): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:pending,followup,done',
        ]);

        $packageContact->update([
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', __('Status updated successfully'));
    }
}
