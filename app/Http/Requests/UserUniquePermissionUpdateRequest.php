<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUniquePermissionUpdateRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'user_id' => 'required|integer',
            'resource_id' => 'required|integer',
            'view' => 'required|boolean',
            'create' => 'required|boolean',
            'update' => 'required|boolean',
            'delete' => 'required|boolean'
        ];
    }
}
