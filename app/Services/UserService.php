<?php

namespace App\Services;

use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserService
{
    public function create($request)
    {
        $user = new User();
        $user->email = $request->email;
        $user->first_name = $request->firstName;
        $user->last_name = $request->lastName;
        $user->password = Hash::make($request->password);
        $user->save();
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()
            ->json(['data' => $user  ,'access_token' => $token, 'token_type' => 'Bearer',]);

    }

    public function login(User $request): ?User
    {
        $user = User::where('email', $request['email'])->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()
            ->json(['message' => 'Hi '.$user->name.', welcome to home','access_token' => $token, 'token_type' => 'Bearer', ]);

    }
}
