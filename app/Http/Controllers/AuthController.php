<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\User;


class AuthController extends Controller
{
    public function unauthorized() {
        return response()->json([
            'error' => 'Acesso negado.'
        ], 401);
    }

    public function me() {
        $array = ['error' => ''];
        $user = auth()->user();
        $array['user'] = $user;

        return $array;
    }

    public function logout() {
        $array = ['error' => ''];
        auth()->logout();
        return $array;
    }

    public function login(Request $request) {
        $array = ['error' => ''];
        $validator = Validator::make($request->all(),[
            'email' => 'required',
            'password' => 'required',
        ]);
        if(!$validator->fails()) {
            $email = $request->input('email');
            $password = $request->input('password');
            
            $token = auth()->attempt([
                'email' => $email,
                'password' => $password
            ]);

            if(!$token) {
                $array['error'] = 'E-mail e/ou Senha incorretos!';
                return $array;
            }

            $array['token'] = $token;
            $user = auth()->user();
            $array['user'] = $user;

        } else {
            $array['error'] = $validator->errors()->first();
            return $array; 
        }
    }

    public function register(Request $request) {
        $array = ['error' => ''];

        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'cpf' => 'required|digits:11|unique:users,cpf',
            'password' => 'required',
           /*  'password_confirm' => 'required|same:password' igual a senha*/
        ]);

        if(!$validator->fails()) {
            $name = $request->input('name');
            $email = $request->input('email');
            $cpf = $request->input('cpf');
            $password = $request->input('password');
            $hash = password_hash($password, PASSWORD_DEFAULT);
             
            $newUser = new User();
            $newUser->name = $name;
            $newUser->email = $email;
            $newUser->cpf = $cpf;
            $newUser->password = $hash;

            $token = auth()->attempt([
                'email' => $email,
                'password' => $password
            ]);

            if(!$token) {
                $array['error'] = 'Ocorreu um erro.';
                return $array;
            }

            $array['token'] = $token;
            $user = auth()->user();
            $array['user'] = $user;


        } else {
            $array['error'] = $validator->errors()->first();
            return $array;
        }

        return $array;
    }
}
