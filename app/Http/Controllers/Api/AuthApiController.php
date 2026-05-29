<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthApiController extends Controller
{
    /**
     * Register a new user
     * POST /api/v1/register
     */
    public function register(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $token = $user->createToken('eventra-mobile')->plainTextToken;

        return response()->json([
            'status'  => 'success',
            'message' => 'Account created successfully.',
            'data'    => [
                'user'  => $this->formatUser($user),
                'token' => $token,
                'token_type' => 'Bearer',
            ]
        ], 201);
    }

    /**
     * Login
     * POST /api/v1/login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Invalid email or password.',
            ], 401);
        }

        $user  = Auth::user();

        // Revoke old tokens and create fresh one
        $user->tokens()->delete();
        $token = $user->createToken('eventra-mobile')->plainTextToken;

        return response()->json([
            'status'  => 'success',
            'message' => 'Login successful.',
            'data'    => [
                'user'       => $this->formatUser($user),
                'token'      => $token,
                'token_type' => 'Bearer',
            ]
        ]);
    }

    /**
     * Get logged in user
     * GET /api/v1/me
     */
    public function me(Request $request)
    {
        $user = $request->user()->load('registrations.event');

        return response()->json([
            'status' => 'success',
            'data'   => [
                'user'               => $this->formatUser($user),
                'total_registrations' => $user->registrations->count(),
                'upcoming_events'     => $user->registrations
                    ->filter(fn($r) => $r->event->status === 'upcoming')
                    ->count(),
            ]
        ]);
    }

    /**
     * Update profile
     * PUT /api/v1/me/update
     */
    public function update(Request $request)
    {
        $data = $request->validate([
            'name'  => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $request->user()->id,
        ]);

        $request->user()->update($data);

        return response()->json([
            'status'  => 'success',
            'message' => 'Profile updated.',
            'data'    => $this->formatUser($request->user()->fresh()),
        ]);
    }

    /**
     * Logout
     * POST /api/v1/logout
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Logged out successfully.',
        ]);
    }

    // Format user data for API response
    private function formatUser(User $user): array
    {
        return [
            'id'    => $user->id,
            'name'  => $user->name,
            'email' => $user->email,
        ];
    }
}