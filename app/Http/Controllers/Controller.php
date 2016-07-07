<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Laracasts\Flash\Flash;

class Controller extends BaseController
{
    use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;

    const UPDATE_MESSAGE = 'Updated Successfully!';
    const DELETE_MESSAGE = 'Deleted Successfully!';

    // for common data
    public function __construct()
    {
        $currentController = $this->getCurrentControllerName();

        // sharing is caring //
        View::share('currentController', $currentController);
        View::share('isAdmin', $this->isAdmin());
    }


    /**
     * @return mixed
     */
    protected function getCurrentControllerName()
    {
        $currentController = explode('@', Route::currentRouteAction());

        if (isset($currentController[0])) {
            $currentController = explode('\\', $currentController[0]);

            if (is_array($currentController)) {
                return end($currentController);
            }
        }

        return '';
    }

    /**
     * saves model and redirects to last page
     *
     * @param $model
     * @return mixed
     */
    public function saveAndRedirect($model)
    {
        if (!$model->save()) {
            return Redirect::back()->withErrors($model->errors());
        }

        Flash::info(self::UPDATE_MESSAGE);

        return Redirect::back();
    }

    /**
     * deletes model and redirects to last page
     *
     * @param $model
     * @return mixed
     */
    public function deleteAndRedirect($model)
    {
        if (!$model->delete()) {
            return Redirect::back()->withErrors($model->errors());
        }

        Flash::info(self::DELETE_MESSAGE);

        return Redirect::back();
    }

    /**
     * Checks user authentication for browser extension
     *
     * @param $email
     * @param $password
     */
    public function checkExtensionAuthentication($email, $password)
    {
        $user = User
            ::where('email', $email)
            ->first();

        if (!$user) {
            exit('Error: no user found.');
        }

        if (Hash::check($password, $user->password) == false) {
            exit('Error: user authentication failed.');
        }

        return $user;
    }

    # checks if loggedin user is admin
    public function isAdmin()
    {
        if (auth()->user()) {
            return auth()->user()->isadmin == 1;
        }

        return false;
    }
}
