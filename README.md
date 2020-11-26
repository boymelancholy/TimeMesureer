# time-mesureer
Make useful handle scheduler as countdown.
This is virion framework.

# How to use
### Register and start
```php
use boymelancholy\timemesureer\TimeMesureer;

$time = 50;
$viewers = [];
$period = 1 * 20;
$delay = false;
$scheduler = /* TaskScheduler */;

$mesureer = new TimeMesureer("AnyText", $scheduler);
$mesureer->setTime($time); // Process time
$mesureer->setViewers($viewers); // Players who you wanna handle in event.
$mesureer->setDelay($delay); // Use as DelayTask? true or false
$mesureer->setPeriod($period); // Second args of TaskScheduler::scheduleRepeatingTask()
$mesureer->run();

/*
 * Can do it:
 *  $mesureer = new TimeMesureer("AnyText", $scheduler);
 *  $mesureer->setTime()->setViewers()->setDelay()->setPeriod()->run();
 *                  ^            ^           ^           ^
 *  default is...   0            []        false        1*20
 */
```

### Get some TimeMesureer
```php
use boymelancholy\timemesureer\MesureerContainer;

$tm = MesureerContainer::get("AnyText");
```

### Kill some TimeMesureer
```php
use boymelancholy\timemesureer\MesureerContainer;

MesureerContainer::get("AnyText")->kill();
```

### e.g. "Show a countdown"
```php
use boymelancholy\timemesureer\MesureerProcessEvent;

class EventListener implements Listener {

    public function onMesureer(MesureerProcessEvent $event) {
        $actionName = $event->getActionName();
        if ($actionName === "AnyText") {
            $time = $event->getCurrentTime();
            $viewers = $event->getViewers();
            foreach ($viewers as $player) {
                $player->sendMessage("TIME: ".$time);
            }
        }
    }
}
```

### e.g. "In elapsing 25 second, time decreasing accerate"
```php
use boymelancholy\timemesureer\MesureerProcessEvent;
use boymelancholy\timemesureer\MesureerContainer;

class EventListener implements Listener {

    public function onMesureer(MesureerProcessEvent $event) {
        $actionName = $event->getActionName();
        if ($actionName === "AnyText") {
            $time = $event->getCurrentTime();
            if ($time == 25) {
                $tm = MesureerContainer::get($actionName);
                $tm->setPeriod(10);
            }
        }
    }
}
```

### e.g. "When a player will sneak, time set again to 50"
```php
use pocketmine\event\player\PlayerSneakingEvent;

class EventListener implements Listener {

    public function onSneaking(PlayerSneakingEvent $event) {
        $tm = MesureerContainer::get("AnyText");
        $tm->setTime(50);
    }
}
```

### e.g. "When task interrupt, show the remain time"
```php
use boymelancholy\timemesureer\MesureerDeadEvent;

class EventListener implements Listener {

    public function onMesureerDead(MesureerDeadEvent $event) {
        $actionName = $event->getActionName();
        $endTime = $event->getCurrentTime();
        if ($actionName === "AnyText") {
            $viewers = $event->getViewers();
            foreach ($viewers as $player) {
                $player->sendMessage("Remained time is ".$time);
            }
        }
    }
}
```

# Scheduled to be updated
- [x] Add TimeMesureer container. (will be able to access in static)  
- [x] Add task mode. (RepeatingTask or DelayedTask)  
- [x] Fix exceeding the minimum value. (in setting $initTime to 0, this time is to be -1 * PHP_INT_MAX)  