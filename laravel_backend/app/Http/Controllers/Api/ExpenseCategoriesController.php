<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SaveExpenseCategoryRequest;
use App\Models\ExpenseCategory;
use Exception;

class ExpenseCategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ExpenseCategory::all()->makeHidden(['updated_at']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SaveExpenseCategoryRequest $request)
    {
        $category = new ExpenseCategory();
        $category->name = $request->name;
        $category->description = $request->description;
        $category->save();
        return ["message" => "Saved", "category" => $category];
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $name
     * @return \Illuminate\Http\Response
     */
    public function show($name)
    {
        // We don't seem to need this for now
        throw new Exception("Not yet implemented");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $name
     * @return \Illuminate\Http\Response
     */
    public function update(SaveExpenseCategoryRequest $request, $name)
    {
        $category = ExpenseCategory::find($name);
        $category->name = $request->name;
        $category->description = $request->description;
        $category->save();
        return ["message" => "Updated", "category" => $category];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $name
     * @return \Illuminate\Http\Response
     */
    public function destroy($name)
    {
        if (ExpenseCategory::count() <= 1) {
            return response('At least one category must remain', 403);
        }
        $category = ExpenseCategory::destroy($name);
        return ["message" => "Deleted", "category" => $category];
    }
}
