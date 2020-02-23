<?php

namespace Tests\Unit\Models\Contacts;

use App\Contacts\Contact;
use App\Contacts\Interaction;
use App\Contacts\InteractionType;
use App\Contacts\Person;
use App\Receipts\Invoice;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Traits\RelationshipAssertions;

class InteractionTest extends TestCase
{
    use RelationshipAssertions;

    /**
     * @test
     */
    public function it_has_many_interactions()
    {
        $model = factory(Interaction::class)->create();

        $related = factory(Interaction::class)->create([
            'company_id' => $model->company_id,
            'contact_id' => $model->contact_id,
            'interaction_id' => $model->id,
        ]);

        $this->assertHasMany($model, $related, 'interactions');
    }

    /**
     * @test
     */
    public function it_belongs_to_a_contact()
    {
        $interaction = factory(Interaction::class)->create();

        $this->assertEquals(Contact::class, get_class($interaction->contact));
    }

    /**
     * @test
     */
    public function it_belongs_to_an_interaction_type()
    {
        $interaction = factory(Interaction::class)->create();

        $this->assertEquals(InteractionType::class, get_class($interaction->type));
    }

    /**
     * @test
     */
    public function it_belongs_to_a_person()
    {
        $interaction = factory(Interaction::class)->create();

        $this->assertEquals(Person::class, get_class($interaction->person));
    }

    /**
     * @test
     */
    public function it_belongs_to_a_user()
    {
        $interaction = factory(Interaction::class)->create();

        $this->assertEquals(User::class, get_class($interaction->user));
    }

    /**
     * @test
     */
    public function it_morphs_to_a_model()
    {
        $interaction = factory(Interaction::class)->create();
        $receipt = factory(Invoice::class)->create([
            'company_id' => $interaction->company_id,
            'contact_id' => $interaction->contact_id,
        ]);

        $interaction->interactionable()
            ->associate($receipt)
            ->save();

        $this->assertEquals(Invoice::class, get_class($interaction->interactionable));
    }
}
