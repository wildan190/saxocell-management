<?php

use App\Models\Warehouse;
use App\Models\Store;
use App\Models\Product;
use App\Models\WarehouseStoreTransfer;
use App\Models\WarehouseStoreTransferItem;
use App\Models\StoreProduct;
use Illuminate\Support\Str;

require __DIR__ . '/bootstrap/app.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$warehouse = Warehouse::first();
$store = Store::first();
$product = Product::first();

if (!$warehouse || !$store || !$product) {
    echo "Missing data to create transfer.\n";
    exit(1);
}

// Ensure StoreProduct exists
StoreProduct::firstOrCreate(
    ['store_id' => $store->id, 'product_id' => $product->id],
    ['stock' => 5, 'price' => 100000]
);

// Create a transfer in 'shipping' status
$transfer = WarehouseStoreTransfer::create([
    'transfer_number' => 'TEST-SHIP-' . strtoupper(Str::random(4)),
    'from_warehouse_id' => $warehouse->id,
    'to_store_id' => $store->id,
    'transfer_date' => now(),
    'status' => 'shipping',
    'shipping_number' => 'RESI123456',
    'shipping_cost' => 15000,
    'notes' => 'Testing transfer tracker UI',
]);

WarehouseStoreTransferItem::create([
    'warehouse_store_transfer_id' => $transfer->id,
    'product_id' => $product->id,
    'quantity' => 10,
]);

echo "Created Transfer: {$transfer->transfer_number} with status 'shipping'.\n";
echo "Store ID: {$store->id}, Product SKU: {$product->sku}\n";
