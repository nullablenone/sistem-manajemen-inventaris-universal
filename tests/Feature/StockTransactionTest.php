<?php

namespace Tests\Feature;

use App\Http\Controllers\StockTransactionController;
use App\Models\StockTransaction;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use ReflectionMethod;
use Tests\TestCase;

class StockTransactionTest extends TestCase
{
    use DatabaseTransactions;

    private function callGenerateTransactionNumber($type, $dateString)
    {
        $controller = resolve(StockTransactionController::class);
        $method = new ReflectionMethod(StockTransactionController::class, 'generateTransactionNumber');
        $method->setAccessible(true);
        return $method->invoke($controller, $type, $dateString);
    }

    public function test_transaction_number_generation_normal()
    {
        $user = User::factory()->create();

        // 1. Initial transaction for a specific date
        StockTransaction::create([
            'transaction_number' => 'TX-IN-20260703-0001',
            'type' => 'inbound',
            'date' => '2026-07-03',
            'created_by' => $user->id,
        ]);

        // Next number should be TX-IN-20260703-0002
        $nextNumber = $this->callGenerateTransactionNumber('inbound', '2026-07-03');
        $this->assertEquals('TX-IN-20260703-0002', $nextNumber);
    }

    public function test_transaction_number_generation_backdate()
    {
        $user = User::factory()->create();

        // Create transaction on July 1
        StockTransaction::create([
            'transaction_number' => 'TX-IN-20260701-0001',
            'type' => 'inbound',
            'date' => '2026-07-01',
            'created_by' => $user->id,
        ]);

        // Create transaction on July 3 (higher ID)
        StockTransaction::create([
            'transaction_number' => 'TX-IN-20260703-0001',
            'type' => 'inbound',
            'date' => '2026-07-03',
            'created_by' => $user->id,
        ]);

        // Create a backdated transaction for July 1 (should get sequence 0002)
        $nextNumber = $this->callGenerateTransactionNumber('inbound', '2026-07-01');
        $this->assertEquals('TX-IN-20260701-0002', $nextNumber);
    }

    public function test_transaction_number_generation_empty()
    {
        User::factory()->create();

        // Initial transaction when no records exist
        $nextNumber = $this->callGenerateTransactionNumber('inbound', '2026-07-01');
        $this->assertEquals('TX-IN-20260701-0001', $nextNumber);
    }
}
