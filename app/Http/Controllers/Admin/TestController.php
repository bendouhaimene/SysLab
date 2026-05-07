<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Archive;
use App\Models\Test;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index()
    {
        $tests    = Test::where('is_archived', false)->latest()->get();
        $archived = Test::where('is_archived', true)->latest()->get();
        return view('admin.tests.index', compact('tests', 'archived'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:100',
            'category'   => 'required|string|max:100',
            'price'      => 'required|numeric|min:0',
            'unit'       => 'nullable|string|max:50',
            'normal_min' => 'nullable|numeric',
            'normal_max' => 'nullable|numeric',
            'normal_label' => 'nullable|string|max:100',
        ]);

        $test = Test::create($request->only([
            'name','category','price',
            'unit','normal_min','normal_max','normal_label'
        ]));

        Archive::create([
            'model_type'    => 'Test',
            'model_id'      => $test->id,
            'action'        => 'created',
            'performed_by'  => auth()->id(),
            'data_snapshot' => $test->toArray(),
        ]);

        return back()->with('success', 'Test added successfully.');
    }

    public function update(Request $request, Test $test)
    {
        $request->validate([
            'name'         => 'required|string|max:100',
            'category'     => 'required|string|max:100',
            'price'        => 'required|numeric|min:0',
            'unit'         => 'nullable|string|max:50',
            'normal_min'   => 'nullable|numeric',
            'normal_max'   => 'nullable|numeric',
            'normal_label' => 'nullable|string|max:100',
        ]);

        $test->update($request->only([
            'name','category','price',
            'unit','normal_min','normal_max','normal_label'
        ]));

        Archive::create([
            'model_type'    => 'Test',
            'model_id'      => $test->id,
            'action'        => 'updated',
            'performed_by'  => auth()->id(),
            'data_snapshot' => $test->fresh()->toArray(),
        ]);

        return back()->with('success', 'Test updated successfully.');
    }

    public function archive(Test $test)
    {
        $test->update(['is_archived' => true]);

        Archive::create([
            'model_type'    => 'Test',
            'model_id'      => $test->id,
            'action'        => 'archived',
            'performed_by'  => auth()->id(),
            'data_snapshot' => $test->toArray(),
        ]);

        return back()->with('success', 'Test archived successfully.');
    }

    public function restore(Test $test)
    {
        $test->update(['is_archived' => false]);

        Archive::create([
            'model_type'    => 'Test',
            'model_id'      => $test->id,
            'action'        => 'restored',
            'performed_by'  => auth()->id(),
            'data_snapshot' => $test->toArray(),
        ]);

        return back()->with('success', 'Test restored successfully.');
    }

    public function destroy(Test $test)
    {
        Archive::create([
            'model_type'    => 'Test',
            'model_id'      => $test->id,
            'action'        => 'deleted',
            'performed_by'  => auth()->id(),
            'data_snapshot' => $test->toArray(),
        ]);

        $test->delete();
        return back()->with('success', 'Test deleted permanently.');
    }
}