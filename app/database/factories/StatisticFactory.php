<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Statistic;
use Faker\Generator as Faker;
use Illuminate\Support\Str;


$factory->define(Statistic::class, function (Faker $faker) {
    return [
        'id' => $faker->uuid,
        'link_id' => \App\Link::inRandomOrder()->limit(1)->get()->first()->id,
        'ip' => $faker->ipv6,
        'country' => $faker->country,
        'city' => $faker->city,
        'user_agent' => $faker->userAgent,
    ];
});
