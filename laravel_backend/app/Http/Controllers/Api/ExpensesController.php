<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SaveExpenseRequest;
use App\Models\Expense;
use Exception;
use Illuminate\Http\Request;

class ExpensesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // TODO: it's possible to create a function for this in the user model
        // Also, this wasn't in the document, but was kinda implied due to the view
        // being called "My Expenses". It would also be weird for users to
        // see the expenses of others. So we only allow all for admins
        if ($request->user()->role == 'Administrator') {
            $expenses = Expense::all();
        } else {
            $expenses = Expense::OwnedBy($request->user()->id)->get();
        }

        return $expenses->makeHidden(['author_id', 'updated_at'])->map(function ($exp) {
            $exp['amount'] = $exp['amount_in_cents'] / 100;
            return $exp;
        });
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SaveExpenseRequest $request)
    {
        $expense = new Expense();
        $expense->category = $request->category;
        $expense->amount_in_cents = $request->amount * 100;
        $expense->entry_date = $request->entry_date;
        $expense->author_id = $request->user()->id;
        $expense->save();
        return ["message" => "Saved", "expense" => $expense];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // We don't seem to need this for now
        throw new Exception("Not yet implemented");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SaveExpenseRequest $request, $id)
    {
        $expense = Expense::find($id);
        $expense->category = $request->category;
        $expense->amount_in_cents = $request->amount * 100;
        $expense->entry_date = $request->entry_date;
        $expense->save();
        return ["message" => "Saved", "expense" => $expense];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $expense = Expense::destroy($id);
        return ["message" => "Deleted", "expense" => $expense];
    }
}
