<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TaskRequest extends FormRequest
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
    public function rules(Request $request)
    {
        $image = request()->isMethod('put') ? 'nullable|image|mimes:jpeg,jpg,png,gif,svg|max:2048' : 'required|image|mimes:jpeg,jpg,png,gif,svg|max:2048';
        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('tasks')->ignore($request->id),
            ],
            'contact_number' => 'required|numeric',
            'gender' => 'required|in:male,female,other',
            'profile_pic' => $image,
            'hobbies' => ['required', 'array', 'min:1'],
            'state' => 'required|exists:states,id',
            'city' => 'required|exists:cities,id',
        ];
    }
}
