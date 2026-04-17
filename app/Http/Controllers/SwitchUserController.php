<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Product;


class SwitchUserController extends Controller
{
    public function switchTo(Request $request, $userId)
    {
        if (!Auth::user()->is_admin) {
            abort(403, 'Unauthorized');
        }
        $user = User::find($userId);
        session(['admin_id' => Auth::id()]);
        Auth::login($user);
        return redirect('/dashboard')->with('success', 'Switched to: ' . $user->name);
    }

    public function switchBack(Request $request)
    {
        $adminId = session('admin_id');
        if (!$adminId) {
            abort(403, 'Session expired. Cannot switch back.');
        }
        $admin = User::findOrFail($adminId);

        Auth::login($admin);
        session()->forget('admin_id');
        return redirect('/dashboard')->with('success', 'Switched back to admin.');
    }
    public function dashboard()
    {
        $users = User::all();
        $totalUsers = $users->count();
        $totalProducts = Product::count();
        return view('dashboard', compact('users', 'totalUsers', 'totalProducts'));
    }

}
