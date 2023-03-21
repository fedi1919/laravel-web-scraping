<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;
use PHPUnit\Exception;
use Symfony\Component\DomCrawler\Crawler;

trait ScrapingTrait
{

    protected function BeginScrape($client, $crawler, $domain, $mainSelector, $titleSelector, $priceSelector, $urlSelector, $imageSelector, $descriptionSelector)
    {
        $data = [];
        try {
            $crawler->filter($mainSelector)
                ->children()
                ->each(function ($node) use ($client, $domain, $titleSelector, $priceSelector, $urlSelector, $imageSelector, $descriptionSelector, &$data) {

                    //local data variable
                    $innerData = [];

                    //Get the title
                    $title = $node->filter($titleSelector)->text();

                    //Get the Price
                    $price = $node->filter($priceSelector)->text();

                    //Get the image src
                    $imageSrc = $node->filter($imageSelector)->attr('src');
                    $imageDataSrc = $node->filter($imageSelector)->attr('data-src');
                    $image = !str_starts_with($imageSrc,'data:image') ? $imageSrc : $imageDataSrc;
                    //Get to the product url
                    $productPageUrl = $node->filter($urlSelector)->attr('href');
                    if (str_contains($productPageUrl, $domain) === false) {
                        //Set the product url
                        $productPageUrl = $domain . $productPageUrl;
                    }

                    //Go to the product page
//                $newResponse = $client->request('GET', $productPageUrl);
//                $productPageHtml = $newResponse->getContent();
//                $secondCrawler = new Crawler($productPageHtml);
//
//                //Get the product description
//                $description = $secondCrawler->filter($descriptionSelector)->text();
//                dd($title, $description);

                    $innerData['title'] = $title;
                    $innerData['price'] = $price;
                    $innerData['image'] = $image;
                    $innerData['url'] = $productPageUrl;
                    //$innerData['description'] = $description;

                    $data[] = $innerData;
                });
            return $data;
        } catch (Exception $e) {
            return $data;
        }
    }
}
