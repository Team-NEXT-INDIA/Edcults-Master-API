<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Package;

class PackageController extends Controller
{
    public function index()
    {
        $packages = Package::all();
        return response()->json($packages);
    }

    public function show($id)
    {
        $package = Package::find($id);
        if (!$package) {
            return response()->json(['message' => 'Package not found'], 404);
        }
        return response()->json($package);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'price' => 'required|integer',
            'coins' => 'required|integer',
        ]);

        $package = Package::create($validatedData);

        return response()->json($package, 201);
    }

    public function update(Request $request, $id)
    {
        $package = Package::find($id);
        if (!$package) {
            return response()->json(['message' => 'Package not found'], 404);
        }

        $validatedData = $request->validate([
            'name' => 'required|string',
            'price' => 'required|integer',
            'coins' => 'required|integer',
        ]);

        $package->update($validatedData);

        return response()->json($package, 200);
    }

    public function destroy($id)
    {
        $package = Package::find($id);
        if (!$package) {
            return response()->json(['message' => 'Package not found'], 404);
        }

        $package->delete();

        return response()->json(
            ['message' => 'Package deleted successfully'],
            200
        );
    }
}
