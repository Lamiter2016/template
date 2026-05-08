<?php

namespace App\Http\Controllers;

use App\Http\Resources\UsersResource;
use App\Models\Users;
use App\Helper\Ulti;
use App\Models\SysToken;
//use App\Models\Sessions;
use App\Models\Session;
use App\Models\SessionRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;



class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $users = Users::paginate(10);
        return UsersResource::collection($users);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function check()
    {
        //
        $users = Users::paginate(10);
        return UsersResource::collection($users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        //Hash::make($request->newPassword)
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $fields = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
            'email' => 'required|string'
        ]);

        // $users = new Users();
        // $users->username = $request->username;
        // $users->name = $request->name;
        // $users->email = $request->email;
        // $users->password = bcrypt($request->password);
        // $token = $users->createToken('token')->plainTextToken;
        $user = Users::create([
            'name' => $fields['name'],
            'username' => $fields['username'],
            'password' => bcrypt($fields['password']),
            'email' => $fields['email'],
        ]);
        // if($users->save()){
        //     $response = [
        //         'user' => $users,
        //         'token' => $token
        //     ];
        //     return response($response, 200);
        // }
        //$token =  $user->createToken('UserToken')->plainTextToken;
        $response = [
            'user' => $user,
        ];
        return response($response, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $user = Users::findOrFail($id);
        return new UsersResource($user);
    }
    public function search($name)
    {
        return Users::where('name', 'like', '%'.$name.'%')->get();
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $users = Users::findOrFail($id);
        $users->username = $request->username;
        $users->name = $request->name;
        $users->email = $request->email;
        $users->password = Hash::make($request->password);
        if($users->save()){
            return new UsersResource($users);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $users = Users::findOrFail($id);
        if($users->delete()){
            return new UsersResource($users);
        }
    }
    public function processLoginJson(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        $user = new Users();
        $auth = false;
        $tokenLogin = '';
        if(Auth::attempt($credentials)){
            $user = Users::getUser($credentials['email']);
            $auth = true;
            $idCode = Session::getToken($user->id);
            $tokenLogin = Ulti::genJWT($user->id);
            if (isset($idCode) && $tokenLogin == $idCode){
                $tokenLogin = $idCode;
            }else{
                $session =  new Session();
                $session->user_id = $user->id;
                $session->name = $user->name;
                $session->status = 'Y';
                $session->token = $tokenLogin;
                $session->save();
                $sessionRole = new SessionRole();
                $sessionRole->session_id = Session::getIdSessions($user->id);
                $sessionRole->role_id = Users::getRoleId($user->id);
                $sessionRole->save();
            }
            return response()->json([
                'auth' => $auth,
                'fullname' => $user->name,
                'email'    => $user->email,
                'token'   => $tokenLogin
            ]);
        }else{
            return response()->json([
                'auth' => $auth
            ]);
        }
    }


    public function processLogin(Request $request)
    {   
        // $response = "bạn đã login";
        // $auth = false;
        // $fields = $request->validate([
        //     'email' => ['required', 'email'],
        //     'password' => ['required'],
        // ]);
        // $user = Users::where('email', $fields['email'])->first();
        // if(!$user || !Hash::check($fields['password'],$user->password)){
        //     return response([
        //         'message' => 'Bad creds'
        //     ],401);
        return response($response, 200);
    }


    public function login(Request $request){

        return view('v1.public.login');
    }

    public function processLogout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect(route('login'));
    }


    public function register(){
        return view('v1.public.register');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function proccessRegister(Request $request)
    {
        $fields = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'password' => 'required|string',
            'email' => 'required|string',
            'termCond' => 'required',
        ]);
        if($fields['termCond']){
            $user = Users::create([
                'first_name' => $fields['first_name'],
                'last_name' => $fields['last_name'],
                'name'     => $fields['first_name'] . ' '. $fields['last_name'],
                'password' => bcrypt($fields['password']),
                'email' => $fields['email'],
            ]);
            $response = [
                'user' => $user,
            ];
            return response($response, 201);
        }
    }
}
