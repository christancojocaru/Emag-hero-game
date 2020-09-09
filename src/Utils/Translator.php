<?php


namespace App\Utils;


class Translator
{
    /**
     * @var array
     */
    private $translations;

    public function __construct()
    {
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
     * @return mixed|void
     */
    public function trans(string $trans): string
    {
        if (array_key_exists($trans, $this->translations)) {
            return $this->translations[$trans];
        }

        Output::writeError(sprintf("Translation \"%s\" does not exist!", $trans));
    }
}