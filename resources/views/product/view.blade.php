<x-app-layout>
    <div  x-data="productItem({{ json_encode([
                    'id' => $product->id,
                    'slug' => $product->slug,
                    'image' => $product->image ?: '/img/noimage.png',
                    'title' => $product->title,
                    'price' => $product->price,
                    'quantity' => $product->quantity,
                    'addToCartUrl' => route('cart.add', $product)
                ]) }})" class="container mx-auto text-center">
        <div class="flex flex-col items-center justify-center gap-6">
            <div class="w-full max-w-lg">
                <h1 class="text-lg font-semibold">
                    {{$product->title}}
                </h1>
                <div class="text-xl font-bold mb-6">{{$product->price}} Lei</div>
                @if ($product->quantity === 0)
                    <div class="bg-red-400 text-white py-2 px-3 rounded mb-3">
                        Produsul nu mai este în stoc
                    </div>
                @endif
                <div class="flex items-center justify-center mb-5">
                    <label for="quantity" class="block font-bold mr-4">
                        Cantitate
                    </label>
                    <input
                        type="number"
                        name="quantity"
                        x-ref="quantityEl"
                        value="1"
                        min="1"
                        class="w-32 focus:border-orange-500 focus:outline-none rounded"
                    />
                </div>
                <button
                    :disabled="product.quantity === 0"
                    @click="addToCart($refs.quantityEl.value)"
                    class="btn-primary py-4 text-lg flex justify-center min-w-0 w-full mb-6"
                    :class="product.quantity === 0 ? 'cursor-not-allowed' : 'cursor-pointer'"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-6 w-6 mr-2"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"
                        />
                    </svg>
                    Adaugă în coș
                </button>
                <div class="mb-6" x-data="{expanded: false}">
                    <div
                        x-show="expanded"
                        x-collapse.min.120px
                        class="text-gray-500 wysiwyg-content text-left"
                    >
                        {!! $product->description !!}
                    </div>
                    <p class="text-right">
                        <a
                            @click="expanded = !expanded"
                            href="javascript:void(0)"
                            class="text-orange-500 hover:text-orange-700"
                            x-text="expanded ? 'Citeste mai puțin' : 'Citește mai mult'"
                        ></a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
