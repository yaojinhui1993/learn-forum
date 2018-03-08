<?php

namespace App;

trait RecordsActivity
{
    protected static function bootRecordsActivity()
    {
        if (\Auth::guest()) {
            return;
        }
        static::getActivitiesToRecord()
            ->each(function ($event) {
                static::$event(function ($thread) use ($event) {
                    $thread->recordActivity($event);
                });
            });
    }

    protected static function getActivitiesToRecord()
    {
        return collect(['created']);
    }

    protected function recordActivity($event)
    {
        $this->activity()->create([
            'user_id' => auth()->id(),
            'type' => $this->getActivityType($event),
        ]);
    }

    protected function getActivityType($event)
    {
        $type = strtolower((new \ReflectionClass($this))->getShortName());
        return "{$event}_{$type}";
    }

    public function activity()
    {
        return $this->morphMany(Activity::class, 'subject');
    }
}
