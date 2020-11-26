<?php

declare(strict_types=1);

namespace boymelancholy\timemesureer;


class MesureerDeadEvent extends MesureerEvent {

    /**
     * MesureerDeadEvent constructor.
     *
     * @param string $name
     * @param int $time
     * @param array $viewers
     */
    public function __construct(string $name, int $time, array $viewers) {
        parent::__construct($name, $time + 1, $viewers);
    }
}