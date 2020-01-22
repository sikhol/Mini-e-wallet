<?php

namespace App\Http\Controllers;
use App\Helpers\ResponseHelper;
use App\Helpers\ValidationHelper;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Validator;
use JWTAuth;
use Auth;
use App\User;
use App\Models\UserBalance;
use App\Models\UserBalanceHistory;
use App\Models\BlanceBank;
use App\Models\BalanceBankHistory;
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
    public function topupBalance(Request $request){
        $input = $request->all();
        $messages = [
            'required' => ':attribute harus di isi',
            'numeric' => ':attribute harus di berupa angka',
        ];
        $validator = Validator::make($input, [
            'nominal' => 'required|numeric',
            'id_bank' => 'required|numeric',
            'type' => 'required',
            'ip'   => 'required',
            'location' => 'required',
            'user_agent' => 'required',
            'author' => 'required'

        ], $messages);

        if ($validator->fails()) {
            return $this->validationHelper->response($validator);
        }

        $topup = New UserBalance;
        $topup->user_id= Auth::user()->id;
        $topup->balance= $input['nominal'];
        $topup->balance_achieve=$input['nominal'];
        $topup->save();

        $count_topup= UserBalance::where('user_id',Auth::user()->id)->count();

        $history_topup= new UserBalanceHistory;
        $history_topup->user_balance_id= $topup->id;
        $history_topup->balance_bank_id = $input['id_bank'];
        if ($count_topup == 1){
            $history_topup->balance_before=0;
        }else{
            $history_topup->balance_before= $topup->balance - $input['nominal'];
        }
        $history_topup->balance_after = $topup->balance + $input['nominal'];
        $history_topup->activity = 'TopUp';
        $history_topup->type = $input['type'];
        $history_topup->ip = $input['ip'];
        $history_topup->location = $input['location'];
        $history_topup->user_agent = $input['user_agent'];
        $history_topup->author = $input['author'];
        $history_topup->save();

        $balance_bank = BlanceBank::where('id',$input['id_bank'])->first();

        if(!empty($balance_bank)){
            $balance_bank->balance = $balance_bank->balance - $input['nominal'];
            $balance_bank->balance_achieve= $balance_bank->balance;
            $balance_bank->save();

            // $count_balance_bank= BalanceBankHistory::where('balance_bank_id',$balance_bank->id)->count();

            $history_balance_bank= new BalanceBankHistory;
            $history_balance_bank->balance_bank_id= $balance_bank->id;
            $history_balance_bank->balance_before= $balance_bank->balance + $input['nominal'];
            $history_balance_bank->balance_after= $balance_bank->balance;
            $history_balance_bank->activity= 'Transfer';
            $history_balance_bank->type=  $input['type'];
            $history_balance_bank->ip=  $input['ip'];
            $history_balance_bank->location=  $input['location'];
            $history_balance_bank->user_agent=  $input['user_agent'];
            $history_balance_bank->author=  $input['author'];
            $history_balance_bank->save();
        }

        return response()->json($this->responseHelper->successWithoutData("dana berhasil di topup"), 200);

    }
    

}
