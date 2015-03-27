<?php namespace App\Http\Middleware;

use App\User;
use Closure;
use Facebook\Authentication\AccessToken;
use Facebook\Exceptions\FacebookSDKException;
use Auth;
use Facebook\FacebookRequest;
use Route;
use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;
use Clockwork;
use Session;

class FacebookLoginMiddleware {

    private $facebook;

    /**
     * Handle an incoming request trough the facebook api.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        Clockwork::startEvent('facebookMiddleware','starting the facebook authentication');
        $this->facebook = \App::make('SammyK\LaravelFacebookSdk\LaravelFacebookSdk');

        switch ($request->getMethod()) {
            case 'GET':

                break;
            case 'POST':
                break;

        }
        $token = $this->checkAccessToken($this->facebook);
        Clockwork::info($token);
        if (!$token) {
            $this->applyNewRoute('login');
        } else if(is_int($token)) {
            Clockwork::info('Getting a facebook error: ' . $token);
            switch($token) {
                case 100:
                    $this->applyNewRoute('login');
                    break;
                default:
                    $this->applyNewRoute('login');
                    break;
            }
        }



        Clockwork::endEvent('facebookMiddleware');
        return $next($request);
    }

    /**
     * @param $target
     * Modifies the Route to point to the apropiate target.
     * this endpoints are setup in the /config/facebook_routes.php file
     *
     */
    private function applyNewRoute($target) {
        $action = Route::current()->getAction();
        $action['uses'] = config('facebook_routes.' . $target);
        Route::current()->setAction($action);

    }

    /**
     * Check if the facebook token is set
     * @param LaravelFacebookSdk $facebook
     * @return AccessToken|int|null
     *
     */
    private function checkAccessToken(LaravelFacebookSdk $facebook) {
        $token = Session::get('facebook_access_token');

        if (!$token) {
            Clockwork::info('no storedtoken');
            $token = $this->pullAccessToken($facebook);

        }
        $token = $this->validateToken($token);

        return $token;

    }

    /**
     * Validates the token by calling the Facebook Graph API, returns the validated
     * token or returns the error code as an integer, or null if no token was passed
     *
     * If the token is validated the Facebook user object is saved as a session variable
     * and also logs in the user
     *
     * @param $token
     * @return AccessToken|int
     */
    private function validateToken($token) {
        if(!$token instanceof AccessToken) {
//            Auth::logout();
            return $token;
        }
        try {
            $facebook_request = $this->facebook->get('/me?fields=id,name,email',$token);
        } catch (FacebookSDKException $exception) {
            Clockwork::info([$exception->getMessage(), $exception->getCode()]);
            Session::forget('facebook_access_token');
            Auth::logout();
            return $exception->getCode();

        }
        Session::set('facebook_access_token',$token);
        $this->facebook->setDefaultAccessToken($token);
        $facebook_user = $facebook_request->getGraphUser();
        $user = User::createOrUpdateGraphNode($facebook_user);
        Session::set('facebook_user',$facebook_user);

        Auth::login($user);

        Clockwork::info($facebook_request);
        return $token;
    }



    /**
     * Tries to use different ways to get the token from the client
     * returns the AccessToken if retrieved. Null if none found or
     * the errorCode as integer if recieved
     *
     * @param LaravelFacebookSdk $facebook
     * @return AccessToken|int|null
     */
    private function pullAccessToken(LaravelFacebookSdk $facebook) {
        $token = null;
        try {
            $token = $facebook->getCanvasHelper()->getAccessToken();
        } catch (FacebookSDKException $exception) {
            Clockwork::info([$token, $exception->getMessage(), $exception->getCode()]);
            return (int)$exception->getCode();

        }
        if (!$token) {
            try {
                $token = $facebook->getJavaScriptHelper()->getAccessToken();
            } catch (FacebookSDKException $exception) {
                Clockwork::info([$token, $exception->getMessage(), $exception->getCode()]);
                return (int)$exception->getCode();

            }
        }
        Clockwork::info($token);
        return $token;
    }

}
