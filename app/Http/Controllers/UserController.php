<?php
namespace App\Http\Controllers;

use App\Models\Coin;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function signInWithGoogle(Request $request)
    {
        $userData = [
            'email' => $request->input('email'),
            'name' => $request->input('name') ?: 'No Name',
            'profile_pic' =>
                $request->input('profile_pic') ?:
                'https://loremflickr.com/320/320',
            'logged_in' => true,
            'is_active' => (bool) $request->input('is_active'),
        ];

        $user = User::signInWithGoogle($userData);

        // Optionally, you can return the user data as a response
        return response()->json(['user' => $user]);
    }

    public function getUser($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }

        return response()->json(['user' => $user]);
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }

        // Update the user attributes
        $user->name = $request->input('name') ?: $user->name;
        $user->profile_pic =
            $request->input('profile_pic') ?: $user->profile_pic;
        $user->is_active = (bool) $request->input(
            'is_active',
            $user->is_active
        );
        $user->save();

        return response()->json(['user' => $user]);
    }

    public function deleteUser($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted successfully.']);
    }
    public function getCoins(Request $request)
    {
        $userId = $request->input('user_id');

        $coin = Coin::where('user_id', $userId)->first();

        if (!$coin) {
            return response()->json(['error' => 'User not found.'], 404);
        }

        $amount = $coin->amount;

        return response()->json(['amount' => $amount]);
    }

    public function addCoins(Request $request)
    {
        $userId = $request->input('user_id');
        $amount = $request->input('amount', 0);

        $user = User::where('user_id', $userId)->first();

        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }

        if (!is_numeric($amount) || $amount <= 0) {
            return response()->json(['error' => 'Invalid amount.'], 400);
        }

        $coin = Coin::where('user_id', $user->user_id)->first();

        if ($coin) {
            $coin->amount += $amount;
            $coin->save();
        } else {
            Coin::create([
                'user_id' => $user->id,
                'amount' => $amount,
            ]);
        }

        return response()->json(['message' => 'Coins added successfully.']);
    }

    public function subtractCoins(Request $request)
    {
        $userId = $request->input('user_id');
        $amount = (int) $request->input('amount', 0);

        $user = User::where('user_id', $userId)->firstOrFail();

        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }

        if (!is_int($amount) || $amount <= 0) {
            return response()->json(['error' => 'Invalid amount.'], 400);
        }

        $coin = Coin::where('user_id', $user->user_id)->first();

        if (!$coin) {
            return response()->json(['error' => 'User has no coins.'], 400);
        }

        if ($coin->amount < $amount) {
            return response()->json(['error' => 'Insufficient coins.'], 400);
        }

        $coin->amount = $coin->amount - $amount;
        $coin->save();

        return response()->json([
            'message' => 'Coins subtracted successfully.',
        ]);
    }
}
