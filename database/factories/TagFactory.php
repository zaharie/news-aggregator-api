<?php

namespace Database\Factories;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;

class TagFactory extends Factory
{
    protected $model = Tag::class;
    public $predefinedTags = [
        'Adventure Sports', 'Arts & Leisure', 'Arts', 'Automobiles', 'Blogs', 'Books', 'Booming', 
        'Business Day', 'Business', 'Cars', 'Circuits', 'Classifieds', 'Connecticut', 
        'Crosswords & Games', 'Culture', 'DealBook', 'Dining', 'Editorial', 'Education', 'Energy', 
        'Entrepreneurs', 'Environment', 'Escapes', 'Fashion & Style', 'Fashion', 'Favorites', 
        'Financial', 'Flight', 'Food', 'Foreign', 'Generations', 'Giving', 'Global Home', 
        'Health & Fitness', 'Health', 'Home & Garden', 'Home', 'Jobs', 'Key', 'Letters', 
        'Long Island', 'Magazine', 'Market Place', 'Media', 'Men\'s Health', 'Metro', 
        'Metropolitan', 'Movies', 'Museums', 'National', 'Nesting', 'Obits', 'Obituaries', 
        'Obituary', 'OpEd', 'Opinion', 'Outlook', 'Personal Investing', 'Personal Tech', 'Play', 
        'Politics', 'Regionals', 'Retail', 'Retirement', 'Science', 'Small Business', 'Society', 
        'Sports', 'Style', 'Sunday Business', 'Sunday Review', 'Sunday Styles', 'T Magazine', 
        'T Style', 'Technology', 'Teens', 'Television', 'The Arts', 'The Business of Green', 
        'The City Desk', 'The City', 'The Marathon', 'The Millennium', 'The Natural World', 
        'The Upshot', 'The Weekend', 'The Year in Pictures', 'Theater', 'Then & Now', 
        'Thursday Styles', 'Times Topics', 'Travel', 'U.S.', 'Universal', 'Upshot', 'UrbanEye', 
        'Vacation', 'Washington', 'Wealth', 'Weather', 'Week in Review', 'Week', 'Weekend', 
        'Westchester', 'Wireless Living', 'Women\'s Health', 'Working', 'Workplace', 'World', 
        'Your Money'
    ];

    public function definition()
    {
        return [
            // Apenas retornando uma tag vazia para evitar problemas com o seeder.
            'name' => '',
        ];
    }
}
