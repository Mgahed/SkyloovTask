<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

class TaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|max:255',
            'description' => 'required',
            'status' => ['required', Rule::in(['pending', 'in_progress', 'completed'])],
            'due_date' => 'required|date|after_or_equal:' . Carbon::now()->format('Y-m-d'),
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */

    public function messages(): array
    {
        return [
            'title.required' => 'Title is required',
            'title.max' => 'Title should not be more than 255 characters',
            'description.required' => 'Description is required',
            'status.required' => 'Status is required',
            'status.in' => 'Status should be one of pending, in_progress, completed',
            'due_date.required' => 'Due date is required',
            'due_date.date' => 'Due date should be a valid date',
            'due_date.after_or_equal' => 'Due date should be a future date',
        ];
    }
}
