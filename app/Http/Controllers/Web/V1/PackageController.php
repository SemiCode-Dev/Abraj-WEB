<?php

namespace App\Http\Controllers\Web\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\V1\PackageContactRequest;
use App\Models\Package;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingRequestMail;

class PackageController extends Controller
{
    public function index()
    {
        $packages = Package::orderBy('created_at', 'desc')->get();

        return view('Web.packages', [
            'packages' => $packages,
        ]);
    }

    public function show($id)
    {
        $package = Package::findOrFail($id);

        return view('Web.package-details', [
            'package' => $package,
        ]);
    }

    public function contact(PackageContactRequest $request, $id)
    {
        $package = Package::findOrFail($id);

        try {
            $user = auth()->user();

            DB::table('package_contacts')->insert([
                'package_id' => $package->id,
                'user_id' => $user?->id,
                'name' => $user ? $user->name : $request->name,
                'email' => $user ? $user->email : $request->email,
                'phone_country_code' => $user ? ($user->phone_country_code ?? '966') : $request->phone_country_code,
                'phone' => $user ? $user->phone : $request->phone,
                'message' => $request->message ?? '',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Send Email Notification
            try {
                $mailData = [
                    'package_name' => $package->title ?? $package->title_ar ?? 'Unknown Package',
                    'name' => $user ? $user->name : $request->name,
                    'email' => $user ? $user->email : $request->email,
                    'phone' => ($user ? ($user->phone_country_code ?? '966') : $request->phone_country_code) . ($user ? $user->phone : $request->phone),
                    'message' => $request->message ?? '',
                ];

                Mail::to('support@abrajstay.com')->send(new BookingRequestMail($mailData, 'Package Contact'));
            } catch (\Exception $e) {
                Log::error('Failed to send package contact email: ' . $e->getMessage());
            }

            return redirect()->back()->with('success', __('Contact request submitted successfully!'));
        } catch (\Exception $e) {
            Log::error('Failed to save package contact: '.$e->getMessage());

            return redirect()->back()->with('error', __('Failed to submit contact request. Please try again.'));
        }
    }
}
