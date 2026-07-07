<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Medicine;

class MedicineController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $medicines = Medicine::when($search, function ($query) use ($search) {

            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('dosage', 'like', "%{$search}%")
                  ->orWhere('status', 'like', "%{$search}%");
        })
        ->latest()
        ->paginate(10)
        ->withQueryString();

        return view('admin.medicines.index', compact('medicines'));
    }

    public function create()
    {
        return view('admin.medicines.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
        ]);

        Medicine::create([
            'name' => $request->name,
            'price' => $request->price,
            'stock' => $request->stock,
            'dosage' => $request->dosage,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('admin.medicines.index')
            ->with('success', 'Medicine created Successfully');
    }

    public function edit($id)
    {
        $medicine = Medicine::findOrFail($id);

        return view('admin.medicines.edit', compact('medicine'));
    }

    public function update(Request $request, $id)
    {
        $medicine = Medicine::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
        ]);

        $medicine->update([
            'name' => $request->name,
            'price' => $request->price,
            'stock' => $request->stock,
            'dosage' => $request->dosage,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('admin.medicines.index')
            ->with('success', 'Medicine updated Successfully');
    }

    public function destroy($id)
    {
        Medicine::findOrFail($id)->delete();

        return back()->with('success', 'Medicine deleted Successfully');
    }
}
