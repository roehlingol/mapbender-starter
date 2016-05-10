<?php

$connectionDir = __DIR__ . "/config/connections";
$yaml          = new Symfony\Component\Yaml\Parser();

/** @var \Symfony\Component\DependencyInjection\Container $container */
$connections = $container->hasParameter("connections") ? $container->getParameter("connections") : array();
foreach (scandir($connectionDir) as $fileName) {
    $filePath = $connectionDir . '/' . $fileName;
    if (!is_file($filePath)) {
        continue;
    }

    $fileShortName                 = preg_replace('/\..*$/', '', $fileName);
    $connections[ $fileShortName ] = $yaml->parse(file_get_contents($filePath));
}

$container->setParameter("connections", $connections);

/**
 * @return mixed
 */
function importYyamlToArray()
{
    return json_decode(file_get_contents(__DIR__ . '/../composer.json'), true);
}

$container->setParameter('project', importYyamlToArray());