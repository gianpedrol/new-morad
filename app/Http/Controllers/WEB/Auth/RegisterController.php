<?php

namespace App\Http\Controllers\WEB\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Mail\UserRegistration;
use Str;
use Mail;
use App\Rules\Captcha;
use App\Models\Setting;
use App\Models\BannerImage;
use App\Models\Navigation;
use App\Models\ManageText;
use App\Models\EmailTemplate;
use App\Models\ValidationText;
use App\Models\NotificationText;
use App\Helpers\MailHelper;

class RegisterController extends Controller
{


    use RegistersUsers;


    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest:web');
    }


    public function userRegisterPage()
    {
        return redirect()->to('/invalid');
    }
    private function isGenericEmail($email, $genericPrefixes)
    {
        // Obter o prefixo do e-mail (parte antes do @)
        $emailPrefix = explode('@', $email)[0];

        // Verificar se o prefixo do e-mail está na lista de prefixos genéricos
        foreach ($genericPrefixes as $prefix) {
            if (strtolower($emailPrefix) == $prefix) {
                return true;
            }
        }

        return false;
    }

    public function storeRegister(Request $request)
    {

        $genericEmailPrefixes = ['admin', 'teste', 'dev', 'info', 'contact', 'sales', 'support'];

        $rules = [
            'name' => 'required',
            'email' => [
                'required',
                'unique:users',
                'email',
                function ($attribute, $value, $fail) use ($genericEmailPrefixes) {
                    if ($this->isGenericEmail($value, $genericEmailPrefixes)) {
                        $fail(trans('Coloque um e-mail válido'));
                    }
                },
            ],
            'password' => 'required|min:6|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/',
            'g-recaptcha-response' => new Captcha()
        ];


        $customMessages = [
            'name.required' => trans('user_validation.Name is required'),
            'name.unique' => trans('user_validation.Name already exist'),
            'email.required' => trans('user_validation.Email is required'),
            'email.email' => trans('Coloque um e-mail válido'),
            'email.unique' => trans('user_validation.Email already exist'),
            'password.required' => trans('user_validation.Password is required'),
            'password.min' => trans('Senha precisa ter 6 caracteres e precisa ser forte'),
            'password.regex' => trans('Senha Precisa ter 1 maiúsculo e 1 caracter especial'),
        ];
        $this->validate($request, $rules, $customMessages);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->status = 1;
        $user->creci = $request->creci;
        $user->slug = mt_rand(10000000, 99999999);
        $user->password = Hash::make($request->password);
        $user->email_verified_token = Str::random(100);
        $user->save();
        MailHelper::setMailConfig();

        $template = EmailTemplate::where('id', 4)->first();
        $message = $template->description;
        $subject = $template->subject;
        $message = str_replace('{{user_name}}', $user->name, $message);

        Mail::to($user->email)->send(new UserRegistration($message, $subject, $user));

        $notification = trans('user_validation.Register Successfully. Please Verify your email');
        return response()->json(['success' => $notification]);
    }

    public function userVerify($token)
    {

        $user = User::where('email_verified_token', $token)->first();
        if ($user) {
            $user->email_verified_token = null;
            $user->status = 1;
            $user->email_verified = 1;
            $user->save();

            $notification = trans('user_validation.Verification Successfully');
            $notification = array('messege' => $notification, 'alert-type' => 'success');
            return  redirect()->route('login')->with($notification);
        } else {

            $notification = trans('user_validation.Invalid token');
            $notification = array('messege' => $notification, 'alert-type' => 'error');
            return redirect()->route('register')->with($notification);
        }
    }
}
