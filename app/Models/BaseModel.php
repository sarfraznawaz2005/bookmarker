<?php
/**
 * Created by PhpStorm.
 * User: Sarfraz
 * Date: 11/20/2015
 * Time: 12:33 PM
 */

namespace App\Models;

use Carbon\Carbon;
use LaravelArdent\Ardent\Ardent;

class BaseModel extends Ardent
{
    // purgable fields
    public $purgeFields = [];

    // Auto Hydrate
    public $autoHydrateEntityFromInput = true; // hydrates on new entries' validation
    public $forceEntityHydrationFromInput = true; // hydrates whenever validation is called (for updates)
    public $autoPurgeRedundantAttributes = true;

    // Auto hash passwords
    public static $passwordAttributes = ['password'];
    public $autoHashPasswordAttributes = true;

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);

        $this->purgeFilters[] = function ($key) {
            return !in_array($key, $this->purgeFields);
        };
    }

    // global dates format when showing up
    public function getCreatedAtAttribute($attr)
    {
        return Carbon::parse($attr)->format('d F, Y - h:i');
    }

    public function getUpdateAtAttribute($attr)
    {
        return Carbon::parse($attr)->format('d F, Y - h:i');
    }
}