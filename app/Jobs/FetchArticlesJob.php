<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class FetchArticlesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    const categories = [
        "Politics",
        "Economy",
        "Technology",
        "Science and Health",
        "Sports",
        "Entertainment and Culture",
        "Environment",
        "Education",
        "Security and Justice",
        "International"
    ];

    public function __construct(){
    }

    public function handle(){
        foreach (self::categories as $category) {
            $newsAPiData = $this->fetchFromNewsApi($category);
            DB::table('articles')->insertOrIgnore($newsAPiData);
            usleep(50000); 
        }

      
        foreach (self::categories as $category) {
            $newYorkTimesAPiData = $this->fetchFromNewYorkTimesApi($category);
            DB::table('articles')->insertOrIgnore($newYorkTimesAPiData);
            usleep(50000); 
        }

   
        foreach (self::categories as $category) {
            $theGuardianAPiData = $this->fetchFromTheGuardianApi($category);
            DB::table('articles')->insertOrIgnore($theGuardianAPiData);
            usleep(50000); 
        }
    }

  
    public function fetchFromNewsApi($category) {
        $newsApiUrl = env('NEWS_API_URL'); 
        $newsApiKey = env('NEWS_API_KEY');
        $sevenDaysAgo = now()->subDays(10)->format('Y-m-d');
        $url = "{$newsApiUrl}everything?q={$category}&from={$sevenDaysAgo}&sortBy=publishedAt&apiKey={$newsApiKey}";
        $response = Http::get($url);
        usleep(50000); 
        if ($response->successful()) {
            $articles = $response->json()['articles'];
            $insertData = [];
         
            foreach ($articles as $articleData) {
                $insertData[] = [
                    'external_id' => md5($articleData['title'] . $articleData['author'] . $articleData['content']),
                    'title' => $articleData['title'],
                    'description' => $articleData['description'] ?? null,
                    'content' => $articleData['content'] ?? null,
                    'author' => $articleData['author'] ?? null,
                    'url' => $articleData['url'],
                    'category'=> $category,
                    'published_at' => $articleData['publishedAt'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            return $insertData;
        }
        return [];
    }


    public function fetchFromNewYorkTimesApi($category) {
        $apiUrl = env('NEW_YORK_TIMES_API_URL'); 
        $apiKey = env('NEW_YORK_TIMES_KEY');
        $tenDaysAgo = now()->subDays(10)->format('Ymd');  
        $today = now()->format('Ymd');
        $allArticles = [];  
    
        
        for ($page = 0; $page < 10; $page++) {
            $url = "{$apiUrl}?q={$category}&begin_date={$tenDaysAgo}&end_date={$today}&api-key={$apiKey}&page={$page}";
            $response = Http::get($url);
            usleep(50000); 
            if ($response->successful()) {
                $articles = $response->json()['response']['docs'];
                $insertData = [];
                
                foreach ($articles as $articleData) {
                    $insertData[] = [
                        'external_id' => md5($articleData['headline']['main'] . ($articleData['byline']['original'] ?? '') . $articleData['lead_paragraph']),
                        'title' => $articleData['headline']['main'],
                        'description' => $articleData['snippet'] ?? null,
                        'content' => $articleData['lead_paragraph'] ?? null,
                        'author' => $articleData['byline']['original'] ?? null,
                        'url' => $articleData['web_url'],
                        'category'=> $category,
                        'published_at' => $articleData['pub_date'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                $allArticles = array_merge($allArticles, $insertData);
            } else {
                break;
            }
        }
        return $allArticles;
    }


    public function fetchFromTheGuardianApi($category) {
        $newsApiUrl = env('THE_GUARDIAN_API_URL'); 
        $newsApiKey = env('THE_GUARDIAN_KEY'); 
        $tenDaysAgo = now()->subDays(10)->format('Y-m-d');
        $today = now()->format('Y-m-d');
        $url = "{$newsApiUrl}?q={$category}&from-date={$tenDaysAgo}&to-date={$today}&api-key={$newsApiKey}";
        $response = Http::get($url);

        if ($response->successful()) {
            $articles = $response->json()['response']['results'];
            $insertData = [];
            
            foreach ($articles as $articleData) {
                $insertData[] = [
                    'external_id' => md5($articleData['webTitle'] . ($articleData['fields']['byline'] ?? '') . ($articleData['fields']['bodyText'] ?? '')),
                    'title' => $articleData['webTitle'],
                    'description' => $articleData['fields']['trailText'] ?? null,
                    'content' => $articleData['fields']['bodyText'] ?? null,
                    'author' => $articleData['fields']['byline'] ?? null,
                    'url' => $articleData['webUrl'],
                    'category'=> $category,
                    'published_at' => $articleData['webPublicationDate'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            return $insertData;
        }
        return [];
    }
}
