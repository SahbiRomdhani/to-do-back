<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Auth;
use Validator;
use App\Models\User;
use App\Services\UserService;
use OpenApi\Attributes as OA;
use App\Http\Requests\StoreUserRequest;

class AuthController extends Controller
{
    private $userService;
    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }


    public function register(StoreUserRequest $request)
    {

        $request->validated();

        return $this->userService->create($request);

    }

/**
 * @SWG\Post(
 *     path="/api/login",
 *     summary="login user",
 *     tags={"login"},
 *     @SWG\Response(response=200, description="Successful operation"),
 *     @SWG\Response(response=400, description="Invalid request")
 * )
 */
    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password')))
        {
            return response()
                ->json(['message' => 'email or password are wrong'], 401);
        }
        $user = User::where('email', $request['email'])->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()
            ->json(['access_token' => $token, 'token_type' => 'Bearer', ]);

    }

}
