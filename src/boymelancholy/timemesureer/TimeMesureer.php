<?php

declare(strict_types=1);

namespace boymelancholy\timemesureer;

use pocketmine\Player;
use pocketmine\scheduler\ClosureTask;
use pocketmine\scheduler\TaskHandler;
use pocketmine\scheduler\TaskScheduler;

class TimeMesureer extends MesureerContainer {

    /** @var int */
    private $time;

    /** @var string */
    private $name;

    /** @var int */
    private $period;

    /** @var bool */
    private $delay;

    /** @var TaskHandler */
    private $handler;

    /** @var array */
    private $viewers;

    /** @var TaskScheduler */
    private $scheduler;

    /** @var bool */
    private $enabled;

    /**
     * TimeMesureer constructor.
     *
     * @param string $name Set any action name.
     * @param TaskScheduler $scheduler
     */
    public function __construct(string $name, TaskScheduler $scheduler) {
        $this->name = $name;
        $this->time = 0;
        $this->period = 20;
        $this->delay = false;
        $this->viewers = [];
        $this->scheduler = $scheduler;
    }

    /**
     * Run a TimeMesureer.
     */
    public function run() {
        $this->handler = $this->scheduler->scheduleRepeatingTask(new ClosureTask(
            function (int $currentTick): void {
                $ev = new MesureerProcessEvent($this);
                $ev->call();
                if ($this->delay) {
                    $this->setTime(--$this->time, false);
                    if ($this->time == -1) {
                        $this->handler->cancel();
                        $ev = new MesureerDeadEvent(
                            $this->name,
                            $this->time,
                            $this->viewers
                        );
                        $ev->call();
                        self::del($this->name);
                    }
                }
            }
        ), $this->period);

        self::set($this->name, $this);
        $this->enabled = true;
    }

    /**
     * Set current time
     *
     * @param int $time
     * @param bool $update
     * @return TimeMesureer
     */
    public function setTime(int $time, $update = true): self {
        $this->time = $time;
        if ($update) $this->update();
        return $this;
    }

    /**
     * Set tick period
     *
     * @param int $period
     * @return TimeMesureer
     */
    public function setPeriod(int $period): self {
        $this->period = $period;
        $this->update();
        return $this;
    }

    /**
     * Set this task is delay
     *
     * @param bool $delay
     * @return TimeMesureer
     */
    public function setDelay(bool $delay = false): self {
        $this->delay = $delay;
        $this->update();
        return $this;
    }

    /**
     * Set this task is delay
     *
     * @param Player[] $viewers
     * @return TimeMesureer
     */
    public function setViewers(array $viewers = []): self {
        $this->viewers = $viewers;
        $this->update();
        return $this;
    }

    /**
     * Get current time
     *
     * @return int
     */
    public function getTime(): int {
        return $this->time;
    }

    /**
     * Get action name
     *
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * Get players (or null)
     *
     * @return array
     */
    public function getViewers(): array {
        return $this->viewers;
    }

    /**
     * Update
     */
    private function update() {
        self::set($this->name, $this);
        if ($this->enabled) {
            $this->handler->cancel();
            $this->run();
        }
    }

    /**
     * Kill this handler and mesureer
     */
    public function kill() {
        $this->__destruct();
    }

    /**
     * Magic method, destruct
     */
    public function __destruct() {
        $this->handler->cancel();
        $ev = new MesureerDeadEvent(
            $this->name,
            $this->time,
            $this->viewers
        );
        $ev->call();

        self::del($this->name);
    }
}