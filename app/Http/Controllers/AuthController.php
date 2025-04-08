<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    //

    public function login(Request $request)
    {
        // Validate the request data
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Check if user exists and password matches
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // Generate the token for the user
        $token = $user->createToken('YourAppName')->plainTextToken;

        // Get the role(s) of the user (this returns a collection of role names)
        $roles = $user->getRoleNames(); // returns an array of roles

        // If user has roles, we will take the first one
        $role = $roles->isNotEmpty() ? $roles->first() : null;

        // Return response with token and role
        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'role' => $role,  // Send the role (can be multiple roles)
        ]);
    }
}
