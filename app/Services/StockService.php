<?php

namespace App\Services;

use App\Models\ProductVariant;
use App\Models\StockMutation;
use Illuminate\Database\Eloquent\Model;

class StockService
{
    /**
     * Adjust stock for a variant and log the mutation.
     * Must be run inside a DB transaction block.
     *
     * @param ProductVariant $variant
     * @param int $quantity
     * @param string $type 'inbound' or 'outbound'
     * @param string|null $note
     * @param Model|null $reference
     * @param string|null $date
     * @return ProductVariant
     * @throws \Exception
     */
    public function adjustStock(
        ProductVariant $variant,
        int $quantity,
        string $type,
        ?string $note = null,
        ?Model $reference = null,
        ?string $date = null
    ): ProductVariant {
        // Lock row for concurrent safety (avoiding race conditions)
        $variant = ProductVariant::where('id', $variant->id)->lockForUpdate()->firstOrFail();

        $stockBefore = $variant->stock;
        
        if ($type === 'inbound') {
            $stockAfter = $stockBefore + $quantity;
        } else {
            $stockAfter = $stockBefore - $quantity;
            
            // Backend safeguard for outbound stock check
            if ($stockAfter < 0) {
                throw new \Exception("Stok tidak mencukupi untuk varian SKU: {$variant->sku}. Tersedia: {$stockBefore}, Diminta: {$quantity}.");
            }
        }

        // Update the variant stock
        $variant->update([
            'stock' => $stockAfter
        ]);

        // Write to audit trail (Stock Ledger)
        StockMutation::create([
            'product_variant_id' => $variant->id,
            'type' => $type,
            'quantity' => $quantity,
            'stock_before' => $stockBefore,
            'stock_after' => $stockAfter,
            'reference_id' => $reference?->getKey(),
            'reference_type' => $reference ? get_class($reference) : null,
            'note' => $note,
            'date' => $date ? date('Y-m-d H:i:s', strtotime($date)) : now(),
        ]);

        return $variant;
    }
}
