<?php

namespace Tests\Feature\Controller\Receipts\Quotes;

use App\Receipts\Quote;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;
use Tests\Traits\BaseReceiptTests;

class QuoteControllerTest extends TestCase
{
    use BaseReceiptTests;

    protected $baseRouteName = 'receipt.quote';
    protected $baseRouteParameter = 'quote';
    protected $className = Quote::class;
}
