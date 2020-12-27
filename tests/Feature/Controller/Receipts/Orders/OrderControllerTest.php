<?php

namespace Tests\Feature\Controller\Receipts\Orders;

use App\Receipts\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;
use Tests\Traits\BaseReceiptTests;

class OrderControllerTest extends TestCase
{
    use BaseReceiptTests;

    protected $baseRouteName = 'receipt.order';
    protected $baseRouteParameter = 'order';
    protected $className = Order::class;

    protected function assertEditViewHas(TestResponse $response) : void
    {
        //
    }
}
