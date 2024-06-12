<?php
require '../vendor/autoload.php';
use jcobhams\NewsApi\NewsApi;

class News extends Controller {

    private $newsApiKey;

    public function __construct() {
        $this->newsApiKey = 'fcf6e8c809984dde9d3eb9302c470ac9'; 
    }

    public function read() {
        if (!isLoggedIn()) {
            error_log("User not logged in, redirecting to login page.");
            header("Location: " . URLROOT . "/users/login");
            return;
        }

        $news = [];

        try {
            // Create a new instance of NewsApi
            $newsapi = new NewsApi($this->newsApiKey);
            $query = 'student';
            $fromDate = (new DateTime())->modify('-5 days')->format('Y-m-d');
            $sortBy = 'publishedAt';

            // Fetch the news articles
            $all_articles = $newsapi->getEverything($query, null, null, null, $fromDate, null, 'en', $sortBy, 10, 1);

            // Check for API errors
            if (isset($all_articles->status) && $all_articles->status == 'error') {
                throw new Exception('API Error: ' . $all_articles->message);
            }

            // Check if articles are returned
            if (isset($all_articles->articles) && !empty($all_articles->articles)) {
                $news = $all_articles->articles;

                // Log and echo fetched articles
                foreach ($news as $article) {
                    error_log("Fetched article: " . $article->title);
                }
            } else {
                error_log("No articles found for the query: " . $query);
                echo "No articles found for the query: " . $query . "<br>";
            }

        } catch (Exception $e) {
            error_log("Error fetching news: " . $e->getMessage());
            echo "Error fetching news: " . $e->getMessage() . "<br>";
        }

        $data = [
            'news' => $news,
        ];
        $this->view('notifications/news', $data);
    }
}
