<?php

namespace App\Http\Controllers;

use App\Models\User;
use Hash;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function login(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        try {
            $email = $request->email;
            $password = $request->password;
            
            $user = User::where('email', $email)->first();
            
            if (!$user) {
                return redirect()->back()->withInput()->with('error', 'Email atau password salah.');
            }
            
            if (!Hash::check($password, $user->password)) {
                return redirect()->back()->withInput()->with('error', 'Email atau password salah.');
            }
            
            $request->session()->put('name', $user->name);
            $request->session()->put('id', $user->id);
            $request->session()->put('role', $user->role);
            $request->session()->put('email', $user->email);
            
            return redirect()->route('dashboard')->with('success', 'Login berhasil.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function logout(Request $request){
        $request->session()->forget([
            'name',
            'id',
            'role',
            'email'
        ]);
        return redirect()->route('login')->with('success', 'Logout berhasil.');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
