<?php namespace MoonWalkerz\Trustpilot\Models;

use Model;

/**
 * Model
 */
class Review extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\SoftDelete;

    /**
     * @var array dates to cast from the database.
     */
    protected $dates = ['deleted_at'];

    /**
     * @var string table in the database used by the model.
     */
    public $table = 'moonwalkerz_trustpilot_reviews';

    /**
     * @var array guarded fields from mass assignment.
     */
    protected $guarded = ['*'];

    /**
     * @var array fillable fields for mass assignment.
     */
    protected $fillable = [
        'id',
        'tp_id',
        'title',
        'date',
        'rating',
        'text',
        'visible',
        'consumer_id',
        'consumer_name',
        'consumer_reviews',
        'consumer_avatar',
        'language'
    ];

    /**
     * @var array rules for validation.
     */
    public $rules = [
    ];

}
