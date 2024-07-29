<dialog id="my_modal_3" class="modal">
    <div class="modal-box p-10">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <h3 class="font-bold text-3xl text-center mb-6">Sales Report</h3>

        <div class="flex justify-end mb-4">
            <label for="start_date" class="mr-2">Start Date:</label>
            <input type="date" id="start_date" name="start_date" class="rounded-md border-gray-300 px-2 py-1">
            <label for="end_date" class="ml-4 mr-2">End Date:</label>
            <input type="date" id="end_date" name="end_date" class="rounded-md border-gray-300 px-2 py-1">
            <button id="apply_filter" class="btn btn-sm btn-primary ml-4">Apply</button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white rounded-lg shadow-md p-6">
                <p class="font-semibold text-lg mb-2">Total Revenue:</p>
                <p class="text-2xl text-green">₱{{ $orders->sum('total_amount') }}</p>
            </div>
            @php
                $totalQuantitySold = $transactions->sum('quantity');
            @endphp
            <div class="bg-white rounded-lg shadow-md p-6">
                <p class="font-semibold text-lg mb-2">Total Items Sold:</p>
                <p class="text-2xl text-green">{{ $totalQuantitySold }}</p>
            </div>
            @php
                $remainingStock = $remainingStock->sum('quantity');
            @endphp
            <div class="bg-white rounded-lg shadow-md p-6">
                <p class="font-semibold text-lg mb-2">Remaining Items:</p>
                <p class="text-2xl text-blue-600">{{ $remainingStock }}</p>
            </div>
            @php
                $mostSoldVariantId = $transactions
                    ->groupBy('variant_id')
                    ->map(function ($grouped) {
                        return $grouped->sum('quantity');
                    })
                    ->sortDesc()
                    ->keys()
                    ->first();
            @endphp
            @if ($mostSoldVariantId)
                @php
                    $mostSoldVariant = App\Models\ProductVariant::with('images')->find($mostSoldVariantId);
                @endphp
                @if ($mostSoldVariant)
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <p class="font-semibold text-lg mb-2">Most Sold Item:</p>
                        @foreach ($mostSoldVariant->images as $image)
                            <img src="{{ asset('storage/' . $image->path) }}" alt="Product Image"
                                class="w-20 h-20 object-cover">
                        @break
                    @endforeach

                    <p class="text-xl">{{ $mostSoldVariant->product->name }}
                        ({{ $mostSoldVariant->product->brand }})
                    </p>
                    <p class="text-lg">Color: {{ $mostSoldVariant->color }}</p>
                    <p class="text-lg">Size: {{ $mostSoldVariant->size }}</p>
                    <p class="text-lg">Price: ₱{{ $mostSoldVariant->price }}</p>
                    <p class="text-lg">Total Item Sold:
                        {{ $transactions->where('variant_id', $mostSoldVariantId)->sum('quantity') }}</p>
                    <!-- Display images -->

                </div>
            @endif
        @endif

    </div>
</div>
</dialog>
