<?php


namespace App\Controller\Game;


use App\Model\Hero;
use App\Utils\Output;

/**
 * @property Output output
 */
class Game extends GameProps
{
    private const MAX_ROUNDS_PER_MATCH = 20;
    private const ATTACK_MESSAGE = "%s attack %s";
    private const ROUND_MESSAGE = "Round %u";
    private const MAX_ROUNDS_MESSAGE = "No one wins and the maximum %s round per match has reached";
    private const WINNER_MESSAGE = "The winner is %s";

    public const DAMAGE_TAKEN_MESSAGE = "Damage taken by %s is %u";
    public const DAMAGE_MISSED_MESSAGE = "%s missed his damage of %u";
    public const RAPID_STRIKE_MESSAGE = "%s uses his rapid strike skill on his opponent";
    public const MAGIC_SHIELD_MESSAGE = "%s use his Magic Shield on this damage of %u";

    /**
     * @var bool
     */
    private $isFinished = false;
    /**
     * @var int
     */
    private $roundNumber = 1;
    /**
     * @var null
     */
    private $turn = null;

    /**
     * Board constructor.
     * @param Hero $super
     * @param Hero $regular
     */
    public function __construct(Hero $super, Hero $regular)
    {
        $this->super = $super;
        $this->regular = $regular;
        parent::__construct();
        $this->output = Output::getInstance();
    }

    public function start(): void
    {
        $this->output->writeCenter('GAME START');
        $this->setProps();
        $this->printHeroStats(true);

        while (!$this->isFinished) {
            $striker = $this->turn ?? $this->determineFirstAttack();
            $defender = $this->getDefender($striker);

            $this->updateProps($striker, $defender);

            $this->printRoundNumber()
//                ->printHeroStats()
                ->printAttack($striker->getName(), $defender->getName());

            $round = new Round($striker, $defender, $this->roundNumber, $this);
            $round->attack();

            $this->printHeroStats();

            $this->isFinished = $defender->getAbilities()->getHealth() <= 0;
            if (!$this->isFinished) {
                $this->turn = $defender;
                if ($this->roundNumber === self::MAX_ROUNDS_PER_MATCH) {
                    $this->printMaxRound();
                    break;
                }
            } else {
                $this->printWinnerMessage($striker);
            }

            $this->roundNumber++;
        }

        $this->output
            ->newLine()
            ->writeCenter("Game was ended");
    }

    /**
     * @param Hero $striker
     * @return Hero
     */
    private function getDefender(Hero $striker): Hero
    {
        return $striker === $this->super ? $this->regular : $this->super;
    }

    /**
     * @return Hero
     */
    private function determineFirstAttack(): Hero
    {
        $superSpeed = $this->super->getAbilities()->getSpeed();
        $regularSpeed = $this->regular->getAbilities()->getSpeed();
        $factor = 'Speed';
        if ($superSpeed === $regularSpeed) {
            $factor = 'Luck';
            $superLuck = $this->super->getAbilities()->getLuck();
            $regularLuck = $this->super->getAbilities()->getLuck();
        }

        $super = 'super' . $factor;
        $regular = 'regular' . $factor;
        $first = $$super > $$regular ? $this->super : $this->regular;

        $this->output
            ->newLine()
            ->writeCenter(
                sprintf(
                    "First attack was determined with %s %s",
                    $first->getName(),
                    $this->output->getColor(strtolower($factor), Output::WHITE)
                )
            )
            ->newLine();
        return $first;
    }

    /**
     * @param Hero $striker
     * @return $this
     */
    private function printWinnerMessage(Hero $striker): self
    {
        $this->output
            ->newLine(2)
            ->writeCenter(sprintf(self::WINNER_MESSAGE, $striker->getName()))
            ->newLine(2);

        return $this;
    }

    /**
     * @return $this
     */
    private function printMaxRound(): self
    {
        $this->output->writeCenter(sprintf(self::MAX_ROUNDS_MESSAGE, $this->roundNumber));

        return $this;
    }

    /**
     * @return $this
     */
    private function printRoundNumber(): self
    {
        $this->output
            ->writeEmpty()
            ->writeCenter(sprintf(self::ROUND_MESSAGE, $this->roundNumber))
            ->addSleep();

        return $this;
    }

    /**
     * @param string $strikerName
     * @param string $defenderName
     * @return Game
     */
    private function printAttack(string $strikerName, string $defenderName): self
    {
        $this->output
            ->newLine()
            ->writeCenter(sprintf(self::ATTACK_MESSAGE, $strikerName, $defenderName));

        return $this;
    }

    /**
     * @param bool $all
     * @return $this
     */
    private function printHeroStats(bool $all = false): self
    {
        $messages = array(
            'l' => $this->super->getName(),
            'r' => $this->regular->getName(),
        );
        $this->output->writeSideBySide($messages, true);

        $vitalAbilities = $this->super->getAbilities()->getVitalAbilities();
        if ($all) {
            $vitalAbilities[] = 'speed';
            $vitalAbilities[] = 'luck';
        }
        foreach ($vitalAbilities as $vitalAbility) {
            $messages = array(
                'l' => ucwords($vitalAbility) . ": " . $this->super->getAbilities()->getAbility($vitalAbility),
                'r' => ucwords($vitalAbility) . ": " . $this->regular->getAbilities()->getAbility($vitalAbility),
            );
            $this->output->writeSideBySide($messages);
        }

        return $this;
    }
}