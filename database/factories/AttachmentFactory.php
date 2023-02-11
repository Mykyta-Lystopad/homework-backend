<?php

/** @var Factory $factory */

use App\Models\Attachment;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Attachment::class, function (Faker $faker) {
    return [
        'comment' => $faker->word,
        'user_id' => 1
    ];
});

$factory->state(Attachment::class, 'avatar', function (Faker $faker) {

    return [
        'file_name' => generateImage($faker, true)
    ];
});

$factory->state(Attachment::class, 'image', function (Faker $faker) {
    $imagePath = '';
    return [
        'file_name' => generateImage($faker, false)
    ];
});

function generateImage(Faker $faker, $avatar = false)
{
    static $attachmentId = 1;
    $imagePath = sprintf(Attachment::FILE_PATH_FORMAT, $attachmentId++);
    Storage::makeDirectory($imagePath);
    $imageFullPath = Storage::path($imagePath);
    $fileName = $faker->image($imageFullPath, $avatar ? 100 : 1280, $avatar ? 100 : 1000, null, false);
    if (!$avatar) {
        Image::make(Storage::path($imagePath . $fileName))
            ->fit(200, 300)
            ->save(Storage::path($imagePath . 'thumb_' . $fileName));
    }
    return $fileName;
}
