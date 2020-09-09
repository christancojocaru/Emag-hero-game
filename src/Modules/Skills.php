<?php


namespace App\Modules;


use App\Utils\Output;

class Skills
{
    /**
     * @var int
     */
    private $rapidStrike;
    /**
     * @var int
     */
    private $magicShield;

    /**
     * Skills constructor.
     * @param array $skills
     */
    public function __construct(array $skills)
    {
        foreach ($skills as $skill => $value) {
            if (property_exists($this, $skill)) {
                $this->$skill = $value;
            } else {
                $output = Output::getInstance();
                $output->writeError("Skills settings include extra data, please verify.");
            }
        }
    }

    /**
     * @return int
     */
    public function getRapidStrike(): int
    {
        return $this->rapidStrike;
    }

    /**
     * @return int
     */
    public function getMagicShield(): int
    {
        return $this->magicShield;
    }
}