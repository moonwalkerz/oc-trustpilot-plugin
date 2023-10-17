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
     * If the reviews list should be ordered by another attribute.
     * @var string
     */
    public $sortOrder;

    public $paginate;

    public $showBusinessUnit = false;

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
                'title'       => 'moonwalkerz.trustpilot::lang.settings.page_number',
                'description' => 'moonwalkerz.trustpilot::lang.settings.page_number_desc',
                'type'        => 'string',
                'default'     => '{{ :page }}',
            ],
            'reviewsPerPage' => [
                'title'             => 'moonwalkerz.trustpilot::lang.settings.reviews_per_page',
                'type'              => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'moonwalkerz.trustpilot::lang.settings.reviews_per_page_validation',
                'default'           => '10',
            ],
            'paginate' => [
                'title'             => 'moonwalkerz.trustpilot::lang.settings.paginate',
                'type'              => 'checkbox',
                'default'           => '1',
            ],
            'css' => [
                'title'        => 'moonwalkerz.trustpilot::lang.settings.css',
                'description'  => 'moonwalkerz.trustpilot::lang.settings.css_desc',
                'type' => 'checkbox',
                'default'      => true,
            ],
            'sortOrder' => [
                'title'       => 'moonwalkerz.trustpilot::lang.settings.order_by',
                'description' => 'moonwalkerz.trustpilot::lang.settings.order_by_desc',
                'type'        => 'dropdown',
            
            ],
            'greaterThan' => [
                'title' => 'moonwalkerz.trustpilot::lang.settings.greater_than',
                'description' => 'moonwalkerz.trustpilot::lang.settings.greater_than_desc',
                'default' => 0,
            ],
            'showBusinessUnit' => [
                'title'=> 'moonwalkerz.trustpilot::lang.settings.show_business_header',
                'description'=> 'moonwalkerz.trustpilot::lang.settings.show_business_header_desc',
                'type' => 'checkbox',
                'default' => false,  
            ],
            
        ];
    }
    public function getReviewPageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }
 
    public function getSortOrderOptions()
    {
        return Review::$allowedSortingOptions;
    }

    public function onRun() {
        if ($this->property('css')) {
            $this->addCss('assets/trustpilot.css');
        }


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
    }
    protected function prepareVars()
    {
        $this->paginate = $this->page['paginate'] = $this->property('paginate');
        $this->showBusinessUnit = $this->page['showBusinessUnit'] = $this->property('showBusinessUnit');
        $this->pageParam = $this->page['pageParam'] = $this->paramName('pageNumber');
        $this->noReviewsMessage = $this->page['noReviewsMessage'] = $this->property('noReviewsMessage');
    }

    protected function listReviews()
    {
       
        $reviews = Review::isVisible()->listFrontEnd([
            'page'       => $this->property('pageNumber'),
            'sort'       => $this->property('sortOrder'),
            'perPage'    => $this->property('reviewsPerPage'),
        ]);


        return $reviews;
    }

   


}
