<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use Illuminate\Support\Facades\File;

class ServiceController extends Controller
{

  public function index(Request $request)
   {
     $services = Service::query();

     if ($request->filled('search')) {

         $search = $request->search;

         $services->where(function ($q) use ($search) {

             $q->where('name', 'like', "%{$search}%")
               ->orWhere('description', 'like', "%{$search}%");

         });
     }

     $services = $services->latest()
                          ->paginate(10)
                          ->withQueryString();

     return view('admin.services.index', compact('services'));
  }

    public function create()
    {
        return view('admin.services.create');
    }

    public function store(Request $request)
    {
    $request->validate([
        'name' => 'required',
        'price' => 'required|numeric',
        'description' => 'nullable',
       'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240',
    ]);

    $imageName = null;

    if ($request->hasFile('image')) {

        $image = $request->file('image');

        $imageName = time().'.'.$image->getClientOriginalExtension();

        $image->move(public_path('service_images'), $imageName);
    }

    Service::create([
        'name' => $request->name,
        'price' => $request->price,
        'description' => $request->description,
        'image' => $imageName,
        'status' => $request->status,
    ]);

    return redirect()
        ->route('admin.services.index')
        ->with('success', 'Service created Successfully');
   }


    public function edit($id)
    {
        $service = Service::findOrFail($id);

        return view('admin.services.edit', compact('service'));
    }

   public function update(Request $request, $id)
   {
    $service = Service::findOrFail($id);

    $request->validate([
        'name' => 'required',
        'price' => 'required|numeric',
        'description' => 'nullable',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240',
    ]);

    $imageName = $service->image;

    if ($request->hasFile('image')) {

        if ($service->image && file_exists(public_path('service_images/'.$service->image))) {

            unlink(public_path('service_images/'.$service->image));
        }

        $image = $request->file('image');

        $imageName = time().'.'.$image->getClientOriginalExtension();

        $image->move(public_path('service_images'), $imageName);
    }

    $service->update([
        'name' => $request->name,
        'price' => $request->price,
        'description' => $request->description,
        'image' => $imageName,
        'status' => $request->status,
    ]);

    return redirect()
        ->route('admin.services.index')
        ->with('success', 'Service updated Successfully');
   }

    public function destroy($id)
    {
        Service::findOrFail($id)->delete();

        return back()->with('success', 'Service deleted Successfully');
    }
}