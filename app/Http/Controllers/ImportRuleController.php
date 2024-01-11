<?php

namespace App\Http\Controllers;

use App\Enums\ImportRuleFieldEnum;
use App\Http\Requests\StoreImportRuleRequest;
use App\Models\Account;
use App\Models\Category;
use App\Models\ImportRule;
use Illuminate\Http\Request;

class ImportRuleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('import-rules.index')
            ->with(
                [
                    'importRules' => ImportRule::orderBy('account_id', 'asc')->orderBy('sequence', 'desc')->get(),
                    'accounts' => Account::all(),
                    'fields' => ImportRule::FIELD_NAMES,
                    'categories' => Category::all(),
                ]
            );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreImportRuleRequest $request)
    {
        $validated = $request->validated();
        $importRule = ImportRule::create($validated);

        return redirect()->route('import-rules.index')->with('success', 'Import rule created');
    }

    /**
     * Display the specified resource.
     */
    public function show(ImportRule $importRule)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ImportRule $importRule)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ImportRule $importRule)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ImportRule $importRule)
    {
        //
    }
}
