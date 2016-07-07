<?php
namespace App\Models;

class Annotation extends BaseModel
{
    var $table = 'annotations';
    var $fillable = ['page_id', 'text', 'quote', 'ranges'];

    var $casts = [
        'ranges' => 'json'
    ];

    /**
     * The model relationships.
     *
     * @var array
     */
    public static $relationsData = array(
        'bookmark' => array(self::BELONGS_TO, Bookmark::class, 'foreignKey' => 'page_id'),
    );
}