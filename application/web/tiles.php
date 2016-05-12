<?php

//$config = array(
//   ''
//)

include("../vendor/klokantech/tileserver/tileserver.php");



global $config;
$config['serverTitle'] = 'TileServer-php v2';
$config['availableFormats'] = array('png', 'jpg', 'jpeg', 'gif', 'webp', 'pbf', 'hybrid');
//$config['template'] = 'template.php';
//$config['baseUrls'] = array('t0.server.com', 't1.server.com');
//$_SERVER['ORIG_PATH_INFO']


$GLOBALS['config'] = array(

);

Router::serve(array(
    '/'                                                 => 'Server:getHtml',
    '/maps'                                             => 'Server:getInfo',
    '/html'                                             => 'Server:getHtml',
    '/:alpha/:number/:number/:number.grid.json'         => 'Json:getUTFGrid',
    '/:alpha.json'                                      => 'Json:getJson',
    '/:alpha.jsonp'                                     => 'Json:getJsonp',
    '/wmts'                                             => 'Wmts:get',
    '/wmts/1.0.0/WMTSCapabilities.xml'                  => 'Wmts:get',
    '/wmts/:alpha/:number/:number/:alpha'               => 'Wmts:getTile',
    '/wmts/:alpha/:alpha/:number/:number/:alpha'        => 'Wmts:getTile',
    '/wmts/:alpha/:alpha/:alpha/:number/:number/:alpha' => 'Wmts:getTile',
    '/:alpha/:number/:number/:alpha'                    => 'Wmts:getTile',
    '/tms'                                              => 'Tms:getCapabilities',
    '/tms/:alpha'                                       => 'Tms:getLayerCapabilities',
));

