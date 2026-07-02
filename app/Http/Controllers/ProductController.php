<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Attribute;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');

        $products = Product::query()
            ->with(['category', 'variants'])
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('products.index', compact('products', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $categories = Category::all();
        $attributes = Attribute::with('values')->get();

        return view('products.create', compact('categories', 'attributes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $rules = [
            'name' => ['required', 'string', 'max:255', 'unique:products,name'],
            'category_id' => ['required', 'exists:categories,id'],
            'description' => ['nullable', 'string'],
        ];

        if ($request->has('has_variant')) {
            $rules['variants'] = ['required', 'array', 'min:1'];
            $rules['variants.*.sku'] = ['required', 'string', 'max:255', 'unique:product_variants,sku'];
            $rules['variants.*.price'] = ['required', 'numeric', 'min:0'];
            $rules['variants.*.attribute_values'] = ['required', 'array', 'min:1'];
            $rules['variants.*.attribute_values.*'] = ['exists:attribute_values,id'];
        } else {
            $rules['single_sku'] = ['required', 'string', 'max:255', 'unique:product_variants,sku'];
            $rules['single_price'] = ['required', 'numeric', 'min:0'];
        }

        $validated = $request->validate($rules, [
            'name.required' => 'Nama produk wajib diisi.',
            'name.unique' => 'Nama produk sudah digunakan.',
            'category_id.required' => 'Kategori wajib dipilih.',
            'single_sku.required' => 'SKU wajib diisi untuk produk tunggal.',
            'single_sku.unique' => 'SKU sudah digunakan.',
            'single_price.required' => 'Harga wajib diisi untuk produk tunggal.',
            'single_price.numeric' => 'Harga harus berupa angka.',
            'variants.required' => 'Varian produk wajib dikonfigurasi.',
            'variants.*.sku.required' => 'SKU varian wajib diisi.',
            'variants.*.sku.unique' => 'SKU varian sudah digunakan.',
            'variants.*.price.required' => 'Harga varian wajib diisi.',
            'variants.*.price.numeric' => 'Harga varian harus berupa angka.',
            'variants.*.attribute_values.required' => 'Kombinasi nilai atribut varian tidak boleh kosong.',
        ]);

        DB::transaction(function () use ($request, $validated) {
            $hasVariant = $request->has('has_variant');

            $product = Product::create([
                'name' => $validated['name'],
                'category_id' => $validated['category_id'],
                'description' => $validated['description'] ?? null,
                'has_variant' => $hasVariant,
            ]);

            if ($hasVariant) {
                foreach ($request->input('variants', []) as $variantData) {
                    $variant = $product->variants()->create([
                        'sku' => $variantData['sku'],
                        'price' => $variantData['price'],
                        'stock' => 0,
                    ]);

                    if (isset($variantData['attribute_values'])) {
                        $variant->attributeValues()->attach($variantData['attribute_values']);
                    }
                }
            } else {
                $product->variants()->create([
                    'sku' => $validated['single_sku'],
                    'price' => $validated['single_price'],
                    'stock' => 0,
                ]);
            }
        });

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): RedirectResponse
    {
        return redirect()->route('products.edit', $id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        $product = Product::with(['variants.attributeValues', 'category'])->findOrFail($id);
        $categories = Category::all();
        $attributes = Attribute::with('values')->get();

        return view('products.edit', compact('product', 'categories', 'attributes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $product = Product::findOrFail($id);

        $rules = [
            'name' => ['required', 'string', 'max:255', 'unique:products,name,' . $product->id],
            'category_id' => ['required', 'exists:categories,id'],
            'description' => ['nullable', 'string'],
        ];

        if ($request->has('has_variant')) {
            $rules['variants'] = ['required', 'array', 'min:1'];
            $rules['variants.*.sku'] = [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) use ($product) {
                    $exists = \App\Models\ProductVariant::where('sku', $value)
                        ->where('product_id', '!=', $product->id)
                        ->exists();
                    if ($exists) {
                        $fail('SKU "' . $value . '" sudah digunakan oleh produk lain.');
                    }
                }
            ];
            $rules['variants.*.price'] = ['required', 'numeric', 'min:0'];
            $rules['variants.*.attribute_values'] = ['required', 'array', 'min:1'];
            $rules['variants.*.attribute_values.*'] = ['exists:attribute_values,id'];
        } else {
            $rules['single_sku'] = [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) use ($product) {
                    $exists = \App\Models\ProductVariant::where('sku', $value)
                        ->where('product_id', '!=', $product->id)
                        ->exists();
                    if ($exists) {
                        $fail('SKU "' . $value . '" sudah digunakan oleh produk lain.');
                    }
                }
            ];
            $rules['single_price'] = ['required', 'numeric', 'min:0'];
        }

        $validated = $request->validate($rules, [
            'name.required' => 'Nama produk wajib diisi.',
            'name.unique' => 'Nama produk sudah digunakan.',
            'category_id.required' => 'Kategori wajib dipilih.',
            'variants.required' => 'Varian produk wajib dikonfigurasi.',
            'variants.*.sku.required' => 'SKU varian wajib diisi.',
            'variants.*.price.required' => 'Harga varian wajib diisi.',
            'variants.*.price.numeric' => 'Harga varian harus berupa angka.',
            'variants.*.attribute_values.required' => 'Kombinasi nilai atribut varian tidak boleh kosong.',
            'single_sku.required' => 'SKU wajib diisi.',
            'single_price.required' => 'Harga wajib diisi.',
            'single_price.numeric' => 'Harga harus berupa angka.',
        ]);

        DB::transaction(function () use ($request, $validated, $product) {
            $hasVariant = $request->has('has_variant');

            $product->update([
                'name' => $validated['name'],
                'category_id' => $validated['category_id'],
                'description' => $validated['description'] ?? null,
                'has_variant' => $hasVariant,
            ]);

            if ($hasVariant) {
                // Ambil varian lama beserta attribute_values-nya
                $existingVariants = $product->variants()->with('attributeValues')->get();

                // Map existing variants by attribute values signature
                $existingMap = [];
                foreach ($existingVariants as $variant) {
                    $valIds = $variant->attributeValues->pluck('id')->toArray();
                    sort($valIds);
                    $signature = implode(',', $valIds);
                    $existingMap[$signature] = $variant;
                }

                $inputSignatures = [];

                foreach ($request->input('variants', []) as $variantData) {
                    $valIds = $variantData['attribute_values'] ?? [];
                    sort($valIds);
                    $signature = implode(',', $valIds);
                    $inputSignatures[] = $signature;

                    if (isset($existingMap[$signature])) {
                        // Update varian yang sudah ada (pertahankan ID dan Stok)
                        $existingMap[$signature]->update([
                            'sku' => $variantData['sku'],
                            'price' => $variantData['price'],
                        ]);
                    } else {
                        // Buat varian baru
                        $newVariant = $product->variants()->create([
                            'sku' => $variantData['sku'],
                            'price' => $variantData['price'],
                            'stock' => 0,
                        ]);
                        $newVariant->attributeValues()->attach($valIds);
                    }
                }

                // Hapus varian yang tidak ada lagi di input
                foreach ($existingMap as $sig => $variant) {
                    if (!in_array($sig, $inputSignatures)) {
                        $variant->delete();
                    }
                }
            } else {
                // Skenario: Produk saat ini TIDAK memiliki varian (Single Product)

                // Cek apakah sebelumnya produk ini adalah single product (hanya punya 1 varian)
                // dan tidak terhubung ke tabel pivot attribute_values
                $existingVariant = $product->variants()->first();

                if ($existingVariant && $product->variants()->count() === 1 && $existingVariant->attributeValues()->count() === 0) {
                    // Jika sebelumnya memang single product, cukup UPDATE saja agar STOK TIDAK HILANG
                    $existingVariant->update([
                        'sku' => $validated['single_sku'],
                        'price' => $validated['single_price'],
                    ]);
                } else {
                    // Jika sebelumnya adalah produk bervarian (banyak), lalu user mengubahnya menjadi single product
                    // Maka wajar kita hapus semua varian lamanya
                    $product->variants()->delete();

                    // Lalu buat 1 varian baru dari awal
                    $product->variants()->create([
                        'sku' => $validated['single_sku'],
                        'price' => $validated['single_price'],
                        'stock' => 0,
                    ]);
                }
            }
        });

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
}
