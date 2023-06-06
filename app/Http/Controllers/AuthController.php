<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Throwable;

class AuthController extends Controller
{
    function createToken()
    {
        $string = sha1(rand());
        return substr($string, 0, 256);
    }

    function login(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'login' => 'required',
                'password' => 'required'
            ]);

            $adminQuery = Admin::query()->where('login', $validatedData['login']);
            if (!$adminQuery->exists()) {
                return $this->returnBadResponse(400, 'Wrong login');
            }

            $admin = $adminQuery->first();

            if (!password_verify($validatedData['password'], $admin->password)) {
                return $this->returnBadResponse(400, 'Wrong password');
            }

            $token = $this->createToken();
            $admin->update([
                'token' => $token,
                'tokenTime' => Carbon::now()
            ]);

            return response(['token' => $token], 200);
        } catch (ValidationException $e) {
            return $this->returnBadResponse(400, $e->errors());
        } catch (Throwable $e) {
            return $this->returnBadResponse(500, $e->getMessage());
        }
    }

    function logout(Request $request)
    {
        try {
            $admin = Admin::query()->where('token', $request->get('admin')['token'])->first();
            $admin->token = null;
            $admin->save();

            return response('', 200);
        } catch (ValidationException $e) {
            return $this->returnBadResponse(400, $e->errors());
        } catch (Throwable $e) {
            return $this->returnBadResponse(500, $e->getMessage());
        }
    }
}
