<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\FinanceAccountController;
use App\Http\Controllers\FinanceTransactionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\GoodsReceiptController;
use App\Http\Controllers\StockTransferController;
use App\Http\Controllers\StoreProductController;
use App\Http\Controllers\WarehouseStoreTransferController;
use App\Http\Controllers\InventoryOverviewController;
use App\Http\Controllers\StoreGoodsRequestController;
use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\WarehouseGoodsRequestController;
use App\Http\Controllers\GoodsReturnController;
use App\Http\Controllers\StoreToStoreTransferController;
use App\Http\Controllers\StockOpnameController;
use Illuminate\Support\Facades\Route;

// Marketplace & Customer Portal (Public)
Route::get('/', [MarketplaceController::class, 'index'])->name('marketplace.index');
Route::get('/marketplace/{product}', [MarketplaceController::class, 'show'])->name('marketplace.show');

// Cart
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/remove/{product}', [CartController::class, 'remove'])->name('cart.remove');

// Checkout
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Warehouse Management (Warehouse-specific actions)
    Route::prefix('warehouses/{warehouse}')->group(function () {
        // Finance (Warehouse Context)
        Route::get('/finance/accounts', [FinanceAccountController::class, 'index'])->name('finance.accounts.index');
        Route::get('/finance/accounts/{account}', [FinanceAccountController::class, 'showWarehouseAccount'])->name('finance.accounts.show');
        Route::post('/finance/accounts', [FinanceAccountController::class, 'store'])->name('finance.accounts.store');
        Route::post('/finance/accounts/{account}/add-balance', [FinanceAccountController::class, 'updateBalance'])->name('finance.accounts.update-balance');

        Route::get('/finance/transactions', [FinanceTransactionController::class, 'index'])->name('finance.transactions.index');
        Route::post('/finance/transactions', [FinanceTransactionController::class, 'store'])->name('finance.transactions.store');
        Route::post('/finance/transfer', [FinanceTransactionController::class, 'transfer'])->name('finance.transactions.transfer');

        // Goods Receipts (Warehouse Context)
        Route::get('/inventory/goods-receipts', [GoodsReceiptController::class, 'index'])->name('inventory.goods-receipts.index');
        Route::get('/inventory/goods-receipts/create', [GoodsReceiptController::class, 'create'])->name('inventory.goods-receipts.create');
        Route::post('/inventory/goods-receipts', [GoodsReceiptController::class, 'store'])->name('inventory.goods-receipts.store');
        Route::get('/inventory/goods-receipts/{goods_receipt}', [GoodsReceiptController::class, 'show'])->name('inventory.goods-receipts.show');

        // Goods Returns (Warehouse Context)
        Route::get('/inventory/goods-returns', [GoodsReturnController::class, 'index'])->name('inventory.goods-returns.index');
        Route::get('/inventory/goods-receipts/{goods_receipt}/returns/create', [GoodsReturnController::class, 'create'])->name('inventory.goods-returns.create');
        Route::post('/inventory/goods-receipts/{goods_receipt}/returns', [GoodsReturnController::class, 'store'])->name('inventory.goods-returns.store');
        Route::get('/inventory/goods-returns/{goods_return}', [GoodsReturnController::class, 'show'])->name('inventory.goods-returns.show');
        Route::get('/inventory/goods-returns/{goods_return}/pdf', [GoodsReturnController::class, 'downloadPdf'])->name('inventory.goods-returns.pdf');

        // Incoming Store Requests (Warehouse Context)
        Route::get('/incoming-requests', [WarehouseGoodsRequestController::class, 'index'])->name('warehouses.incoming-requests.index');
        Route::get('/incoming-requests/{goodsRequest}', [WarehouseGoodsRequestController::class, 'show'])->name('warehouses.incoming-requests.show');
        Route::post('/incoming-requests/{goodsRequest}/confirm', [WarehouseGoodsRequestController::class, 'confirm'])->name('warehouses.incoming-requests.confirm');
        Route::post('/incoming-requests/{goodsRequest}/ship', [WarehouseGoodsRequestController::class, 'ship'])->name('warehouses.incoming-requests.ship');
    });

    // Global Resource Management
    Route::resource('warehouses', WarehouseController::class);
    Route::resource('stores', StoreController::class);
    Route::resource('products', ProductController::class);
    Route::resource('suppliers', SupplierController::class);

    // Store Management (Store-specific actions)
    Route::prefix('stores/{store}')->group(function () {
        // Finance (Store Context)
        Route::get('/finance/accounts', [FinanceAccountController::class, 'index'])->name('stores.finance.accounts.index');
        Route::get('/finance/accounts/{account}', [FinanceAccountController::class, 'showStoreAccount'])->name('stores.finance.accounts.show');
        Route::post('/finance/accounts', [FinanceAccountController::class, 'store'])->name('stores.finance.accounts.store');
        Route::post('/finance/accounts/{account}/add-balance', [FinanceAccountController::class, 'updateBalance'])->name('stores.finance.accounts.update-balance');
        Route::get('/finance/transactions', [FinanceTransactionController::class, 'index'])->name('stores.finance.transactions.index');
        Route::post('/finance/transactions', [FinanceTransactionController::class, 'store'])->name('stores.finance.transactions.store');
        Route::post('/finance/transfer', [FinanceTransactionController::class, 'transfer'])->name('stores.finance.transactions.transfer');

        Route::get('/products', [StoreProductController::class, 'index'])->name('stores.products.index');
        Route::get('/products/{product}', [StoreProductController::class, 'show'])->name('stores.products.show');
        Route::get('/products/{product}/edit', [StoreProductController::class, 'edit'])->name('stores.products.edit');
        Route::put('/products/{product}', [StoreProductController::class, 'update'])->name('stores.products.update');
        Route::post('/products/{product}/adjust', [StoreProductController::class, 'adjust'])->name('stores.products.adjust');

        // Store to Store Transfers
        Route::get('/transfers', [StoreToStoreTransferController::class, 'index'])->name('stores.transfers.index');
        Route::get('/transfers/create', [StoreToStoreTransferController::class, 'create'])->name('stores.transfers.create');
        Route::post('/transfers', [StoreToStoreTransferController::class, 'store'])->name('stores.transfers.store');
        Route::get('/transfers/{transfer}', [StoreToStoreTransferController::class, 'show'])->name('stores.transfers.show');
        Route::post('/transfers/{transfer}/ship', [StoreToStoreTransferController::class, 'ship'])->name('stores.transfers.ship');
        Route::post('/transfers/{transfer}/receive', [StoreToStoreTransferController::class, 'receive'])->name('stores.transfers.receive');

        // Goods Requests to Warehouse
        Route::get('/goods-requests', [StoreGoodsRequestController::class, 'index'])->name('stores.goods-requests.index');
        Route::get('/goods-requests/create', [StoreGoodsRequestController::class, 'create'])->name('stores.goods-requests.create');
        Route::post('/goods-requests', [StoreGoodsRequestController::class, 'store'])->name('stores.goods-requests.store');
        Route::get('/goods-requests/{request}', [StoreGoodsRequestController::class, 'show'])->name('stores.goods-requests.show');
        Route::post('/goods-requests/{request}/receive', [StoreGoodsRequestController::class, 'receive'])->name('stores.goods-requests.receive');

        Route::get('/warehouse-shipments', [WarehouseGoodsRequestController::class, 'index'])->name('stores.warehouse-shipments.index');
        Route::get('/warehouse-shipments/{transfer}', [WarehouseGoodsRequestController::class, 'show'])->name('stores.warehouse-shipments.show');
        Route::post('/warehouse-shipments/{transfer}/receive', [WarehouseGoodsRequestController::class, 'receive'])->name('stores.warehouse-shipments.receive');
    });

    // Inventory Management (Global)
    Route::get('/inventory/overview', [InventoryOverviewController::class, 'index'])->name('inventory.overview');
    Route::get('/inventory/goods-returns', [GoodsReturnController::class, 'index'])->name('inventory.goods-returns.global');

    // Stock Transfers (Global)
    Route::get('/inventory/transfers', [StockTransferController::class, 'index'])->name('inventory.transfers.index');
    Route::get('/inventory/transfers/create', [StockTransferController::class, 'create'])->name('inventory.transfers.create');
    Route::post('/inventory/transfers', [StockTransferController::class, 'store'])->name('inventory.transfers.store');
    Route::get('/inventory/transfers/{stock_transfer}', [StockTransferController::class, 'show'])->name('inventory.transfers.show');

    // Warehouse to Store Transfers (Global)
    Route::get('/inventory/warehouse-to-store', [WarehouseStoreTransferController::class, 'index'])->name('inventory.warehouse-to-store.index');
    Route::get('/inventory/warehouse-to-store/create', [WarehouseStoreTransferController::class, 'create'])->name('inventory.warehouse-to-store.create');
    Route::post('/inventory/warehouse-to-store', [WarehouseStoreTransferController::class, 'store'])->name('inventory.warehouse-to-store.store');
    Route::get('/inventory/warehouse-to-store/{transfer}', [WarehouseStoreTransferController::class, 'show'])->name('inventory.warehouse-to-store.show');

    // Stock Opname (Global)
    Route::get('/inventory/opname', [StockOpnameController::class, 'index'])->name('inventory.opname.index');
    Route::get('/inventory/opname/create', [StockOpnameController::class, 'create'])->name('inventory.opname.create');
    Route::post('/inventory/opname', [StockOpnameController::class, 'store'])->name('inventory.opname.store');
    Route::get('/inventory/opname/{opname}', [StockOpnameController::class, 'show'])->name('inventory.opname.show');
    Route::post('/inventory/opname/{opname}/items', [StockOpnameController::class, 'storeItem'])->name('inventory.opname.items.store');
    Route::post('/inventory/opname/{opname}/complete', [StockOpnameController::class, 'complete'])->name('inventory.opname.complete');

    // Admin Orders
    Route::get('/admin/orders', [AdminOrderController::class, 'index'])->name('admin.orders.index');
    Route::get('/admin/orders/{order}', [AdminOrderController::class, 'show'])->name('admin.orders.show');
    Route::post('/admin/orders/{order}/confirm', [AdminOrderController::class, 'confirm'])->name('admin.orders.confirm');
    Route::post('/admin/orders/{order}/ship', [AdminOrderController::class, 'ship'])->name('admin.orders.ship');
    Route::post('/admin/orders/{order}/complete', [AdminOrderController::class, 'complete'])->name('admin.orders.complete');
});