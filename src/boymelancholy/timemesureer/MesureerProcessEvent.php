<?php

declare(strict_types=1);

namespace boymelancholy\timemesureer;

class MesureerProcessEvent extends MesureerEvent {

    /** @var TimeMesureer */
    private $timeMesureer;

    /**
     * MesureerProcessEvent constructor.
     *
     * @param TimeMesureer $timeMesureer
     */
    public function __construct(TimeMesureer $timeMesureer) {
        parent::__construct(
            $timeMesureer->getName(),
            $timeMesureer->getTime(),
            $timeMesureer->getViewers()
        );
        $this->timeMesureer = $timeMesureer;
    }

    /**
     * Kill a task
     */
    public function kill(): void{
        $this->timeMesureer = null;
    }
}