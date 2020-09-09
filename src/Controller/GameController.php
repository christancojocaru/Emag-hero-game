<?php

namespace App\Controller;


use App\Controller\Game\Game;
use App\Model\Hero;
use App\Utils\Output;
use App\Utils\YamlParser;

/**
 * @property Output output
 * @property Hero super
 * @property Hero regular
 */
class GameController
{
    private CONST GRAND_HERO_NAME = 'Orderus';

    public function __construct()
    {
        $this->output = Output::getInstance();
        $this->setOpponents()
            ->init();
    }

    private function init()
    {
        $board = new Game($this->super, $this->regular);
        $board->start();
    }

    /**
     * @return $this
     */
    private function setOpponents(): self
    {
        $heroSetting = $this->getHeroSetting();

        $this->super = new Hero();
        $this->super
            ->setPower(HERO::HERO_POWER['super'])
            ->setName(self::GRAND_HERO_NAME)
            ->setColor(Output::RED)
            ->setAbilities($heroSetting['abilities'])
            ->setSkills($heroSetting['skills']);

        $this->regular = new Hero();
        $this->regular
            ->setPower(HERO::HERO_POWER['regular'])
            ->setName($this->getRandomHeroName())
            ->setColor(Output::CYAN)
            ->setAbilities($heroSetting['abilities'])
            ->setSkills($heroSetting['skills']);

        return $this;
    }

    /**
     * @return array
     */
    private function getHeroSetting(): array
    {
        return YamlParser::parse("/config/heroSetting");
    }

    /**
     * @return string
     */
    private function getRandomHeroName(): string
    {
        $data = YamlParser::parse("/config/heroNames");
        $random = rand(1, count($data));
        return $data[$random]['name'];
    }
}