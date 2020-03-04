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

use App\ParseUaAdapterInterface;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Ramsey\Uuid\Uuid;
use UAParser\Parser;
use App\maxMindAdapter;

App::singleton(\App\AdapterInterface::class, function () {

    $reader = new \GeoIp2\Database\Reader(resource_path() . '/GeoLite2/GeoLite2-City.mmdb');

//    return new \App\maxMindAdapter($reader);
    return new \App\IpApiAdapter();
});

App::singleton(\App\ParseUaAdapterInterface::class, function () {

    return new \App\WhichBrowserAdapter();
});



Route::get('/', function () {
    return view('welcome');
});

Route::get('/r/{code}', function ($code, \App\AdapterInterface $adapter, \App\ParseUaAdapterInterface $browser ) {

    $link = \App\Link::where('short_code', $code)->get()->first();

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


    $adapter->parse(\request()->ip());

//    $browser = new WhichBrowser\Parser(getallheaders());
    $ua = request()->userAgent();
    $parser = Parser::create();
    $browser_name = $browser->getBrowserType();
    $engine = $browser->getEngine();
    $os = $browser->getOs();
    $device = $browser->getDevice();



    $statistic = new \App\Statistic();
    $statistic->id = \Ramsey\Uuid\Uuid::uuid4()->toString();
    $statistic->link_id = $link->id;
    $statistic->ip = !empty(env('DEFAULT_IP_ADDR')) ? env('DEFAULT_IP_ADDR') : '';
    $statistic->user_agent = !empty($ua) ? $ua : '';
    $statistic->browser = $browser_name;
    $statistic->engine = $engine;
    $statistic->os = $os;
    $statistic->device = $device;
    $statistic->city = $adapter->getCityName();
    $statistic->country = $adapter->getCountryCode();
    $statistic->save();


    if ($link !== null) {
//        return redirect($link);
    } else {
//        return redirect('/');
    }

});

