<?php

namespace App\Http\Requests;

use App\Models\Expense;
use Illuminate\Foundation\Http\FormRequest;

class SaveExpenseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        # TODO: globalize this or make an enum later
        $isAdmin = $this->user()->role === 'Administrator';

        if ($isAdmin) {
            // just so we don't do uneccessary queries anymore
            return true;
        }

        if (in_array($this->method(), ['PUT', 'PATCH'])) {

            $this->expense = Expense::find($this->id);
            $isOwner = $this->user()->id == $this->expense->author_id;
            return $isAdmin || $isOwner;
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'category' => 'required|exists:expense_categories,name',
            'amount' => 'required|numeric|min:0|not_in:0',
            'entry_date' => 'required|date'
        ];
    }
}
