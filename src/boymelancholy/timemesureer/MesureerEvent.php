<?php

declare(strict_types=1);

namespace boymelancholy\timemesureer;


use pocketmine\event\Event;

class MesureerEvent extends Event {

    /** @var string */
    private $name;

    /** @var int */
    private $time;

    /** @var array */
    private $viewers;

    public function __construct($name, $time, $viewers) {
        $this->name = $name;
        $this->time = $time;
        $this->viewers = $viewers;
    }

    /**
     * Get handle-task name (action name)
     *
     * @return string
     */
    public function getActionName(): string {
        return $this->name;
    }

    /**
     * Get current task time
     *
     * @return int
     */
    public function getCurrentTime(): int {
        return $this->time;
    }

    /**
     * Get watching players
     *
     * @return array
     */
    public function getViewers(): array {
        return $this->viewers;
    }
}