@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="sm:flex sm:items-center mb-8">
            <div class="sm:flex-auto">
                <h1 class="text-3xl font-bold text-slate-900">Return Goods / Rejected</h1>
                <p class="mt-2 text-sm text-slate-700">Process rejection for items in Goods Receipt <span
                        class="font-semibold text-indigo-600">{{ $goodsReceipt->receipt_number }}</span>.</p>
            </div>
        </div>

        <form id="rejectionForm" action="{{ route('inventory.goods-returns.store', [$warehouse, $goodsReceipt]) }}"
            method="POST" enctype="multipart/form-data">
            @csrf
            <div class="space-y-8">
                <!-- Items Selection -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="p-6 border-b border-slate-100 bg-slate-50">
                        <h2 class="text-lg font-bold text-slate-900">Rejected Items</h2>
                    </div>
                    <div class="p-0">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead class="bg-white">
                                <tr>
                                    <th
                                        class="py-4 pl-6 pr-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                                        Product</th>
                                    <th
                                        class="px-3 py-4 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">
                                        Received Qty</th>
                                    <th
                                        class="px-3 py-4 text-center text-xs font-bold text-slate-500 uppercase tracking-wider w-32">
                                        <div class="flex flex-col items-center space-y-1">
                                            <span>Reject Qty</span>
                                            <label class="inline-flex items-center cursor-pointer">
                                                <input type="checkbox" id="selectAllCheckbox" checked
                                                    class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 h-3.5 w-3.5 transition-all">
                                                <span class="ml-1 text-[10px] lowercase font-medium">all</span>
                                            </label>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                @foreach($goodsReceipt->items as $index => $item)
                                    <tr>
                                        <td class="py-4 pl-6 pr-3">
                                            <div class="text-sm font-medium text-slate-900">{{ $item->product->name }}</div>
                                            <div class="text-xs text-slate-500">{{ $item->product->sku }}</div>
                                            <input type="hidden" name="items[{{ $index }}][product_id]"
                                                value="{{ $item->product_id }}">
                                        </td>
                                        <td class="px-3 py-4 text-center text-sm text-slate-600">
                                            {{ $item->quantity }} {{ $item->product->unit }}
                                        </td>
                                        <td class="px-3 py-4">
                                            <input type="number" name="items[{{ $index }}][quantity]"
                                                value="{{ $item->quantity }}" min="0" max="{{ $item->quantity }}"
                                                data-max="{{ $item->quantity }}"
                                                class="item-quantity-input block w-full rounded-xl border-slate-200 px-3 py-2 text-center text-sm focus:border-indigo-500 focus:ring-indigo-500 transition-all">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Reason and Resolution -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 uppercase tracking-wider">Reason for
                                Rejection</label>
                            <textarea name="reason" rows="3" required
                                class="block w-full rounded-xl border-slate-200 px-4 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm transition-all"
                                placeholder="E.g. Damaged during transit, Incorrect specification..."></textarea>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 uppercase tracking-wider">Requested
                                Resolution</label>
                            <select name="resolution" id="resolutionSelect" required
                                class="block w-full rounded-xl border-slate-200 px-4 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm transition-all">
                                <option value="Replacement">Request Replacement</option>
                                <option value="Refund">Request Refund / Credit Note</option>
                                <option value="Repair">Return for Repair</option>
                            </select>
                        </div>
                    </div>

                    <div id="adjustedPriceContainer" class="space-y-2 border-t border-slate-100 pt-6 hidden">
                        <label class="text-sm font-bold text-slate-700 uppercase tracking-wider">Adjusted Price (Credit
                            Note)</label>
                        <p class="text-xs text-slate-500 mb-2">Specify the price to be adjusted for the credit note.</p>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="text-slate-500 sm:text-sm font-bold">Rp</span>
                            </div>
                            <input type="number" name="adjusted_price" step="0.01" min="0"
                                class="block w-full rounded-xl border-slate-200 pl-12 pr-4 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm transition-all"
                                placeholder="0.00">
                        </div>
                    </div>

                    <div class="space-y-2 border-t border-slate-100 pt-6">
                        <label class="text-sm font-bold text-slate-700 uppercase tracking-wider">Proof Photos</label>
                        <p class="text-xs text-slate-500 mb-2">Upload photos clearly showing the issue/damage.</p>
                        <input type="file" name="proof_photos[]" multiple accept="image/*"
                            class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    </div>
                </div>

                <div class="flex justify-end space-x-3 pb-12">
                    <a href="{{ route('inventory.goods-receipts.show', [$warehouse, $goodsReceipt]) }}"
                        class="px-6 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition-all">Cancel</a>
                    <button type="submit"
                        class="px-8 py-2.5 rounded-xl bg-indigo-600 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-all">
                        Submit Rejection
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const resolutionSelect = document.getElementById('resolutionSelect');
            const adjustedPriceContainer = document.getElementById('adjustedPriceContainer');
            const selectAllCheckbox = document.getElementById('selectAllCheckbox');
            const itemInputs = document.querySelectorAll('.item-quantity-input');
            const form = document.getElementById('rejectionForm');

            // Handle Select All
            selectAllCheckbox.addEventListener('change', function() {
                itemInputs.forEach(input => {
                    input.value = this.checked ? input.getAttribute('data-max') : 0;
                });
            });

            // Handle individual input changes to update "Select All" state
            itemInputs.forEach(input => {
                input.addEventListener('input', function() {
                    const allMaxed = Array.from(itemInputs).every(inp => 
                        parseInt(inp.value) === parseInt(inp.getAttribute('data-max'))
                    );
                    const allZero = Array.from(itemInputs).every(inp => 
                        parseInt(inp.value) === 0
                    );
                    
                    if (allMaxed) {
                        selectAllCheckbox.checked = true;
                        selectAllCheckbox.indeterminate = false;
                    } else if (allZero) {
                        selectAllCheckbox.checked = false;
                        selectAllCheckbox.indeterminate = false;
                    } else {
                        selectAllCheckbox.indeterminate = true;
                    }
                });
            });

            // Handle Resolution Change
            resolutionSelect.addEventListener('change', function () {
                if (this.value === 'Refund') {
                    adjustedPriceContainer.classList.remove('hidden');
                } else {
                    adjustedPriceContainer.classList.add('hidden');
                }
            });

            // Handle Form Submission
            form.addEventListener('submit', function (e) {
                e.preventDefault();

                // Validate at least one item has quantity > 0
                const items = document.querySelectorAll('input[name^="items"][name$="[quantity]"]');
                let hasItems = false;
                items.forEach(input => {
                    if (parseInt(input.value) > 0) hasItems = true;
                });

                if (!hasItems) {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Please specify at least one item to reject.',
                        icon: 'error',
                        confirmButtonColor: '#4f46e5'
                    });
                    return;
                }

                Swal.fire({
                    title: 'Confirm Rejection',
                    text: "Are you sure you want to submit this rejection report?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#4f46e5',
                    cancelButtonColor: '#ef4444',
                    confirmButtonText: 'Yes, Submit',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });

            // Handle Displaying Errors from Laravel Validation
            @if($errors->any())
                let errorMessages = '';
                @foreach($errors->all() as $error)
                    errorMessages += '• {{ $error }}\n';
                @endforeach

                Swal.fire({
                    title: 'Validation Error',
                    text: errorMessages,
                    icon: 'error',
                    confirmButtonColor: '#4f46e5'
                });
            @endif
            });
    </script>
@endpush