<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
require_once '../vendor/autoload.php';
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Ramsey\Uuid\Uuid;
use UAParser\Parser;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/r/{code}', function ($code) {

    $link = \App\Link::where('short_code', $code)->get()->first();

    $reader = new \GeoIp2\Database\Reader(resource_path() . '/GeoLite2/GeoLite2-City.mmdb');

//    $city = null;
//    $country = null;
//
//    $result = file_get_contents('http://ip-api.com/json' . env('DEFAULT_IP_ADDR'));
//
//    $data = json_decode($result, true);
//
//    if ($data['status'] == 'fail') {
//        $result = file_get_contents('http://ip-api.com/json' . \request()->ip());
//        $data = json_decode($data, true);
//
//        $city = $data['city'];
//        $country = $data['countryCode'];
//    }


    try {
        $record = $reader->city(request()->ip());
    } catch (\GeoIp2\Exception\AddressNotFoundException $exception) {
        $record = $reader->city(env('DEFAULT_IP_ADDR'));
    }

    $browser = new WhichBrowser\Parser(getallheaders());
    $ua = request()->userAgent();
    $parser = Parser::create();
    $browser_name = $browser->browser->name;
    $engine = $browser->engine->toString();
    $os = $browser->os->name;
    $device = $browser->device->type;

    $statistic = new \App\Statistic();
    $statistic->id = \Ramsey\Uuid\Uuid::uuid4()->toString();
    $statistic->link_id = $link->id;
    $statistic->ip = !empty(env('DEFAULT_IP_ADDR')) ? env('DEFAULT_IP_ADDR') : '';
    $statistic->user_agent = !empty($ua) ? $ua : '';
    $statistic->browser = $browser_name;
    $statistic->engine = $engine;
    $statistic->os = $os;
    $statistic->device = $device;
    $statistic->city = $record->city->name ? $record->city->name : 'Odesa';
    $statistic->country = $record->country->isoCode ? $record->country->isoCode : 'Ukraine';
    $statistic->save();


    if ($link !== null) {
//        return redirect($link);
    } else {
//        return redirect('/');
    }

});

