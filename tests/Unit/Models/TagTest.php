<?php

namespace Tests\Unit\Models;

use App\Contacts\Contact;
use App\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TagTest extends TestCase
{
    /**
     * @test
     */
    public function it_has_many_taggables()
    {
        $this->markTestIncomplete();

        $tag = factory(Tag::class)->create();
        $contact = factory(Contact::class)->create([
            'company_id' => $tag->company_id,
        ]);

        $tag->taggables()
            ->attach($contact)
            ->save();

        dd($tag->fresh()->taggables);
    }
}
