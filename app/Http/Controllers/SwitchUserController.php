<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Product;


class SwitchUserController extends Controller
{
    public function switchTo(Request $request, User $user)
    {
        $currentUser = Auth::user();

        if (! $currentUser || ! $currentUser->is_admin) {
            abort(403, 'Unauthorized');
        }

        if (session()->has('admin_id')) {
            return redirect()->route('dashboard')->with('warning', 'You are already switched as another user.');
        }

        if ((int) $currentUser->id === (int) $user->id) {
            return redirect()->route('users.index')->with('info', 'You are already using this admin account.');
        }

        session(['admin_id' => $currentUser->id]);
        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Switched to: ' . $user->name);
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

        return redirect()->route('dashboard')->with('success', 'Switched back to admin.');
    }
    public function dashboard()
    {
        $users = User::all();
        $totalUsers = $users->count();
        $totalProducts = Product::count();
        return view('dashboard', compact('users', 'totalUsers', 'totalProducts'));
    }

}
