# time-mesureer
Make useful scheduler to handle countdown.  
This is virion library.

# How to use
```php
use boymelancholy\timemesureer\TimeMesureer;
use boymelancholy\timemesureer\MesureerProcessEvent;
```

### Register and start
```php
# Register a wanna use tasks.

$time = 50; // Process time
$players = []; // Players who you wanna handle in event.
$mesureer = new TimeMesureer('AnyText', $time, $players);


# Start task.

$period = 1*20; // Same as parameter 2 of TaskScheduler::scheduleRepeatingTask()
$delay = false; // If you wanna cancel a task when time is 0, please set true.
/** @var PluginBase $this */
$mesureer->run($this, $period, $delay);
```

### Event
```php
# Processing per time.

class EventListener implements Listener {

    public function onMesureer(MesureerProcessEvent $event) {

        /** @var string $actionName */
        $actionName = $event->getActionName();
        if ($actionName === 'AnyText') {

            /** @var int $time */
            $time = $event->getCurrentTime();
 
            /** @var Player[] $viewers */
            $viewers = $event->getViewers();
            foreach ($viewers as $player) {
                $player->sendMessage('TIME: '.$time);
            }

            // If you set $delay as "false", you can use this method.
            if ($time == 0) {
                $event->kill(); // Cancel this task.
            }
        }
    }
}
```
