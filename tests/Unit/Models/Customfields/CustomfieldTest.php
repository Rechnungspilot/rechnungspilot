<?php

namespace Tests\Unit\Models\Customfields;

use App\Contacts\Contact;
use App\Models\CustomFields\CustomField;
use App\Receipts\Invoice;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CustomfieldTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_get_the_for_from_a_model()
    {
        $models = [
            'rechnungen' => factory(Invoice::class)->make(),
            'kontakte' => factory(Contact::class)->make(),
        ];

        foreach ($models as $for => $model) {
            $this->assertEquals($for, CustomField::getForFromModel($model));
        }
    }

    /**
     * @test
     */
    public function it_can_get_all_default_models_for_a_type()
    {
        $for = 'kontakte';

        $this->signIn();

        $defaultCustomfield = factory(CustomField::class)->create([
            'company_id' => $this->user->company_id,
            'default' => true,
            'for' => $for,
        ]);

        $defaultCustomfieldDifferentCompany = factory(CustomField::class)->create([
            'default' => true,
            'for' => $for,
        ]);

        $customfield = factory(CustomField::class)->create([
            'company_id' => $this->user->company_id,
            'default' => false,
            'for' => $for,
        ]);

        $defaultCustomFields = CustomField::defaultsFor(factory(Contact::class)->make());

        $this->assertCount(1, $defaultCustomFields);
        $this->assertEquals($defaultCustomfield->id, $defaultCustomFields->first()->id);
    }
}
