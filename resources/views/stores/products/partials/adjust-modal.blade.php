<div id="adjustModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" onclick="closeAdjustModal()">
        </div>

        <div
            class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-xl">
            <form id="adjustForm" method="POST" action="" enctype="multipart/form-data">
                @csrf
                <div class="px-8 py-6 border-b border-slate-50 flex items-center justify-between bg-slate-50/50">
                    <h3 class="text-lg font-bold text-slate-900" id="modalTitle">Adjust Product</h3>
                    <button type="button" onclick="closeAdjustModal()" class="text-slate-400 hover:text-slate-500">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="p-8 space-y-5">
                    <div class="flex items-center gap-3 p-4 bg-indigo-50/50 rounded-2xl border border-indigo-100/50">
                        <div class="flex h-6 items-center">
                            <input id="modalActive" name="is_active" type="checkbox"
                                class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-600 transition-colors">
                        </div>
                        <div class="text-sm leading-6 flex-auto">
                            <label for="modalActive" class="font-bold text-indigo-900">Show in Marketplace</label>
                            <p class="text-xs text-indigo-600/70">Enable this to make the product visible to customers.
                            </p>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <label
                                class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Purchase
                                Price Reference</label>
                            <div id="modalPurchaseDisplay" class="text-sm font-black text-slate-600">Rp 0</div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label
                                class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-1.5">Stock</label>
                            <input type="number" name="stock" id="modalStock" required
                                class="block w-full rounded-xl border-slate-200 px-4 py-3 bg-white shadow-sm focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-1.5">Min
                                Price (Rp)</label>
                            <input type="number" name="min_price" id="modalMinPrice" required step="0.01"
                                class="block w-full rounded-xl border-slate-200 px-4 py-3 bg-white shadow-sm focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-1.5">Max
                                Price (Rp)</label>
                            <input type="number" name="max_price" id="modalMaxPrice" required step="0.01"
                                class="block w-full rounded-xl border-slate-200 px-4 py-3 bg-white shadow-sm focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label
                                class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-1.5">Marketplace
                                Price (Rp)</label>
                            <input type="number" name="price" id="modalPrice" required step="0.01"
                                class="block w-full rounded-xl border-slate-200 px-4 py-3 bg-white shadow-sm focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm">
                        </div>
                        <div>
                            <label
                                class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-1.5">Label</label>
                            <input type="text" name="label" id="modalLabel" placeholder="e.g. Best Seller, New"
                                class="block w-full rounded-xl border-slate-200 px-4 py-3 bg-white shadow-sm focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm">
                        </div>
                        <div>
                            <label
                                class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-1.5">Category</label>
                            <input type="text" name="category" id="modalCategory" list="category-list"
                                placeholder="Laptop, Phone..."
                                class="block w-full rounded-xl border-slate-200 px-4 py-3 bg-white shadow-sm focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm">
                            <datalist id="category-list">
                                <option value="Laptop">
                                <option value="Smartphone">
                                <option value="Tablet">
                                <option value="Accessories">
                            </datalist>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-1.5">Product
                            Image</label>
                        <input type="file" name="image" accept="image/*"
                            class="mt-1 block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-1.5">Internal
                            Description</label>
                        <textarea name="internal_description" id="modalInternalDesc" rows="3"
                            class="block w-full rounded-xl border-slate-200 px-4 py-3 bg-white shadow-sm focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm"
                            placeholder="Internal notes not visible to customers..."></textarea>
                    </div>

                    <div>
                        <label
                            class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-1.5">Description
                            1 (Marketplace)</label>
                        <input type="text" name="description_1" id="modalDesc1"
                            class="block w-full rounded-xl border-slate-200 px-4 py-3 bg-white shadow-sm focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm"
                            placeholder="Color, size, or material...">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label
                                class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-1.5">Description
                                2</label>
                            <input type="text" name="description_2" id="modalDesc2"
                                class="block w-full rounded-xl border-slate-200 px-4 py-3 bg-white shadow-sm focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm">
                        </div>
                        <div>
                            <label
                                class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-1.5">Description
                                3</label>
                            <input type="text" name="description_3" id="modalDesc3"
                                class="block w-full rounded-xl border-slate-200 shadow-sm focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm">
                        </div>
                    </div>
                </div>

                <div class="bg-slate-50 px-8 py-4 flex items-center justify-end gap-3 border-t border-slate-100">
                    <button type="button" onclick="closeAdjustModal()"
                        class="text-sm font-semibold text-slate-600 hover:text-slate-900 transition-colors">Cancel</button>
                    <button type="submit"
                        class="rounded-xl bg-indigo-600 px-8 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-all active:scale-[0.98]">
                        Save Product
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>