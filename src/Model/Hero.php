<?php


namespace App\Model;


use App\Modules\Abilities;
use App\Modules\Skills;
use App\Utils\Output;

/**
 * @property Output output
 */
class Hero
{
    CONST HERO_POWER = array(
        "regular" => 5,
        "super" => 10
    );
    /**
     * @var int
     */
    private $id;
    /**
     * @var string
     */
    private $name;
    /**
     * @var int
     */
    private $power;
    /**
     * @var Abilities
     */
    private $abilities;
    /**
     * @var Skills|null
     */
    private $skills = null;
    /**
     * @var string
     */
    private $color;

    /**
     * Hero constructor.
     */
    public function __construct()
    {
        $this->output = Output::getInstance();
        $this->id = uniqid("ID_");
    }

    /**
     * @return bool
     */
    public function isSuper(): bool
    {
        return $this->power == self::HERO_POWER['super'];
    }

    /**
     * @param array $abilities
     * @return Hero
     */
    public function setAbilities(array $abilities): self
    {
        $this->abilities = new Abilities($this->getPropertiesByPower($abilities));
        return $this;
    }

    /**
     * @param array $skills
     * @return Hero
     */
    public function setSkills(array $skills): self
    {
        if ($this->isSuper()) {
            $this->skills = new Skills($this->getPropertiesByPower($skills));
        }

        return $this;
    }

    /**
     * @param array $properties
     * @return array
     */
    private function getPropertiesByPower(array $properties): array
    {
        $powerCode = array_search($this->power, self::HERO_POWER);
        if ($powerCode === false) {
            $this->output->writeError(sprintf("Setting not found for hero with power of \"%s\"", $powerCode));
            exit;
        }
        return $properties[$powerCode];
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->output->getColor($this->name, $this->color);
    }

    /**
     * @param string $color
     * @return Hero
     */
    public function setColor(string $color): self
    {
        $this->color = $color;
        return $this;
    }

    /**
     * @param string $name
     * @return Hero
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return Abilities
     */
    public function getAbilities(): Abilities
    {
        return $this->abilities;
    }

    /**
     * @return Skills|null
     */
    public function getSkills(): ?Skills
    {
        return $this->skills;
    }

    /**
     * @param int $power
     * @return Hero
     */
    public function setPower(int $power): self
    {
        $this->power = $power;
        return $this;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }
}