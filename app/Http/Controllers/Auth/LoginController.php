<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;
use Facebook\Facebook;
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

    public function redirectToGoogle()
    {
        // Redirect the user to Google OAuth consent screen
        $authUrl = "https://accounts.google.com/o/oauth2/auth?"
            . "client_id=YOUR_CLIENT_ID"
            . "&redirect_uri=http://localhost:8000/auth/google/callback"
            . "&scope=https://www.googleapis.com/auth/userinfo.email+https://www.googleapis.com/auth/userinfo.profile"
            . "&response_type=code";

        header("Location: $authUrl");
        exit();
    }

    public function handleGoogleCallback(Request $request)
    {
        if (isset($_GET['code'])) {
            $data = array(
                'code' => $_GET['code'],
                'client_id' => "719081473908-dt60eaopgh8c159l02aro7mbq6judnu7.apps.googleusercontent.com",
                'client_secret' => "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",
                'redirect_uri' => "http://localhost:8000/auth/google/callback",
                'grant_type' => 'authorization_code'
            );

            $ch = curl_init('https://oauth2.googleapis.com/token');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($ch);
            curl_close($ch);

            $tokenData = json_decode($response, true);



            $accessToken = $tokenData['access_token'];


            // Now you have the access token, use it to fetch user data
            $url = 'https://www.googleapis.com/oauth2/v2/userinfo';
            $headers = array('Authorization: Bearer ' . $accessToken);

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $profileResponse = curl_exec($ch);
            curl_close($ch);

            $profileData = json_decode($profileResponse, true);

//            print_r($profileData['email']);die;/
            // Check if the user exists in your database based on the Google email
            $existingUser = User::where('email', $profileData['email'])->first();

            if ($existingUser) {
                Auth::login($existingUser);
            } else {
                // If the user does not exist, create a new user record in your database
                $newUser = User::create([
                    'name' => $profileData['name'],
                    'email' => $profileData['email'],
                    'password' => Hash::make($profileData['email']), // Empty password or set it to null, depending on your application logic
                    'salt_password' => $profileData['email'], // Empty password or set it to null, depending on your application logic
                    'role' => '2'
                ]);

                // Authenticate the newly registered user
                Auth::login($newUser);
            }

            return redirect(url('/'));
        } else {
            Session::flash('error','Something went wrong. Try again');
            return redirect(url('/login'));
        }
    }

// Inside a controller or a helper file
    function generateAppSecretProof($appSecret, $accessToken) {
        return hash_hmac('sha256', $accessToken, $appSecret);
    }
// Redirect to Facebook for authorization
    public function redirectToFacebook()
    {

        $facebookAppId = '246341838226511';
        $facebookAppSecret = '1c6125a535b9be51660320205061ae6d';
        $redirectUri = 'http://localhost:8000/facebook-callback';
        $scope = 'email';
        //Call Facebook API
        $facebook = new Facebook(array(
            'appId'  => $facebookAppId,
            'secret' => $facebookAppSecret
        ));
        $fbUser = $facebook->getUser();
        print_r($fbUser);
        die;
        $responseData = $response->getDecodedBody();

        if (isset($responseData['error'])) {
            var_dump($responseData['error']);
        } else {
            print_r($responseData);
        }


        die;

        $helper = $fb->getRedirectLoginHelper();
        $permissions = ['email']; // Add any additional permissions you need
        $loginUrl = $helper->getLoginUrl(url('/facebook/callback'), $permissions);

//        print_r($loginUrl);die;

        return redirect($loginUrl);
    }

// Handle Facebook Callback
    public function handleFacebookCallback(Request $request)
    {
        $facebookAppId = '246341838226511';
        $facebookAppSecret = '1c6125a535b9be51660320205061ae6d';
        $redirectUri = 'http://localhost:8000/facebook-callback';
        $scope = 'email';

        $fb = new Facebook([
            'app_id' => $facebookAppId,
            'app_secret' =>$facebookAppSecret,
            'default_graph_version' => 'v2.5', // Use the desired version
        ]);
        $helper = $fb->getRedirectLoginHelper();
        $accessToken = $helper->getAccessToken(route('facebook.callback'));
        print_r($helper);die;

        try {


            if (!$accessToken) {
                return redirect()->route('login')->with('error', 'Facebook authentication failed.');
            }

            $response = $fb->get('/me?fields=id,name,email', $accessToken);
            $userData = $response->getGraphUser();

            $facebookId = $userData->getId();
            $name = $userData->getName();
            $email = $userData->getEmail();

            // Now you can use $facebookId, $name, and $email to authenticate or create a user
            // ... your authentication and user creation logic ...

            return redirect('/dashboard'); // Redirect the user after successful login
        } catch (\Facebook\Exceptions\FacebookResponseException $e) {
            // Handle API response errors
            return redirect()->route('login')->with('error', 'Facebook API error: ' . $e->getMessage());
        } catch (\Facebook\Exceptions\FacebookSDKException $e) {
            // Handle SDK related errors
            return redirect()->route('login')->with('error', 'Facebook SDK error: ' . $e->getMessage());
        }
    }

    public function redirectTo() {
        $role = Auth::user()->role;
        switch ($role) {
            case '1':
                return '/admin/dashboard';
                break;
            case '2':
                return '/user-dashboard';
                break;

            default:
                return '/home';
                break;
        }
    }

    function login_page(){
        if(Auth::check()){
            if(Auth::user()->role == '1'){
                return view('web.login_page');
            }
        }else{
            return view('web.login_page');
        }
    }

    public function login(Request $request)
    {
//        print_r($request->all());die;
        $username = $request->username; //the input field has name='username' in form
        $password = $request->password;

        if(filter_var($username, FILTER_VALIDATE_EMAIL)) {
            //user sent their email
//            echo 'e';
            Auth::attempt(['email' => $username, 'password' => $password]);
        } else {
            echo 'n';
            //they sent their username instead
            Auth::attempt(['username' => $username, 'password' => $password]);
        }
        if ( Auth::check() ) {
            return redirect(url('admin/dashboard'));
        }else{
            return redirect(url('login'));
        }

    }

    public function check_login(Request $request)
    {
//        print_r($request->all());die;
        $username = $request->email; //the input field has name='username' in form
        $password = $request->password;

        if(filter_var($username, FILTER_VALIDATE_EMAIL)) {
            //user sent their email
//            echo 'e';
            Auth::attempt(['email' => $username, 'password' => $password]);
        } else {
//            echo 'n';
//            die;
            //they sent their username instead
            Auth::attempt(['email' => $username, 'password' => $password]);
        }
        if ( Auth::check() ) {
//            echo "yes";
            if(Auth::user()->role == 2){
                return redirect(url('/'));
            }else{
                return redirect(url('/'));
            }

        }else{
//            echo "np";
            \Session::flash('error','Username or Password is incorrect');
            return redirect(url('login'));
        }

    }

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
