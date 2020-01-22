<?php

namespace App\Http\Controllers;
use App\Helpers\ResponseHelper;
use App\Helpers\ValidationHelper;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Validator;
use JWTAuth;
use App\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class UserController extends Controller
{
    public function __construct()
    {
        $this->responseHelper = new ResponseHelper;
        // $this->validationHelper = new ValidationHelper;

    }


    public function login(Request $request)
    {
        
        $input = $request->all();
        $messages = [
            'required' => ':attribute harus di isi',
        ];
        $validator = Validator::make($input, [
            'email' => 'required|string|email',
            'password' => 'required|string|min:6'

        ], $messages);

        if ($validator->fails()) {
            return $this->validationHelper->response($validator);
        }
        
        $userByEmail = User::where('email', '=', $input['email']);
        // dd($userByEmail);
        if ($userByEmail->count() > 0) {
            $user = $userByEmail->first();  
        }else{
            return response()->json($this->responseHelper->errorCustom(204, 'Akun tidak ditemukan'), 200);
        }

        $checked_pin = \Hash::check($input['password'], $user->password);

        if ($checked_pin) {
            $credentials = array(
                'email' => $user->email,
                'password' => $input['password']
            );
            try {
                // dd('hard');
                if (!$token = JWTAuth::attempt($credentials)) {
                    dd($token);
                    return response()->json($this->responseHelper->errorCustom(403, 'email atau password salah'), 403);
                }
            }catch (JWTException $e) {
                return response()->json($this->responseHelper->errorCustom(500, 'Could not create token'), 500);
            }
            return response()->json($this->responseHelper->successWithData([
                'token' => $token,
                // 'expired_at' => $expired_at->format('Y-m-d H:i:s')
            ]), 200);

        } else {
            return response()->json($this->responseHelper->errorCustom(403, 'email atau password salah'), 403);
        }
    }

    protected function register(array $data)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json($this->responseHelper->successWithoutData("User baru berhasil dibuat"), 200);
    }
    public function logout(Request $request){

    	
	   if ($request->header('authorization') == NULL || $request->header('authorization') == 'Bearer') {
           return response()->json($responseHelper->errorCustom(400, 'Token is Invalid'), 401);
           }
	 
    	try {
            $token= substr($request->header('authorization'), 7 );
    	    JWTAuth::invalidate($token);

    	    return response()->json($this->responseHelper->successWithoutData('Logout Success'),200);
    	} 
    	catch (JWTException $exception){
    	    return response()->json($this->responseHelper->errorCustom(403, 'Logout Fail'), 403);
    	}

    }

}
