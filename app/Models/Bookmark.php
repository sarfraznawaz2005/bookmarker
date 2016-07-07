<?php

namespace App\Models;

use Laracasts\Flash\Flash;
use Redirect;

class Bookmark extends BaseModel
{
    public static $orderBy = 'id';
    public static $orderDirection = 'DESC';

    /**
     * The validation rules.
     *-
     * @var array
     */
    public static $rules = [
        'user_id' => 'required',
        'url' => 'required',
        'title' => 'required|max:255',
        'folder_id' => 'required',
    ];

    /**
     * The custom validation messages.
     *
     * @var array
     */
    public static $customMessages = [
        'folder_id.required' => 'The folder is required',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'title',
        'folder_id',
        'comments',
    ];

    /**
     * The model relationships.
     *
     * @var array
     */
    public static $relationsData = array(
        'folder' => array(self::BELONGS_TO, Folder::class),
        'user' => array(self::BELONGS_TO, User::class),
        'annotations' => array(self::HAS_MANY, Annotation::class, 'foreignKey' => 'page_id'),
    );

    # global scope that will be applied to all queries
    public function newQuery($excludeDeleted = true)
    {
        return parent::newQuery()->orderBy(self::$orderBy, self::$orderDirection);
    }

    # checks if given url for given user is already saved
    public function isBookmarked($url, User $user)
    {
        $bookmark = Bookmark
            ::where('url', $url)
            ->where('user_id', $user->id)
            ->first();

        if ($bookmark) {
            return true;
        }

        return false;
    }
}
