<?php

namespace Tests\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait RelationshipAssertions
{
    public function assertBelongsTo()
    {
        $this->assertEquals(HasMany::class, get_class($model->$relationship()));

        $this->assertCount(0, $model->fresh()->$relationship);

        $related->$relationship()
            ->associate($model->id)
            ->save();

        $this->assertCount(1, $model->fresh()->$relationship);
    }

    public function assertHasMany(Model $model, Model $related, string $relationship)
    {
        $this->assertEquals(HasMany::class, get_class($model->$relationship()));

        $this->assertCount(1, $model->fresh()->$relationship);
    }

    public function assertMorphMany()
    {

    }
}

?>