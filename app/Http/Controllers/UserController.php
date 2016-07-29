<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\User;
use Auth;
use Laracasts\Flash\Flash;
use Redirect;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     *
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function index(User $user)
    {
        if (!$this->isAdmin()) {
            abort(404);
        }

        $title = 'Users';

        $users = $user->whereNotIn('id', [auth()->user()->id])->paginate(10);

        return view('pages.users', compact('title', 'users'));
    }

    /**
     * logs in as specified user
     *
     * @param $id
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function loginUser($id, User $user)
    {
        if (!$this->isAdmin()) {
            abort(404);
        }

        Auth::loginUsingId($id);

        $user = $user->findOrFail($id);

        Flash::info("Logged in as $user->name");

        return Redirect::to('/');
    }

    /**
     * sets read status for given bookmark.
     *
     * @param  int $id
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function setAdminStatus($id, User $user)
    {
        if (!$this->isAdmin()) {
            abort(404);
        }

        $user = $user->findOrFail($id);

        $isAdmin = $user->isadmin == 1 ? 0 : 1;
        $user->isadmin = $isAdmin;

        return $this->saveAndRedirect($user);
    }

    /**
     * Remove the specified folder from storage.
     *
     * @param  int $id
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, User $user)
    {
        if (!$this->isAdmin()) {
            abort(404);
        }

        $user = $user->where('id', $id)->first();

        if ($redirect = $this->deleteAndRedirect($user)) {

            // also delete folders of this folder
            $user->folders()->delete();

            // also delete bookmarks of this folder
            $user->bookmarks()->delete();

            return $redirect;
        }
    }
}
