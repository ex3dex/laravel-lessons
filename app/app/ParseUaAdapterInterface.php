<?php


namespace App;


interface ParseUaAdapterInterface {

    public function getBrowserType();

    public function getEngine();

    public function getOs();

    public function getDevice();

}
