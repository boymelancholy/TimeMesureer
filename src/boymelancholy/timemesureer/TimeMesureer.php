<?php

declare(strict_types=1);

namespace boymelancholy\timemesureer;

use pocketmine\scheduler\ClosureTask;
use pocketmine\scheduler\TaskHandler;
use pocketmine\plugin\PluginBase;

class TimeMesureer {

    /** @var int */
    private $time;

    /** @var string */
    private $name;

    /** @var TaskHandler */
    private $handler;

    /** @var array */
    private $viewers = [];

    /**
     * TimeMesureer constructor.
     *
     * @param string $name Set any action name.
     * @param int $initTime Set process time.
     * @param array|null $viewers Set players who can get time.
     */
    public function __construct(string $name, int $initTime, array $viewers = null) {
        $this->name = $name;
        $this->time = $initTime;
        if ($viewers !== null) {
            $this->viewers = $viewers;
        }
    }

    /**
     * Run a TimeMesureer.
     *
     * @param PluginBase $plugin
     * @param int $period Sets ticks for when to process. (default is 1*20)
     * @param bool $delay If you wanna stop the task in time is 0, please set this true.
     */
    public function run(PluginBase $plugin, int $period = 20, bool $delay = false) {
        $this->handler = $plugin->getScheduler()->scheduleRepeatingTask(new ClosureTask(
            function (int $currentTick) use ($delay): void {
                $this->setTime(--$this->time);
                $ev = new MesureerProcessEvent($this);
                $ev->call();
                if ($delay) {
                    if ($this->time == 0) {
                        $this->kill();
                    }
                }
            }
        ), $period);
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
     * Set current time
     *
     * @param int $val
     */
    public function setTime(int $val) {
        $this->time = $val;
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
     * Get TaskHandler
     *
     * @return TaskHandler
     */
    public function getHandler(): TaskHandler {
        return $this->handler;
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
     * Kill process of TimeMesureer
     */
    public function kill() {
        $this->handler->cancel();
    }
}