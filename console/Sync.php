<?php namespace MoonWalkerz\Trustpilot\Console;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use DOMDocument;
use DOMXPath;
use MoonWalkerz\Trustpilot\Models\Review;
use MoonWalkerz\Trustpilot\Models\Settings;
use Carbon\Carbon;

/**
 * Sync Command
 *
 * @link https://docs.octobercms.com/3.x/extend/console-commands.html
 */
class Sync extends Command
{

    private $settings;
    private $data;

    /**
     * @var string signature for the console command.
     */
    protected $signature = 'trustpilot:sync {--clean : Clean the table before importing}';

    /**
     * @var string description is the console command description
     */
    protected $description = 'Load and sync latest reviews from Trustpilot.';

    /**
     * handle executes the console command.
     */
    public function handle()
    {
        $url=str_replace(array('http://', 'https://'), '', Settings::get('url'));
        $tp_url = str_replace(array('http://', 'https://'), '', Settings::get('tp_url'));

        if (empty($url) || empty($tp_url)) {
            $this->output->writeln('Please set the url and trustpilot url in the settings');
            return;
        }
        if ($this->option('clean')) {
            // Clean the table before importing
            Review::truncate();
            $this->output->writeln('Table cleaned.');
        }
        $client = new Client(['base_uri' => 'https://'.$tp_url]);

        $response = $client->request('GET', '/review/'.$url);
        $html= (string)$response->getBody();

        $dom = new DOMDocument();
     
        // Load the HTML content into the DOMDocument
        try {
            $dom->loadHTML($html);
        } catch (\Exception $e) {
        }
        $xpath = new DOMXPath($dom);   

// Use XPath to query for the script element with the specific ID
$query = '//script[@id="__NEXT_DATA__"]';

// Execute the query
$scriptNodeList = $xpath->query($query);


// Check if a matching script element was found
if ($scriptNodeList->length > 0) {
    // Get the first matching script element
    $scriptElement = $scriptNodeList->item(0);
    
    // Get the content of the script tag
    $scriptContent = $scriptElement->textContent;
    
    
    $this->data = json_decode($scriptContent, true);
    ray($this->data);
} else {
    echo "Script with ID '__NEXT_DATA__' not found.";
}

        $business = $this->data['props']['pageProps']['businessUnit'];
        $reviews = $this->data['props']['pageProps']['reviews'];


        ray($reviews);
        $this->output->writeln('Trustpilot import...');
        $this->output->writeln('Number of reviews: '.count($reviews));

        //Check if the reviews are already in the database and if not, add them

        //prepare progress bar
        $bar = $this->output->createProgressBar(count($reviews));

        foreach ($reviews as $review) {
            $r = Review::firstOrNew(['tp_id'=>$review['id']]);
            $r->title = $review['title'];
            $r->date = Carbon::parse($review['dates']['experiencedDate']);
            $r->text = $review['text'];
            $r->visible = true;
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

            $r->save();
            $bar->advance();
        }

    }
    
}
