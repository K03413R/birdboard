<?php


namespace App;


use Illuminate\Support\Arr;

trait RecordsActivity
{

    /*
    * Boot the trait
    */
    public static function bootRecordsActivity()
    {

        foreach (self::recordableEvents() as $event) {
            static::$event(function ($model) use ($event) {
                $model->recordActivity($model->activityDescription($event));
            });
        }

    }

    protected function activityDescription($description){

        return "{$description}_" . strtolower(class_basename($this)); //created_

    }

    /**
     * @return array
     */
    protected static function recordableEvents(): array
    {
        if (isset(static::$recordableEvents)) {
            return static::$recordableEvents;
        }
        return ['created', 'updated'];

    }


    /**
     * Record activity for a project.
     *
     * @param $description
     */
    public function recordActivity($description)
    {
        $project = ($this instanceof Project) ? $this : $this->project;
        $this->activity()->create([
            'description' => $description,
            'changes' => $this->activityChanges(),
            'project_id' => $project->id,
            'user_id' => $project->owner->id
        ]);
    }


    protected function activityChanges()
    {
        if ($this->wasChanged()) {

            return [
                'before' => arr::except(array_diff($this->getOriginal(), $this->getAttributes()), 'updated_at'),
                'after' => arr::except($this->getChanges(), 'updated_at')
            ];
        }
    }


    /**
     * The activity feed for the project.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function activity()
    {
        return $this->morphMany(Activity::class, 'subject')->latest();
    }


}
