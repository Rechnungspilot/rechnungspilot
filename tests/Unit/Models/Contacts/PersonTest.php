<?php

namespace Tests\Unit\Models\Contacts;

use App\Contacts\Contact;
use App\Contacts\Person;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Unit\TestCase;

class PersonTest extends TestCase
{
    protected $class_name = Person::class;

    /**
     * @test
     */
    public function it_has_model_paths()
    {
        $contact = factory(Contact::class)->create();
        $model = factory($this->class_name)->create([
            'company_id' => $contact->company_id,
            'contact_id' => $contact->id,
        ]);

        $route_parameter = [
            'contact' => $model->contact_id,
            'person' => $model->id,
        ];

        $routes = [
            'index_path' => strtok(route($this->class_name::ROUTE_NAME . '.index', $route_parameter), '?'),
            'create_path' => strtok(route($this->class_name::ROUTE_NAME . '.create', $route_parameter), '?'),
            'path' => route($this->class_name::ROUTE_NAME . '.show', $route_parameter),
            'edit_path' => route($this->class_name::ROUTE_NAME . '.edit', $route_parameter),
        ];

        $this->testModelPaths($model, $routes, [
            'contact_id' => $model->contact_id,
        ]);
    }

    /**
     * @test
     */
    public function it_sets_the_default_invoice()
    {
        $this->markTestIncomplete('This test has not been implemented yet.');
    }

    /**
     * @test
     */
    public function it_sets_the_default_quote()
    {
        $this->markTestIncomplete('This test has not been implemented yet.');
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
        $this->markTestIncomplete('This test has not been implemented yet.');
    }
}
