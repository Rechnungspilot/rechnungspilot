<?php

namespace Tests\Unit\Models\Contacts;

use App\Contacts\Contact;
use App\Contacts\Person;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PersonTest extends TestCase
{
    /**
     * @test
     */
    public function it_sets_the_default_invoice()
    {

    }

    /**
     * @test
     */
    public function it_sets_the_default_quote()
    {

    }

    /**
     * @test
     */
    public function it_belongs_to_a_contact()
    {
        $contact = factory(Contact::class)->create();
        $person = factory(Person::class)->create([
            'company_id' => $contact->company_id,
            'contact_id' => $contact->id,
        ]);

        $this->assertEquals($contact->fresh(), $person->contact);
    }

    /**
     * @test
     */
    public function it_gets_its_name()
    {
        $person = factory(Person::class)->make();

        $this->assertEquals($person->lastname . ', ' . $person->firstname, $person->name);
    }

    /**
     * @test
     */
    public function it_knows_if_it_is_deletable()
    {

    }
}
