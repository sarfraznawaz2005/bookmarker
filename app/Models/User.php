<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends BaseModel implements AuthenticatableContract,
    CanResetPasswordContract
{
    use Authenticatable;
    use CanResetPassword;

    /**
     * The validation rules.
     *-
     * @var array
     */
    public static $rules = [
        'name' => 'required|max:50',
        'email' => 'required|email',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'password_confirmation',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The model relationships.
     *
     * @var array
     */
    public static $relationsData = array(
        'folders' => array(self::HAS_MANY, Folder::class),
        'bookmarks' => array(self::HAS_MANY, Bookmark::class),
    );

    // gets count of user's total bookmarks
    public function bookmarkCount()
    {
        $bookmarks = auth()->user()->bookmarks;

        return count($bookmarks);
    }

    // gets count of user's total bookmarks that are read
    public function readBookmarkCount()
    {
        $readBookmarks = auth()->user()->bookmarks()->where('isread', 1)->get();

        return count($readBookmarks);
    }

    // checks if user is owner of given bookmark
    public function isBookmarkOwner(Bookmark $bookmark)
    {
        return $bookmark->user_id == auth()->user()->id;
    }
}
