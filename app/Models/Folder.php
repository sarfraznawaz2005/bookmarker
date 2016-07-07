<?php

namespace App\Models;

class Folder extends BaseModel
{
    protected $orderBy = 'name';
    protected $orderDirection = 'ASC';

    /**
     * The validation rules.
     *-
     * @var array
     */
    public static $rules = [
        'name' => 'required|max:50',
        'user_id' => 'required',
    ];

    /**
     * The custom validation messages.
     *
     * @var array
     */
    public static $customMessages = [
        'name.required' => 'The folder name is required',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'user_id',
    ];

    /**
     * The model relationships.
     *
     * @var array
     */
    public static $relationsData = array(
        'user' => array(self::BELONGS_TO, User::class),
        'bookmarks' => array(self::HAS_MANY, Bookmark::class),
    );

    # global scope that will be applied to all queries
    public function newQuery($excludeDeleted = true)
    {
        return parent::newQuery()->orderBy($this->orderBy, $this->orderDirection);
    }

}
