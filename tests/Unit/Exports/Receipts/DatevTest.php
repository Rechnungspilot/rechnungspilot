<?php

namespace Tests\Unit\Exports\Receipts;

use App\Exports\Receipts\Datev;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DatevTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_export_invoices()
    {
        $receipts = new Collection();
        $company = Company::make();
        $path = Datev::invoices($company, $receipts);
        $csv = Storage::disk('public')->get($path);

        dump($csv);
    }
}