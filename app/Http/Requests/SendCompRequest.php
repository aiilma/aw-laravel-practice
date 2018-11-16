<?php

namespace Artworch\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendCompRequest extends FormRequest
{
    protected $MAX_FILE_SIZE_MB = 150;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // True, если пользователь авторизован под учетной записью
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            '_project' => 'required|file|mimetypes:application/zip|max:'.$this->MAX_FILE_SIZE_MB * 1024,
            '_receive' => 'required|numeric|between:0,1000',
        ];
    }
    
    /**
     * Get the error messages for the defined validation rules
     *
     * @return array
     */
    public function messages()
    {
        return [
            '_project.required' => 'The source project is required',
            '_project.mimetypes' => 'The source project must be a file of ZIP type',
            '_project.max' => 'The source project may not be greater than '.$this->MAX_FILE_SIZE_MB.' MB',
            
            '_receive.required' => 'The price is required',
            '_receive.numeric' => 'The price must be a numeric',
            '_receive.between' => 'The price must be between :min and :max',
        ];
    }
}
