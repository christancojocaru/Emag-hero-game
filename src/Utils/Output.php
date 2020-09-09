<?php


namespace App\Utils;


class Output
{
    private const SEPARATOR = '=';
    private const SIDE_BY_SIDE_TAB_LENGTH = 8;
    public const RED = '0;31';
    public const CYAN = '1;36';
    public const WHITE = '1;37';
    /**
     * All the text to output
     * @var string
     */
    private $output = '';
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
        $this->output .= $message;

        return $this->checkColored();
    }

    /**
     * @param string $message
     */
    public function writeError(string $message): void
    {
        echo "\n" . $this->getColor($message, self::RED). "\n";
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
        $this->output .= str_repeat("\n", $no);
        return $this->checkColored();
    }

    /**
     * @param string $filler
     * @return $this
     */
    public function writeEmpty(string $filler = self::SEPARATOR): self
    {
        $this->output .= str_repeat($filler, WINDOW_WIDTH);
        $this->output .= "\n";

        return $this->checkColored();
    }

    /**
     * @param string $message
     * @return Output
     */
    public function writeCenter(string $message): self
    {
        $len = $this->getLength($message);
        if ($len < WINDOW_WIDTH) {
            /**
             * This is for space around output text
             * @var int $add
             */
            $add = 2;
            $multiplier = (WINDOW_WIDTH - ($len + $add)) / 2;
            if ($this->isColored) {
                $this->output .= "\e[0m";
                $this->output .= str_repeat(self::SEPARATOR, floor($multiplier));
                $this->addColor($this->color);
                $this->output .= ' ' . $message . ' ';
                $this->output .= "\e[0m";
                $this->output .= str_repeat(self::SEPARATOR, ceil($multiplier));
            } else {
                $this->output .= str_repeat(self::SEPARATOR, floor($multiplier));
                $this->output .= ' ' . $message . ' ';
                $this->output .= str_repeat(self::SEPARATOR, ceil($multiplier));
            }
        } else {
            $this->output .= $message;
        }

//        $this->newLine();
        return $this->checkColored();
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

        $this->output .= $text . "\n";

        return $this->checkColored();
    }

    private function checkColored(): self
    {
        if ($this->isColored) {
            $this->output .= "\e[0m";
            $this->isColored = false;
        }

        return $this;
    }

    /**
     * @param string $color
     * @return $this
     */
    public function addColor(string $color): self
    {
        $this->isColored = true;
        $this->color = $color;
        $this->output .= "\e[" . $color . ";40m";
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
        echo $this->output;
    }
}