<?php


namespace App\Modules;


use App\Utils\Output;

class Abilities
{
    const VITAL_ABILITIES = array(
        "health",
        "strength",
        "defence"
    );

    /**
     * @var int
     */
    private $health;
    /**
     * @var int
     */
    private $strength;
    /**
     * @var int
     */
    private $defence;
    /**
     * @var int
     */
    private $speed;
    /**
     * @var int
     */
    private $luck;

    /**
     * Abilities constructor.
     * @param array $abilities
     */
    public function __construct(array $abilities)
    {
        foreach ($abilities as $ability => $values) {
            if (property_exists($this, $ability)) {
                $this->$ability = rand($values["min"], $values["max"]);
            } else {
                $output = Output::getInstance();
                $output->writeError("Abilities passed include extra data, please verify.");
            }
        }
    }

    /**
     * @return int
     */
    public function getHealth(): int
    {
        return $this->health;
    }

    /**
     * @param int $health
     */
    public function setHealth(int $health): void
    {
        $this->health = $health;
    }

    /**
     * @return int
     */
    public function getStrength(): int
    {
        return $this->strength;
    }

    /**
     * @param int $strength
     */
    public function setStrength(int $strength): void
    {
        $this->strength = $strength;
    }

    /**
     * @return int
     */
    public function getDefence(): int
    {
        return $this->defence;
    }

    /**
     * @param int $defence
     */
    public function setDefence(int $defence): void
    {
        $this->defence = $defence;
    }

    /**
     * @return int
     */
    public function getSpeed(): int
    {
        return $this->speed;
    }

    /**
     * @param int $speed
     */
    public function setSpeed(int $speed): void
    {
        $this->speed = $speed;
    }

    /**
     * @return int
     */
    public function getLuck(): int
    {
        return $this->luck;
    }

    public function getLuckPercentage()
    {
        return $this->luck / 100;
    }

    /**
     * @param int $luck
     */
    public function setLuck(int $luck): void
    {
        $this->luck = $luck;
    }

    /**
     * @param string $ability
     * @return mixed
     */
    public function getVitalAbility(string $ability)
    {
        if (property_exists($this, $ability)) {
            return $this->$ability;
        }
        echo "Vital ability " . $ability . " not found";
        exit;
    }

    /**
     * @return array
     */
    public function getVitalAbilities(): array
    {
        $abilities = array();
        foreach (self::VITAL_ABILITIES as $property) {
            if (property_exists($this, $property)) {
                array_push($abilities, $property);
            } else {
                echo "Vital abilities not found. Please check line" . __LINE__ . " on class" . __CLASS__;
                exit;
            }
        }
        return $abilities;
    }
}