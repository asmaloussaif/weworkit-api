<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'lastName' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|string|in:client,freelancer,admin'
        ]);

        $user = User::create([
            'name' => $request->name,
            'lastName' => $request->lastName,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Assigner le rôle
        $user->assignRole($request->role);

        return response()->json(['message' => 'User registered successfully'], 201);
    }

   public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages(['email' => 'Invalid credentials']);
        }

        return response()->json([
            'token' => $user->createToken('auth_token')->plainTextToken,
            'role' => $user->getRoleNames(),
            'user'=>$user
        ]);
    } 

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }
    public function getUsers(Request $request)
{
    $users = User::whereHas('roles', function ($query) {
        $query->where('name', '!=', 'admin');
    })->get();

    
    $usersWithRoles = $users->map(function ($user) {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'lastName' => $user->lastName,
            'email' => $user->email,
            'role' => $user->getRoleNames(), 
            'created_at'=> $user->created_at,
        ];
    });

    return response()->json($usersWithRoles);
}
public function getFreelencerId(Request $request)
{
    $userIds = User::whereHas('roles', function ($query) {
        $query->where('name', 'freelancer');
    })->pluck('id');

    return response()->json($userIds);
}
public function destroy($id)
{
    $user = User::find($id);

    if (!$user) {
        return response()->json(['message' => 'User not found'], 404);
    }

    $user->delete();

    return response()->json(['message' => 'User deleted successfully']);
}

   public function getClient(Request $request)
{
    $users = User::whereHas('roles', function ($query) {
        $query->where('name', '=', 'client');
    })->get();

    
    $usersWithRoles = $users->map(function ($user) {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'lastName' => $user->lastName,
            'email' => $user->email,
            'role' => $user->getRoleNames(), 
            'created_at'=> $user->created_at,
        ];
    });

    return response()->json($usersWithRoles);
}
}