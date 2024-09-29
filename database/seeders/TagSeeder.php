<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tag;
use Database\Factories\TagFactory;

class TagSeeder extends Seeder
{
    public function run()
    {
        $tags = (new TagFactory())->predefinedTags;

        foreach ($tags as $tagName) {
            Tag::updateOrCreate(['name' => $tagName]);
        }
    }
}
