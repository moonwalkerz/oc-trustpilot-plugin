<?php namespace MoonWalkerz\Trustpilot\Models;

use Model;
use DB;
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

        /**
     * The attributes on which the post list can be ordered
     * @var array
     */
    public static $allowedSortingOptions = [
        'title asc' => 'Title (ascending)',
        'title desc' => 'Title (descending)',
        'date asc' => 'Date Created (ascending)',
        'date desc' => 'Date Created (descending)',
        'rand' => 'Random'
    ];
  /**
     * Lists reviews for the front end
     *
     * @param        $query
     * @param  array $options Display options
     *
     * @return Reviews
     */
    public function scopeListFrontEnd($query, $options)
    {
        
        /*
         * Default options
         */
        extract(array_merge([
            'page'       => 1,
            'perPage'    => 30,
            'sort'       => 'date',
        ], $options));


        /*
         * Sorting
         */
        if (!is_array($sort)) {
            $sort = [$sort];
        }

        foreach ($sort as $_sort) {

            if (in_array($_sort, array_keys(self::$allowedSortingOptions))) {
                $parts = explode(' ', $_sort);
                if (count($parts) < 2) {
                    array_push($parts, 'desc');
                }
                list($sortField, $sortDirection) = $parts;
                if ($sortField == 'random') {
                    $sortField = Db::raw('RAND()');
                }
                $query->orderBy($sortField, $sortDirection);
            }
        }


        return $query->paginate($perPage, $page);
    }

    public function scopeIsVisible($query)
    {
        return $query->where('visible', 1);
    }

    public function getRatingStarsAttribute() {
        return $this->generateStarRating($this->rating);
    }
    public function getBusinessRatingStarsAttribute() {
        return $this->generateStarRating($this->business_stars);
    }
    
    public function generateStarRating($rating) {
        $output = '';
        $fullStars = floor($rating);
        $halfStar = ($rating - $fullStars) >= 0.5;
        $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
    
        // Generate full stars
        for ($i = 1; $i <= $fullStars; $i++) {
            $output .= '<span class="star full-star">&#9733;</span>';
        }
    
        // Generate half star if necessary
        if ($halfStar) {
            $output .= '<span class="star half-star">&#9733;&#189;</span>';
        }
    
        // Generate empty stars
        for ($i = 1; $i <= $emptyStars; $i++) {
            $output .= '<span class="star empty-star">&#9734;</span>';
        }
    
        return $output;
    }
}
