<?php


namespace App;



class WhichBrowserAdapter implements ParseUaAdapterInterface {

    protected $data;



    public function __construct() {

        $this->data = new \WhichBrowser\Parser(getallheaders());

    }

    public function getBrowserType() {
        return $browser_name = $this->data->browser->name;
    }

    public function getEngine() {
        return $engine = $this->data->engine->toString();
    }

    public function getOs() {
        return $os = $this->data->os->name;
    }

    public function getDevice() {
        return $device = $this->data->device->type;
    }
}
