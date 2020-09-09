<?php


namespace App\Utils;


class Output
{
    private const SEPARATOR = '=';
    private const SIDE_BY_SIDE_TAB_LENGTH = 8;
    public const RED = '0;31';
    public const CYAN = '1;36';
    public const WHITE = '1;37';
    private const SLEEP_KEY = "q%q%q%";
    private const SLEEP_TIME = array(
        "min"   => 1,
        "max"   => 3
    );
    /**
     * All the text to output
     * @var array
     */
    private $outputs = array();
    /**
     * @var self
     */
    public static $instance;
    /**
     * @var bool
     */
    private $isColored = false;
    /**
     * @var string
     */
    private $color;

    /**
     * @return static
     */
    public static function getInstance(): self
    {
        if (self::$instance) {
            return self::$instance;
        }
        return self::$instance = new self();
    }

    /**
     * @param string $message
     * @return Output
     */
    public function write(string $message): self
    {
        $this->outputs[] = $message;

        return $this;
    }

    /**
     * @param string $message
     */
    public static function writeError(string $message): void
    {
        echo "\n" . (new Output)->getColor($message, self::RED). "\n";
        exit;
    }

    /**
     * @param string $message
     * @return int
     */
    private function getLength(string $message): int
    {
        $message = preg_replace('/\\e\[0m|\\e\[[0-9];[0-9]*;40m/', '', $message);
        return strlen($message);
    }

    /**
     * @param int $no
     * @return $this
     */
    public function newLine(int $no = 1): self
    {
        $this->outputs[] = str_repeat("\n", $no);

        return $this;
    }

    /**
     * @param string $filler
     * @return $this
     */
    public function writeEmpty(string $filler = self::SEPARATOR): self
    {
        $this->outputs[] = str_repeat($filler, WINDOW_WIDTH) . "\n";

        return $this;
    }

    /**
     * @param string $message
     * @return Output
     */
    public function writeCenter(string $message): self
    {
        $len = $this->getLength($message);
        $text = '';
        if ($len < WINDOW_WIDTH) {
            /**
             * This is for space around output text
             * @var int $add
             */
            $add = 2;
            $multiplier = (WINDOW_WIDTH - ($len + $add)) / 2;
            $text .= str_repeat(self::SEPARATOR, floor($multiplier));
            $text .= ' ' . $message . ' ';
            $text .= str_repeat(self::SEPARATOR, ceil($multiplier));
        } else {
            $text .= $message;
        }

        $this->outputs[] = $text . "\n";

        return $this;
    }

    /**
     * @TODO not accurate
     * @param array $data
     * @param bool $centered
     * @return Output
     */
    public function writeSideBySide(array $data, bool $centered = false): self
    {
        $text = "";
        foreach ($data as $position => $message) {
            $subText = '';
            $len = $this->getLength($message);
            if ($centered) {
                $margins = WINDOW_WIDTH / 2 - $len;
                $subText .= str_repeat(" ", floor($margins / 2));
                $subText .= $message;
                $subText .= str_repeat(" ", ceil($margins / 2));
            } else {
                $subText .= str_repeat(" ", self::SIDE_BY_SIDE_TAB_LENGTH);
                $subText .= $message;
                $subText .= str_repeat(" " ,floor(WINDOW_WIDTH / 2 - self::SIDE_BY_SIDE_TAB_LENGTH - $len));
            }
            if ($position === 'l') {
                $text = sprintf('%1$s%2$s', $subText, $text);
            } else {
                $text = sprintf('%1$s-%2$s', $text, $subText);
            }
        }

        $this->outputs[] = $text . "\n";

        return $this;
    }

    /**
     * @return $this
     */
    public function addSleep(): self
    {
        $this->outputs[] = self::SLEEP_KEY;
        return $this;
    }

    /**
     * @param string $message
     * @param $color
     * @return string
     */
    public function getColor(string $message, string $color): string
    {
        return "\e[" . $color . ";40m" . $message . "\e[0m";
    }

    public function outputAll()
    {
        foreach ($this->outputs as $output) {
            if ($output === self::SLEEP_KEY) {
                sleep(rand(self::SLEEP_TIME['min'], self::SLEEP_TIME['max']));
            } else {
                echo $output;
            }
        }
    }
}