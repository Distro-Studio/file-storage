<?php

namespace App\Http\Controllers;

use App\Http\Resources\DataResource;
use App\Http\Resources\WithoutDataResource;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'username' => ['required'],
            'password' => ['required'],
        ], [
            'username.required' => 'Username harus diisi',
            'password.required' => 'Password harus diisi',
        ]);

        if ($validator->fails()) {
            return response()->json(new WithoutDataResource(Response::HTTP_NOT_ACCEPTABLE, $validator->messages()),Response::HTTP_NOT_ACCEPTABLE);
        }

        if(Auth::attempt([
            'email' => $request->username,
            'password' => $request->password
        ])){
            $user = User::where('id', Auth::user()->id)->first();
            $now = Carbon::now('Asia/Jakarta')->addMinutes(30);

            $token = $user->createToken('TLogin',['*'],$now)->plainTextToken;
            // $user->push($arrtoken);

            // if (Gate::denies('create.unitkerja'))
            // {
            //     return response()->json(new WithoutDataResource(Response::HTTP_UNAUTHORIZED, 'Kamu tidak bisa tambah unitkerja'), Response::HTTP_UNAUTHORIZED);
            // }else{
            //     return response()->json(new WithoutDataResource(Response::HTTP_UNAUTHORIZED, 'Kamu bisa tambah unitkerja'), Response::HTTP_UNAUTHORIZED);
            //     return response()->json(new DataResource(Response::HTTP_OK, 'Login Berhasil', $role), Response::HTTP_OK);
            // }
            // if (Gate::check('isSAdmin'))
            // {
            //     return response()->json(new WithoutDataResource(Response::HTTP_UNAUTHORIZED, 'Kamu super admin'), Response::HTTP_UNAUTHORIZED);
            // }

            // if (Gate::check('isDirektur'))
            // {
            //     return response()->json(new WithoutDataResource(Response::HTTP_UNAUTHORIZED, 'Kamu direktur'), Response::HTTP_UNAUTHORIZED);
            // }
            // if (Gate::check('isAdmin'))
            // {
            //     return response()->json(new WithoutDataResource(Response::HTTP_UNAUTHORIZED, 'Kamu admin'), Response::HTTP_UNAUTHORIZED);
            // }
            // if (Gate::check('isKaryawan'))
            // {
            //     return response()->json(new WithoutDataResource(Response::HTTP_UNAUTHORIZED, 'Kamu Karyawan'), Response::HTTP_UNAUTHORIZED);
            // }

                // return response()->json(new WithoutDataResource(Response::HTTP_OK, 'Logout Berhasil'), Response::HTTP_UNAUTHORIZED);
            return response()->json(new DataResource(Response::HTTP_OK, 'Login Berhasil', [
                'token' => $token,
            ]), Response::HTTP_OK);
        }

        return response()->json(new WithoutDataResource(Response::HTTP_UNAUTHORIZED, 'Email atau password salah'), Response::HTTP_UNAUTHORIZED);
    }
}
