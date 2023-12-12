<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreStudent extends FormRequest
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
            'lrn' => ['required',
                'string',
                Rule::unique('students')->ignore($this->student?? null),
                ],
            'student_number' => ['required',
                'string',
                Rule::unique('students')->ignore($this->student?? null),
                ],
            'email' => [
                'required',
                'email',
                Rule::unique('students')->ignore($this->student?? null),
                ],
            'program' => 'required',
            'year' => 'required',
            'section' => 'required',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'birthdate' => 'required',
            'sex' => 'required',
            'gender' => 'required',
            'weight' => 'required|integer',
            'height' => 'required|integer',
            'civil_status' => 'required',
            'phone' => 'required|string',
            'region' => 'required',
            'province' => 'required',
            'city' => 'required',
            'barangay' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
          'lrn.required' => 'LRN is required!',
            'lrn.unique' => 'LRN must be unique!',
            'lrn.string' => 'LRN must be a string!',
            'student_number.required' => 'Student Number is required!',
            'student_number.string' => 'Student Number must be a string!',
            'student_number.unique' => 'Student Number must be unique!',
            'email.required' => 'Email is required!',
            'email.email' => 'Email must be a valid email address!',
            'email.unique' => 'Email must be unique!',
            'program.required' => 'Program is required!',
            'year.required' => 'Year is required!',
            'section.required' => 'Section is required!',
            'first_name.required' => 'Firstname is required!',
            'first_name.string' => 'Firstname must be a string!',
            'last_name.required' => 'Lastname is required!',
            'last_name.string' => 'Lastname must be a string!',
            'birthdate.required' => 'Birthdate is required!',
            'sex.required' => 'Sex is required!',
            'gender.required' => 'Gender is required!',
            'weight.required' => 'Weight is required!',
            'weight.integer' => 'Weight must be an number!',
            'height.required' => 'Height is required!',
            'height.integer' => 'Height must be an number!',
            'civil_status.required' => 'Civil Status is required!',
            'phone.required' => 'Phone is required!',
            'phone.string' => 'Phone must be a number!',
            'region.required' => 'Region is required!',
            'province.required' => 'Province is required!',
            'city.required' => 'City is required!',
            'barangay.required'=> 'Barangay is required!',
        ];
    }
}
