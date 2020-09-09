<?php


namespace App\Controller\Game;


use App\Model\Hero;
use App\Utils\Calculator;
use App\Utils\Output;

/**
 * @property Output output
 */
class Round
{
    private const LUCKY_NUMBER =  7;
    /**
     * @var Hero
     */
    private $striker;
    /**
     * @var Hero
     */
    private $defender;
    /**
     * @var int
     */
    private $roundNumber;
    /**
     * @var Game
     */
    private $parent;

    public function __construct(Hero $striker, Hero $defender, int $roundNumber, Game $parent)
    {
        $this->striker = $striker;
        $this->defender = $defender;
        $this->output = Output::getInstance();
        $this->roundNumber = $roundNumber;
        $this->parent = $parent;
    }

    public function attack()
    {
        $this->output->newLine();
        $damage = $this->calculateDamage();
        if ($this->isRapidStrike()) {
            $damage = $this->getRapidStrikeDamage($damage);
            $this->output->writeCenter(sprintf(GAME::RAPID_STRIKE_MESSAGE, $this->striker->getName()));
        }

        $damage = $this->getDamage($damage);
        if ($invalidDamage = $this->isDamageMissed()) {
            $this->output->writeCenter(sprintf(GAME::DAMAGE_MISSED_MESSAGE, $this->striker->getName(), $damage));
        }
        if (!$invalidDamage && $invalidDamage = $this->isMagicShieldUsed()) {
            $this->output->writeCenter(sprintf(GAME::MAGIC_SHIELD_MESSAGE, $this->defender->getName(), $damage));
        }

        if (!$invalidDamage) {
            $this->subtractDamage($damage);
            $this->output->writeCenter(sprintf(GAME::DAMAGE_TAKEN_MESSAGE, $this->defender->getName(), $damage));
        }
        $this->output->newLine();
    }

    /**
     * @param int $damage
     * @return int
     */
    private function getRapidStrikeDamage(int $damage): int
    {
        return $damage * 2;
    }

    /**
     * @param int $damage
     */
    private function subtractDamage(int $damage): void
    {
        $health = $this->defender->getAbilities()->getHealth();
        $this->defender->getAbilities()->setHealth($health - $damage);
    }

    private function calculateDamage(): int
    {
        return $this->striker->getAbilities()->getStrength() - $this->defender->getAbilities()->getDefence();
    }

    /**
     * @param int $damage
     * @return int
     */
    private function getDamage(int $damage): int
    {
        $defenderHealth = $this->defender->getAbilities()->getHealth();
        return $damage < $defenderHealth ? $damage : $defenderHealth;
    }

    /**
     * @return bool
     */
    private function isRapidStrike(): bool
    {
        if ($this->striker->getSkills() === null) {
            return false;
        }

        $rapidStrike = $this->striker->getSkills()->getRapidStrike();
        return rand(1, $rapidStrike) === self::LUCKY_NUMBER;
    }

    /**
     * @return bool
     */
    private function isDamageMissed(): bool
    {
        $luckPercentage = $this->defender->getAbilities()->getLuckPercentage();
        if ($luckPercentage === 0) {
            return false;
        }
        if ($luckPercentage == 1) {
            return true;
        }
        $luckyNumber = rand(1, round(1 / $luckPercentage));

        return $luckyNumber === 1;
    }

    private function isMagicShieldUsed(): bool
    {
        if ($this->defender->getSkills() === null) {
            return false;
        }

        $magicShield = $this->defender->getSkills()->getMagicShield();
        return rand(1, $magicShield) === self::LUCKY_NUMBER;
    }
}