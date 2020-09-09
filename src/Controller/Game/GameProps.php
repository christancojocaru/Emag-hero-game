<?php


namespace App\Controller\Game;


use App\Model\Hero;

class GameProps
{
    /**
     * @var Hero
     */
    protected $super;
    /**
     * @var Hero
     */
    protected $regular;
    /**
     * @var array $missCounter
     */
    private $missCounter = array();
    /**
     * @var array
     */
    private $strikesCounter = array();
    /**
     * @var array
     */
    private $defenceCounter = array();
    /**
     * @var array
     */
    private $strikesUntilWin = array();

    /**
     * Props constructor.
     */
    public function __construct()
    {
        $this->init();
    }

    private function init()
    {
        $this->missCounter[$this->super->getId()] = 0;
        $this->missCounter[$this->regular->getId()] = 0;

        $this->strikesCounter[$this->super->getId()] = 0;
        $this->strikesCounter[$this->regular->getId()] = 0;

        $this->defenceCounter[$this->super->getId()] = 0;
        $this->defenceCounter[$this->regular->getId()] = 0;

        $this->strikesUntilWin[$this->super->getId()] = 0;
        $this->strikesUntilWin[$this->regular->getId()] = 0;
    }

    /**
     * @param Hero $hero
     */
    public function setMiss(Hero $hero)
    {
        $this->missCounter[$hero->getId()]++;
    }

    /**
     * @param Hero $hero
     */
    private function increaseAttacks(Hero $hero)
    {
        $this->strikesCounter[$hero->getId()]++;
    }

    /**
     * @param Hero $hero
     */
    private function increaseDefences(Hero $hero)
    {
        $this->defenceCounter[$hero->getId()]++;
    }

    protected function setProps(): void
    {
        $this->strikesUntilWin[$this->super->getId()] = $this->strikesCountUntilWin($this->super, $this->regular);
        $this->strikesUntilWin[$this->regular->getId()] = $this->strikesCountUntilWin($this->regular, $this->super);
    }

    /**
     * @param Hero $striker
     * @param Hero $defender
     * @return int
     */
    private function strikesCountUntilWin(Hero $striker, Hero $defender): int
    {
        $damage = round($striker->getAbilities()->getStrength() - $defender->getAbilities()->getDefence());

        return ceil($defender->getAbilities()->getHealth() / $damage);
    }

    /**
     * @param Hero $striker
     * @param Hero $defender
     */
    protected function updateProps(Hero $striker, Hero $defender): void
    {
        $this->increaseAttacks($striker);
        $this->increaseDefences($defender);
    }

    /**
     * @param Hero $hero
     * @return int
     */
    public function getMissCounter(Hero $hero): int
    {
        return $this->missCounter[$hero->getId()];
    }

    /**
     * @param Hero $hero
     * @return int
     */
    public function getStrikes(Hero $hero): int
    {
        return $this->strikesCounter[$hero->getId()];
    }

    /**
     * @param Hero $hero
     * @return int
     */
    public function getDefences(Hero $hero): int
    {
        return $this->defenceCounter[$hero->getId()];
    }
}