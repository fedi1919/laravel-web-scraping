<?php

namespace App\Repositories;

use App\Traits\ScrapingTrait;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;

class ScrapeRepository
{
    use ScrapingTrait;

//    private $client;
//
//    public function  __construct(HttpClientInterface $client)
//    {
//        $this->client = $client;
//    }

    public function scrape($request)
    {
        //Get the inputs from the request
        $domain = $request->input('domain');
        $mainUrl = $request->input('mainUrl');
        $mainSelector = $request->input('mainSelector');
        $titleSelector = $request->input('titleSelector');
        $urlSelector = $request->input('productUrlSelector');
        $priceSelector = $request->input('priceSelector');
        $imageSelector = $request->input('imageSelector');
        $descriptionSelector = $request->input('descriptionSelector');
        $paginationUrl = $request->query('paginationUrl');
        $pagesNumber = $request->query('pagesNumber');

        //The data to be sent to the admin

        //Create a new instance of HttpClient
        $client = HttpClient::create();


        //Create a new instance of Crawler class
        $crawler = new Crawler();
        $data = array();
        if ($paginationUrl) {
            // Scrape data with pagination
            for ($i = 1; $i <= $pagesNumber; $i++) {
                //Fetch the html content from the crawler
                $pageUrl = $paginationUrl . $i;
                $html = file_get_contents($pageUrl);
                //Clear the crawler before add a new html to scrape
                $crawler->clear();
                $crawler->addHtmlContent($html);

                // Begin to scrape
                $data = $this->BeginScrape($client,
                    $crawler,
                    $domain,
                    $mainSelector,
                    $titleSelector,
                    $priceSelector,
                    $urlSelector,
                    $imageSelector,
                    $descriptionSelector);
            }
        } else {
            // Scrape the data from the main Url

            //Fetch the html content from the crawler
            $response = $client->request('GET', $mainUrl);
            $html = $response->getContent();
            $crawler->addHtmlContent($html);

            // Begin to scrape
            $data = $this->BeginScrape($client,
                $crawler,
                $domain,
                $mainSelector,
                $titleSelector,
                $priceSelector,
                $urlSelector,
                $imageSelector,
                $descriptionSelector);
        }

        return response()->json([
            'total products' => count($data),
            'data' => $data
        ]);
    }
}
