<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ApplicationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'category' => ['required', 'string', Rule::in(['bereavement', 'illness', 'emergency'])],
            'subcategory' => ['required', 'string'],
            'bank_name' => ['required', 'string', 'max:100'],
            'bank_account_number' => ['required', 'string', 'max:20', 'regex:/^[0-9]+$/'],
            'documents' => ['required', 'array', 'min:1'],
            'documents.*' => ['file', 'max:10240', 'mimes:pdf,jpg,jpeg,png'], // 10MB max, allowed types
        ];

        // Category-specific validation
        if ($this->category === 'bereavement') {
            $rules['subcategory'] = ['required', 'string', Rule::in(['student', 'parent', 'sibling'])];
        } elseif ($this->category === 'illness') {
            $rules['subcategory'] = ['required', 'string', Rule::in(['outpatient', 'inpatient', 'injuries'])];
            
            // Outpatient specific
            if ($this->subcategory === 'outpatient') {
                $rules['clinic_name'] = ['required', 'string', 'max:255'];
                $rules['reason_visit'] = ['required', 'string', 'max:1000'];
                $rules['visit_date'] = ['required', 'date'];
                $rules['amount_applied'] = ['required', 'numeric', 'min:0', 'max:30'];
            }
            
            // Inpatient specific
            if ($this->subcategory === 'inpatient') {
                $rules['reason_visit'] = ['required', 'string', 'max:1000'];
                $rules['check_in_date'] = ['required', 'date'];
                $rules['check_out_date'] = ['required', 'date', 'after:check_in_date'];
                $rules['amount_applied'] = ['required', 'numeric', 'min:0', 'max:10000'];
            }
            
            // Injuries specific
            if ($this->subcategory === 'injuries') {
                $rules['amount_applied'] = ['required', 'numeric', 'min:0', 'max:200'];
            }
        } elseif ($this->category === 'emergency') {
            $rules['subcategory'] = ['required', 'string', Rule::in(['critical', 'disaster', 'others'])];
            
            // Critical illness specific
            if ($this->subcategory === 'critical') {
                $rules['amount_applied'] = ['required', 'numeric', 'min:0', 'max:200'];
            }
            
            // Disaster and Others specific
            if (in_array($this->subcategory, ['disaster', 'others'])) {
                $rules['case_description'] = ['required', 'string', 'max:2000'];
                $maxAmount = $this->subcategory === 'others' ? 5000 : 200;
                $rules['amount_applied'] = ['required', 'numeric', 'min:0', "max:{$maxAmount}"];
            }
        }

        return $rules;
    }

    /**
     * Get custom error messages for validation.
     */
    public function messages(): array
    {
        return [
            'category.required' => 'Please select a category.',
            'category.in' => 'Please select a valid category.',
            'subcategory.required' => 'Please select a sub-category.',
            'subcategory.in' => 'Please select a valid sub-category.',
            'bank_name.required' => 'Bank name is required.',
            'bank_account_number.required' => 'Bank account number is required.',
            'bank_account_number.regex' => 'Bank account number should contain only numbers.',
            'documents.required' => 'Please upload at least one document.',
            'documents.min' => 'Please upload at least one document.',
            'documents.*.file' => 'Each document must be a valid file.',
            'documents.*.max' => 'Each document must not exceed 10MB.',
            'documents.*.mimes' => 'Documents must be PDF, JPG, JPEG, or PNG files.',
            'amount_applied.max' => 'The amount applied exceeds the maximum limit.',
            'check_out_date.after' => 'Check-out date must be after check-in date.',
        ];
    }
}
