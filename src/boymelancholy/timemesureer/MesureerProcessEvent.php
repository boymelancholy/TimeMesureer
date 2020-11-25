<?php

declare(strict_types=1);

namespace boymelancholy\timemesureer;

use pocketmine\event\Event;
use pocketmine\scheduler\TaskHandler;

class MesureerProcessEvent extends Event {

    /** @var string */
    private $name;

    /** @var int */
    private $time;

    /** @var TaskHandler */
    private $handler;

    /** @var array */
    private $viewers;

    /** @var TimeMesureer */
    private $timeMesureer;

    /**
     * MesureerProcessEvent constructor.
     *
     * @param TimeMesureer $timeMesureer
     */
    public function __construct(TimeMesureer $timeMesureer) {
        $this->name = $timeMesureer->getName();
        $this->time = $timeMesureer->getTime();
        $this->handler = $timeMesureer->getHandler();
        $this->viewers = $timeMesureer->getViewers();
        $this->timeMesureer = $timeMesureer;
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

    /**
     * Cancel task
     */
    public function kill(): void{
        $this->timeMesureer->kill();
    }
}