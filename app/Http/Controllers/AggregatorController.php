<?php

namespace App\Http\Controllers;

use App\Models\Aggregator;
use Illuminate\Http\Request;

class AggregatorController extends Controller
{
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $aggregator = Aggregator::create($request->only('name'));

        return response()->json([
            'status' => true,
            'message' => 'Aggregator created successfully',
            'data' => $aggregator
        ]);
    }

   
    public function index()
    {
        return response()->json([
            'status' => true,
            'data' => Aggregator::latest()->get()
        ]);
    }

    
    public function edit($id)
    {
        $aggregator = Aggregator::findOrFail($id);

        return response()->json([
            'status' => true,
            'data' => $aggregator
        ]);
    }

    
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $aggregator = Aggregator::findOrFail($id);
        $aggregator->update($request->only('name'));

        return response()->json([
            'status' => true,
            'message' => 'Aggregator updated successfully',
            'data' => $aggregator
        ]);
    }

    
    public function destroy($id)
    {
        Aggregator::findOrFail($id)->delete();

        return response()->json([
            'status' => true,
            'message' => 'Aggregator deleted successfully'
        ]);
    }
}
