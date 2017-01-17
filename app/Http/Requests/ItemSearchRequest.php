<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ItemSearchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $category = implode(",", array_keys(config('amazonAPI.category')));
        $condition = implode(",", array_keys(config('amazonAPI.condition')));
        $sort = implode(",", array_keys(config('amazonAPI.sort')));

        return [
            'keywords'  => 'nullable|max:50',
            'category'  => 'in:' . $category,
            'condition' => 'nullable|in:' . $condition,
            'sort' => 'nullable|in:'. $sort,
        ];
    }
}
