<?php namespace MoonWalkerz\Trustpilot\Components;

use Cms\Classes\ComponentBase;
use MoonWalkerz\Trustpilot\Models\Review;
use Artisan;
/**
 * Trustpilot Component
 *
 * @link https://docs.octobercms.com/3.x/extend/cms-components.html
 */
class Reviews extends ComponentBase
{

    public $reviews;
    /**
     * Message to display when there are no messages.
     * @var string
     */
    public $noReviewsMessage;
     /**
     * Reference to the page name for linking to posts.
     * @var string
     */
    public $reviewPage;

    public $pageParam;
     /**
     * If the post list should be ordered by another attribute.
     * @var string
     */
    public $sortOrder;
    public function componentDetails()
    {
        return [
            'name' => 'Trustpilot',
            'description' => 'Show trustpilot reviews'
        ];
    }

    /**
     * @link https://docs.octobercms.com/3.x/element/inspector-types.html
     */
    public function defineProperties()
    {
        return [
            'pageNumber' => [
                'title'       => 'moonwalkerz.press::lang.settings.page_number',
                'description' => 'moonwalkerz.press::lang.settings.page_number_description',
                'type'        => 'string',
                'default'     => '{{ :page }}',
            ],
            'reviewsPerPage' => [
                'title'             => 'moonwalkerz.press::lang.settings.reviews_per_page',
                'type'              => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'moonwalkerz.press::lang.settings.reviews_per_page_validation',
                'default'           => '10',
            ],
            'noReviewsMessage' => [
                'title'        => 'moonwalkerz.press::lang.settings.no_reviews',
                'description'  => 'moonwalkerz.press::lang.settings.no_reviews_description',
                'type'         => 'string',
                'default'      => 'No posts found',
                'showExternalParam' => false
            ],
            'sortOrder' => [
                'title'       => 'moonwalkerz.trustpilot::lang.properties.order_by',
                'description' => 'moonwalkerz.trustpilot::lang.properties.order_by_desc',
                'type'        => 'dropdown',
                'default'     => 'date desc'
            ],
            'greaterThan' => [
                'title' => 'moonwalkerz.trustpilot::lang.properties.greater_than',
                'description' => 'moonwalkerz.trustpilot::lang.properties.greater_than_desc',
                'default' => '0',
            ],
            'showBusinessHeader' => [
                'title'=> 'moonwalkerz.trustpilot::lang.properties.show_business_header',
                'description'=> 'moonwalkerz.trustpilot::lang.properties.show_business_header_desc',
                'type' => 'checkbox',
                'default' => false,
                
            ],
            
        ];
    }

    public function onRun() {
        $this->addCss('assets/trustpilot.css');
        $this->prepareVars();
        $this->reviews = $this->page['reviews'] = $this->listReviews();

        /*
         * If the page number is not valid, redirect
         */
        if ($pageNumberParam = $this->paramName('pageNumber')) {
            $currentPage = $this->property('pageNumber');

            if ($currentPage > ($lastPage = $this->reviews->lastPage()) && $currentPage > 1)
                return Redirect::to($this->currentPageUrl([$pageNumberParam => $lastPage]));
        }

        $this->reviews = $this->page['reviews'] = $this->reviews->map(function($review) {
            return [
                'id'=> $review->id,
                'title'=> $review->title,
                'text'=> $review->text,
                'rating' => $review->rating,
                'rating_stars'=> $this->generateStarRating($review->rating),
                'language' => $review->language,
                'consumer_id' => $review->consumer_id,
                'consumer_name' => $review->consumer_name,
                'consumer_reviews' => $review->consumer_reviews,
                'consumer_avatar' => $review->consumer_avatar,
                'business_reviews' => $review->business_reviews,
                'business_trustscore' => $review->business_trustscore,
                'business_stars' => $review->business_stars,
                'business_name' => $review->business_name,
                'business_image' => $review->business_image
            ];
        });

/*
$r->rating = $review['rating'];
            $r->language = $review['language'];
            $r->consumer_id = $review['consumer']['id'];
            $r->consumer_name = $review['consumer']['displayName'];
            $r->consumer_reviews = $review['consumer']['numberOfReviews'];
            $r->consumer_avatar = $review['consumer']['imageUrl'];
            $r->business_reviews = $business['numberOfReviews'];
            $r->business_trustscore = $business['trustScore'];
            $r->business_stars = $business['stars'];
            $r->business_name = $business['displayName'];
            $r->business_image = $business['profileImageUrl'];
            */
    }
    protected function prepareVars()
    {
        $this->pageParam = $this->page['pageParam'] = $this->paramName('pageNumber');
        $this->noReviewsMessage = $this->page['noReviewsMessage'] = $this->property('noReviewsMessage');
    }

    protected function listReviews()
    {
       
        $reviews = Review::listFrontEnd([
            'page'       => $this->property('pageNumber'),
            'sort'       => $this->property('sortOrder'),
            'perPage'    => $this->property('reviewsPerPage'),
        ]);


        return $reviews;
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
