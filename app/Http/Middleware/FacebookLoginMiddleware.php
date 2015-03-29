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

    protected $facebook;
    protected $facebook_acesstoken;
    protected $facebook_user;

    /**
     * Handle an incoming request trough the facebook api.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        Clockwork::startEvent('facebookMiddleware', 'starting the facebook authentication');

        $token = $this->handleFacebook();

        Clockwork::endEvent('facebookMiddleware');
        return $next($request);
    }

    /**
     * @param $target
     * Modifies the Route to point to the apropiate target.
     * this endpoints are setup in the /config/facebook_routes.php file
     *
     */
    protected function applyNewRoute($target) {

        $action = Route::current()->getAction();
        if (Route::getCurrentRequest()->isJson()) {
            //TODO Better JSON error-response
            $action['uses'] = function () {
                return json_encode(array("status" => "error", "data" => null, "message" => "this is probably a unauthorized request"));
            };
        } else {
            $action['uses'] = config('facebook_routes.' . $target);

        }
        Route::current()->setAction($action);

    }


    /**
     * Start the facebook authentication
     * @return AccessToken|int|null
     */
    protected function handleFacebook() {
        $this->facebook = \App::make('SammyK\LaravelFacebookSdk\LaravelFacebookSdk');

        $token = $this->checkAccessToken($this->facebook);

        if (!$token) {
            $this->applyNewRoute('login');
        } else if (is_int($token)) {
            Clockwork::info('Getting a facebook error: ' . $token);
            switch ($token) {
                case 100:
                    $this->applyNewRoute('login');
                    break;
                default:
                    $this->applyNewRoute('login');
                    break;
            }
        }
        return $token;
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
        if (!$token instanceof AccessToken) {
//            Auth::logout();
            return $token;
        }
        try {
            $facebook_request = $this->facebook->get('/me?fields=id,name,email', $token);
        } catch (FacebookSDKException $exception) {
            Clockwork::info([$exception->getMessage(), $exception->getCode()]);
            Session::forget('facebook_access_token');
            Auth::logout();

            return $exception->getCode();

        }

        /*
         * All is good, lets store the goodies
         */

        $this->facebook_acesstoken = $token;
        $this->facebook->setDefaultAccessToken($token);
        $this->facebook_user = $facebook_request->getGraphUser();


        Session::set('facebook_access_token', $token);
        Session::set('facebook_user', $this->facebook_user);

        $user = User::createOrUpdateGraphNode($this->facebook_user);
        Auth::login($user);

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
        Clockwork::info(['pullAccessToken', $token]);
        return $token;
    }

}
