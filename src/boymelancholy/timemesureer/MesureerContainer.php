<?php

declare(strict_types=1);

namespace boymelancholy\timemesureer;

class MesureerContainer {

    /** @var TimeMesureer[]  */
    protected static $container = [];

    /**
     * Set TimeMesureer
     *
     * @param string $name
     * @param TimeMesureer $tm
     */
    protected function set(string $name, TimeMesureer $tm) {
        if (isset(self::$container[$name])) {
            unset(self::$container[$name]);
        }
        self::$container[$name] = $tm;
    }

    /**
     * Get TimeMesureer
     *
     * @param string $name
     * @return TimeMesureer|null
     */
    public static function get(string $name): ?TimeMesureer {
        return self::$container[$name] ?? null;
    }

    /**
     * Delete TimeMesureer
     *
     * @param string $name
     */
    protected function del(string $name): void {
        unset(self::$container[$name]);
    }
}