<?php

namespace App\Http\Controllers\User;

use App\User;
use App\Mail\UserCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Transformers\UserTransformer;
use App\Http\Controllers\ApiController;

class UserController extends ApiController
{

    public function __construct(){
        $this->middleware('client.credentials')->only(['store', 'resend']); //protect the cilent
        $this->middleware('auth:api')->except(['store', 'verify', 'resend']); //protect the client and the user (me is here as well)
        $this->middleware('transform.input' . UserTransformer::class)->only(['store', 'update']);
        $this->middleware('scope:manage-account')->only(['show', 'update']);
        
        $this->middleware('can:view,user')->only(['show']);
        $this->middleware('can:update,user')->only(['update']);
        $this->middleware('can:delete,user')->only(['destroy']);

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->allowedAdminAction();

        $users = User::all();
        //return response()->json(['data' => $users], 200); //200 is OK response code
        //same as above, but with trait ApiResponser, used on ApiController
        return $this->showAll($users); 
        
    }

   
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ];

        $this->validate($request, $rules);

        $data = $request->all();
        $data['password'] = bcrypt($request->password);
        $data['is_verified'] = User::UNVERIFIED_USER;
        $data['verification_token'] = User::generateVerificationCode();
        $data['is_admin'] = User::REGULAR_USER;
        
        $user = User::create($data);

        //return response()->json(['data' => $user], 201);//201 means it has been created
        //same as above, but with trait ApiResponser, used on ApiController
        return $this->showOne($user, 201);//201 means it has been created
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //$user = User::findOrFail($id); //used when the param is $id        
        //return response()->json(['data' => $user], 200);
        //same as above, but with trait ApiResponser, used on ApiController
        return $this->showOne($user);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user/*$id*/)
    {
        //$user = User::findOrFail($id); //used when the param is $id             

        $rules = [
            'email' => 'email|unique:users,email,' . $user->id, //if user want to updare his profile his email gonna be the same, so we need to get new unique email, except the existing one of the current user
            'password' => 'min:6|confirmed',
            'is_admin' => 'in:' . User::ADMIN_USER . "," . User::REGULAR_USER, //
        ];

        $this->validate($request, $rules);

        if($request->has('name')){
            $user->name = $request->name;
        }

        //if the request has an emain and the email is diff than the original email    
        if($request->has('email') && $user->email != $request->email){
            $user->is_verified = User::UNVERIFIED_USER;
            $user->verification_token = User::generateVerificationCode();
            $user->email = $request->email;
        }

        if($request->has('password')){
            $user->password->bcrypt($request->password);
        }

        if($request->has('is_admin')){
            $this->allowedAdminAction();   
            
            if(!$user->isVerified()){ //if the user is not verified -> error
                //return response()->json(['error' => "Only verified users can modify the admin field", 'code' => 409], 409 );
                //same as above, but with trait ApiResponser, used on ApiController
                return $this->errorResponse("Only verified users can modify the admin field", 409);
            }

            $user->is_admin = $request->is_admin;
        }

        if(!$user->isDirty()){ //is idDirty method returns true that means that the user changed, in other case we are going to return an error
            //return response()->json(['error' => 'You need to specify a  different value to update', 'code' => 422], 422);
            //same as above, but with trait ApiResponser, used on ApiController
            return $this->errorResponse('You need to specify a  different value to update', 422);

        }

        $user->save();

        //return $response()->json(['data' => $user], 200);
        //same as above, but with trait ApiResponser, used on ApiController
        return $this->showOne($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user /*$id*/)
    {
        //$user = User::findOrFail($id); //used when the param is $id
        $user->delete();
        //return response()->json(['data' => $user], 200);
        //same as above, but with trait ApiResponser, used on ApiController
        return $this->showOne($user);

    }

    public function me(Request $request){ //we need the request in order to identify the user 
        
        $user = $request->user();

        return $this->showOne($user);
    }

    public function verify($token){
        $user = User::where('verification_token', $token)->firstOrFail();
        
        $user->is_verified = User::VERIFIED_USER;
        $user->verification_token = null;

        $user->save();

        return $this->showMessage('The account has been verified successfully');
    }

    public function resend(User $user){//as we are recieving the user id, we can use the Model Binding and obatin automatically the instance of a user
        //check if it's not a verified user yet
        if($user->isVerified()){
            return $this->errorMessage('This user is already verified', 409);
        }
                
        retry(5, function() use ($user){
            Mail::to($user)->send(new UserCreated($user));
        }, 100);

        return $this->showMessage('The verification email has been resend');
    }
}
