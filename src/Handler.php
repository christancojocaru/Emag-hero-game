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
            ->writeCenter($this->output->getColor($this->translator->trans('hello'), Output::RED))
            ->writeEmpty();

        $controller = new GameController();
        $controller
            ->setOpponents()
            ->init();
    }

    public function end()
    {
        $this->output
            ->writeCenter("Bye Bye")
            ->newLine()
            ->outputAll();
    }
}