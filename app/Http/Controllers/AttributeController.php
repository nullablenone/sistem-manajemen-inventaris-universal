<?php

namespace App\Http\Controllers;

use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class AttributeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');

        $attributes = Attribute::query()
            ->with('values')
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                    ->orWhereHas('values', function ($q) use ($search) {
                        $q->where('value', 'like', "%{$search}%");
                    });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('attributes.index', compact('attributes', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('attributes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:attributes,name'],
            'values' => ['required', 'array', 'min:1'],
            'values.*' => ['required', 'string', 'max:255'],
        ], [
            'name.unique' => 'Atribut dengan nama tersebut sudah ada.',
            'values.required' => 'Minimal harus menambahkan satu nilai atribut.',
            'values.min' => 'Minimal harus menambahkan satu nilai atribut.',
            'values.*.required' => 'Nilai atribut tidak boleh kosong.',
        ]);

        DB::transaction(function () use ($validated) {
            $attribute = Attribute::create([
                'name' => $validated['name'],
            ]);

            foreach ($validated['values'] as $value) {
                $trimmed = trim($value);
                if ($trimmed !== '') {
                    $attribute->values()->create([
                        'value' => $trimmed,
                    ]);
                }
            }
        });

        return redirect()
            ->route('attributes.index')
            ->with('success', 'Atribut berhasil dibuat.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Attribute $attribute): View
    {
        $attribute->load('values');
        return view('attributes.edit', compact('attribute'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Attribute $attribute): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:attributes,name,' . $attribute->id],
            'values' => ['required', 'array', 'min:1'],
            'values.*' => ['required', 'string', 'max:255'],
        ], [
            'name.unique' => 'Atribut dengan nama tersebut sudah ada.',
            'values.required' => 'Minimal harus menambahkan satu nilai atribut.',
            'values.min' => 'Minimal harus menambahkan satu nilai atribut.',
            'values.*.required' => 'Nilai atribut tidak boleh kosong.',
        ]);

        DB::transaction(function () use ($validated, $attribute) {
            $attribute->update([
                'name' => $validated['name'],
            ]);

            $existingValues = $attribute->values()->pluck('value', 'id')->toArray();
            $newValues = array_map('trim', $validated['values']);

            // Hapus nilai yang tidak ada di input baru
            $toDeleteIds = [];
            foreach ($existingValues as $id => $val) {
                if (!in_array($val, $newValues)) {
                    $toDeleteIds[] = $id;
                }
            }
            if (!empty($toDeleteIds)) {
                AttributeValue::destroy($toDeleteIds);
            }

            // Tambahkan nilai baru yang belum ada sebelumnya
            $existingValueStrings = array_values($existingValues);
            foreach ($newValues as $val) {
                if ($val !== '' && !in_array($val, $existingValueStrings)) {
                    $attribute->values()->create(['value' => $val]);
                }
            }
        });

        return redirect()
            ->route('attributes.index')
            ->with('success', 'Atribut berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attribute $attribute): RedirectResponse
    {
        $attribute->delete();

        return redirect()
            ->route('attributes.index')
            ->with('success', 'Atribut berhasil dihapus.');
    }
}
