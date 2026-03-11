<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomerModel;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CustomerController extends Controller
{


public function showForgotPassword()
{
    return view('Client.forgot_password');
}

// ── Email அனுப்பு ──
public function sendResetLink(Request $request)
{
    $request->validate(['email' => 'required|email']);

    $customer = CustomerModel::where('email', $request->email)->first();

    if (!$customer) {
        return back()->with('error', 'No account found with this email.');
    }

    // Token generate பண்ணு
    $token = Str::random(60);

    // DB-ல் save பண்ணு
    DB::table('password_reset_tokens')->updateOrInsert(
        ['email' => $request->email],
        [
            'email'      => $request->email,
            'token'      => bcrypt($token),
            'created_at' => Carbon::now(),
        ]
    );

    // Reset link
    $resetLink = route('client.reset', $token) . '?email=' . urlencode($request->email);

    // Mail அனுப்பு
    Mail::send('Client.emails.reset_password', 
        ['resetLink' => $resetLink, 'customer' => $customer], 
        function ($message) use ($request) {
            $message->to($request->email)
                    ->subject('Password Reset Request — SmartEcom');
        }
    );

    return back()->with('success', 'Password reset link sent to your email!');
}

// ── Reset Password Form காட்டு ──
public function showResetForm(Request $request, $token)
{
    return view('Client.reset_password', [
        'token' => $token,
        'email' => $request->email,
    ]);
}

// ── புதிய Password Save பண்ணு ──
public function resetPassword(Request $request)
{
    $request->validate([
        'email'                 => 'required|email',
        'token'                 => 'required',
        'password'              => 'required|confirmed|min:6',
        'password_confirmation' => 'required',
    ]);

    // Token check பண்ணு
    $record = DB::table('password_reset_tokens')
        ->where('email', $request->email)
        ->first();

    if (!$record || !Hash::check($request->token, $record->token)) {
        return back()->with('error', 'Invalid or expired reset link.');
    }

    // 60 minutes-க்கு மேல் ஆனால் expire
    if (Carbon::parse($record->created_at)->addMinutes(60)->isPast()) {
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();
        return back()->with('error', 'Reset link expired. Please request a new one.');
    }

    // Password update பண்ணு
    CustomerModel::where('email', $request->email)
        ->update(['password' => Hash::make($request->password)]);

    // Token delete பண்ணு
    DB::table('password_reset_tokens')->where('email', $request->email)->delete();

    return redirect()->route('ClientLogin')->with('success', 'Password reset successful! Please login.');
}








    public function index()
    {
        $customers = CustomerModel::all(); // Fetch all customers
        return view('admin.customer', compact('customers'));
    }


    public function updateStatus(Request $request, $id)
    {
    $customer = CustomerModel::findOrFail($id);

    $request->validate([
        'status' => 'required|in:active,block'
    ]);

    $customer->status = $request->status;
    $customer->save();

    return redirect()->route('customers.index')->with('success', 'Status updated successfully.');
    }

    
 
}
