<?php 
class AccountController extends BaseController
{
	// User login starts on view
	public function getLogin()
	{
		return View::make('account.login');
	}//End function Login
	public function postLogin()
	{
		$data=Input::all();
        $validator=Validator::make($data,
            array(
           'username'=>'required',
            'password'=>'required'
        ));
        if($validator->fails())
        {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        }
        else{
            $remember=(Input::has('remember'))? true : false;
            $username=$data['username'];
            $password=$data['password'];
            $active='Y';
            $field=filter_var($username,FILTER_VALIDATE_EMAIL)?'email':'username';
            if(Auth::attempt(array($field=>$username,'password'=>$password,'active'=>$active),$remember))
            {
                $mod=Auth::userType();
                Session::get('login_redirect') ? $url = Session::get('login_redirect') : $url = URL::to('/');
                // echo $mod. "<>".$url;exit;
                Session::put('developers.userId',Auth::user()->id);
                return Redirect::to($url)
                            ->with('login_redirect',Session::forget('login_redirect'));
            }
            else{
                return Redirect::back()->withInput()
                    ->withErrors(array('error'=>'Username or password incorrect'))
                    ->with('error','Username or password incorrect');
            }
        }
	}//End function login validation
	 public function getSignOut()
    {
        Auth::logout();
        $this->no_cache();
        return Redirect::to('/')
                ->with('msg',"<div class='alert alert-info'>Sign Out successfully </div>");
    }
    public function no_cache()
    {
	    header('Cache-Control: no-store, no-cache, must-revalidate');
	    header('Cache-Control: post-check=0, pre-check=0',false);
	    header('Pragma: no-cache');
    }
        //Forget password
    public function getForgetPassword()
    {
        return View::make('account.forget_password');
    }
    //send link to registered email
    public function postForgetPassword()
    {
         $validator=Validator::make(Input::all(),array(
            'email'=>'required|email'
        ));
        if($validator->fails())
        {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        }
        else
        {
            $email=Input::get('email');
            $check=User::whereEmail($email)->first();
            if($check)
            {
                $username=$check->username;
                $name=$check->displayname;
                $password=str_random(10);
                $check->resetcode=str_random(60);
                $check->tmp_password=Hash::make($password);
                $code = $check->resetcode.$password;
                if($check->save())
                {
                    Mail::send('emails.auth.forget_password',array('link'=>URL::to('account/recover',$code),'name'=>$name,'username'=>$username,'password'=>$password),function($message) use($email,$name){
                        $message->to($email,$name)->subject('Reset Password');
                    });
                    return Redirect::to('account/login')
                            ->with('success','Password reset link send to your mail');
                }
            }
            else
            {
                return Redirect::back()
                ->withErrors(array('error'=>'Please enter registered email'))
                ->withInput();
            }
        }
    }
    public function getRecover($code)
    {
            $data['id']=substr($code,0,60);
            $password=substr($code,60,70);
            $data['password']=$password;
            $validator=Validator::make($data,array('id'=>'alpha_num|min:60|max:60','password'=>'alpha_num|min:10|max:10'));
            if($validator->fails())
            {
               return Redirect::to('/')->with('error','Invalid link');
            }

            $user=User::where('resetcode','=',$data['id'])
                ->where('tmp_password','!=','')->first();
          
            if($user)
            {
                $user->password=$user->tmp_password;
                $user->tmp_password='';
                $user->resetcode='';
                if($user->save())
                {
                    if(Auth::attempt(array('email'=>$user->email,'password'=>$data['password'])))
                    {
                        Session::flash('password',$password);
                        return Redirect::to('account/create-password');
                    }

                }
                else{
                    return Redirect::to('/')->with('error','Invalid link');
                }
            }
            else{
                 return Redirect::to('/')->with('error','Invalid link');
            }
    }
    
    public function getCreatePassword()
    {
        return View::make('account.create_password');
    }
    public function postCreatePassword()
    {
        if($uId=Auth::user()->id)
        {
             $validator=Validator::make(Input::all(),
                        array(
                            'old_password' =>'required',
                            'new_password' =>'required',
                            'confirm_password'=>'required|same:new_password'
                            ));
             if($validator->fails())
             {
                return Redirect::back()
                ->withInput()
                ->withErrors($validator);
             }
            else{
                $user=User::find(Auth::user()->id);
                $old_password=Input::get('old_password');
                $new_password=Input::get('new_password');
                if(Hash::check($old_password,$user->getAuthPassword()))
                {
                    $user->password=Hash::make($new_password);
                    if($user->save())
                    {
                        $mod=Auth::getProfile();
                        return Redirect::to("/")
                            ->with('success','Your password has been changed.');
                    }
                    else{
                        return Redirect::back()
                            ->with('error','Your password could not change');
                    }
                }
                else{
                    return Redirect::back()
                        ->with('error','Your old password incorrect');
                }
            }
        }
        else
        {
            App::abort(404);
        }
    }
    public function getChangePassword()
    {
        return View::make('account.change_password');
    }
}