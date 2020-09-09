<?php


namespace App;


use App\Controller\GameController;
use App\Utils\Output;
use App\Utils\Translator;

/**
 * @property Output output
 */
class Handler
{
    /**
     * @var Translator
     */
    private $translator;

    public function __construct()
    {
        $this->output = Output::getInstance();
        $this->translator = new Translator();
    }

    public function start()
    {
        $this->output
            ->newLine()
            ->writeEmpty()
            ->addColor(Output::RED)
            ->writeCenter($this->translator->trans('hello'))
            ->writeEmpty();

        new GameController();
    }

    public function end()
    {
        $this->output
            ->writeCenter("Bye Bye")
            ->newLine()
            ->outputAll();
    }
}