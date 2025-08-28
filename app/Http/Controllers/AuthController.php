<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;

class AuthController extends Controller
{
    public function showRegisterForm() {
        return view('register');
    }

    public function register(Request $request) {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:6',
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $photoName = time() . '.' . $request->photo->extension();
        $request->photo->move(public_path('photos'), $photoName);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'photo' => $photoName,
            'is_approved' => false, // default to pending
        ]);

        return redirect('/login')->with('success', 'Registered successfully! Please wait for approval.');
    }

    public function showLoginForm() {
        return view('login');
    }

    public function login(Request $request) {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            if (!Auth::user()->is_approved) {
                Auth::logout();
                return back()->withErrors(['email' => 'Your account is pending approval.']);
            }

            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    public function dashboard() {
        return view('dashboard', ['user' => Auth::user()]);
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    /**
     * Serve the reference ID image (ako.png) for client-side comparison
     */
    public function idImage()
    {
        $path = base_path('ako.png');
        if (!File::exists($path)) {
            abort(404, 'ID image not found');
        }
        return response()->file($path, [
            'Content-Type' => File::mimeType($path),
            'Content-Disposition' => 'inline; filename="ako.png"'
        ]);
    }

    /**
     * Login the user when client-side scan succeeds
     */
    public function loginByScan(Request $request)
    {
        // Basic server-side guard: require a boolean flag that client only sends on exact match
        $request->validate([
            'scan_ok' => 'required|boolean'
        ]);

        if (!$request->boolean('scan_ok')) {
            return response()->json(['message' => 'Scan failed'], 422);
        }

        // Choose an approved user to authenticate the session
        $user = \App\Models\User::where('is_approved', true)->first();
        if (!$user) {
            return response()->json(['message' => 'No approved user available'], 422);
        }

        Auth::login($user);
        $request->session()->regenerate();
        return response()->json(['redirect' => url('/dashboard')]);
    }

    // Show list of users pending approval
    public function showPendingAccounts() {
        $users = User::where('is_approved', false)->get();
        return view('add-new-user', compact('users'));
    }

    // Approve user
    public function approveUser($id) {
        $user = User::findOrFail($id);
        $user->is_approved = true;
        $user->save();

        return redirect()->back()->with('success', 'User approved successfully.');
    }

    // Reject user (delete)
    public function rejectUser($id) {
        $user = User::findOrFail($id);
        $user->delete(); // Use soft delete if preferred
        return redirect()->back()->with('success', 'User rejected and deleted.');
    }
}
    