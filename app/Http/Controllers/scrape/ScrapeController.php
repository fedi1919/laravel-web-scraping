<?php

namespace App\Http\Controllers\scrape;

use App\Http\Controllers\Controller;
use App\Repositories\ScrapeRepository;
use Illuminate\Http\Request;
use Exception;

class ScrapeController extends Controller
{
    private $scrapeRepository;

    public function __construct(ScrapeRepository $scrapeRepository)
    {
        $this->scrapeRepository = $scrapeRepository;
    }

    public function scrape(Request $request)
    {

        try {
            return $this->scrapeRepository->scrape($request);
        } catch (Exception $exception)
        {
            return $exception;
        }
    }
}
