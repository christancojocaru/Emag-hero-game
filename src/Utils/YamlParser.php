<?php


namespace App\Utils;


use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

class YamlParser
{
    public static function parse(string $fileName): array
    {
        try {
            return Yaml::parseFile(ROOT . $fileName. ".yaml");
        } catch (ParseException $exception) {
            Output::writeError($exception->getMessage());
            exit;
        }
    }
}