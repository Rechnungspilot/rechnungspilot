<?php

namespace Tests\Unit\Collections\CustomFields;

use App\Contacts\Contact;
use App\Models\CustomFields\CustomField;
use App\Models\CustomFields\CustomFieldValue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Tests\TestCase;

class CustomFieldValueCollectionTest extends TestCase
{
    protected $className = CustomFieldValue::class;

    protected function setUp() : void
    {
        parent::setUp();

        $this->customfields[] = factory(CustomField::class)->create([
            'company_id' => $this->user->company_id,
        ]);
        $this->customfields[] = factory(CustomField::class)->create([
            'company_id' => $this->user->company_id,
        ]);
        $this->customfields[] = factory(CustomField::class)->create([
            'company_id' => $this->user->company_id,
        ]);

        $this->customfieldvalues = factory($this->className, 3)->create([
            'company_id' => $this->user->company_id,
            'custom_field_id' => $this->customfields[0]->id,
        ]);
    }

    /**
     * @test
     */
    public function it_can_validate_itself()
    {
        $attributes = [];
        foreach ($this->customfieldvalues as $key => $value)
        {
            $attributes[$value->key] = 'Update ' . $key;
        }

        $this->customfieldvalues->validate(new Request($attributes));

        $this->markTestIncomplete('This test has not been implemented yet.');
    }

    /**
     * @test
     */
    public function it_can_update_each_item()
    {
        $attributes = [];
        foreach ($this->customfieldvalues as $key => $value)
        {
            $attributes[$value->key] = 'Update ' . $key;
        }

        $this->customfieldvalues->update(new Request($attributes));

        foreach ($this->customfieldvalues as $key => $value)
        {
            $this->assertDatabaseHas('custom_field_values', [
                'id' => $value->id,
                'value' => 'Update ' . $key,
            ]);
        }
    }
}
