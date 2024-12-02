<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;
/*    protected $redirectTo = '/';*/

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username()
    {
        return 'username';
    }

    public function loguin(Request $request)
    {
        // Validar que el usuario haya provisto los datos necesarios
        // para hacer la autenticación: "username" y "password".
        try {
            $request->validate([
                'username' => 'required|string|exists:users,username',
                'password' => 'required|string|min:4',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->status);
        }

        // Verificar que los datos provistos sean correctos y que
        // efectivamente el usuario se autentique con ellos utilizando
        // los datos de la tabla "users".
        if (!Auth::attempt($request->only('username', 'password'))) {
            return response()->json([
                'message' => 'Invalid login details'
            ], 401);
        }

        // Una vez autenticado, obtener la información del usuario en sesión.
        $tokenType = 'Bearer';
        $user = User::where('username', $request['username'])->firstOrFail();

        // Borrar los tokens anteriores (tipo Bearer) del usuario para
        // evitar que tenga más de uno del mismo tipo.
        $user->tokens()->where('name', $tokenType)->delete();

        // Crear un nuevo token tipo Bearer para el usuario autenticado.
        $token = $user->createToken($tokenType);

        // Enviar el token recién creado al cliente.
        return response()->json([
            'token' => $token->plainTextToken,
            'type' => $tokenType
        ], 200);
    }

    /* public function logout(Request $request)
    {
        // Revocar el token actual del usuario autenticado
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Token revoked'
        ], 200);
    } */
}


