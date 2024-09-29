<?php

namespace App\Console\Commands;
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\FetchArticlesJob;

class DispatchFetchArticles extends Command
{
    protected $signature = 'dispatch:fetch-articles';
    protected $description = 'Dispatch the FetchArticles job';

    public function handle()
    {
        FetchArticlesJob::dispatch();
        $this->info('FetchArticles job dispatched successfully.');
    }
}
