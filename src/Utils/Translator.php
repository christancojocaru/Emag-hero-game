<?php


namespace App\Utils;


use Exception;

/**
 * @property Output output
 */
class Translator
{
    /**
     * @var array
     */
    private $translations;

    public function __construct()
    {
        $this->output = Output::getInstance();
        $this->setTranslations();
    }

    /**
     * @return void
     */
    private function setTranslations(): void
    {
        $this->translations = YamlParser::parse("/translations/index");
    }

    /**
     * @param string $trans
     * @return mixed
     */
    public function trans(string $trans): string
    {
        if (array_key_exists($trans, $this->translations)) {
            return $this->translations[$trans];
        }

        $this->output->writeError(sprintf("Translation \"%s\" does not exist!", $trans));
    }
}