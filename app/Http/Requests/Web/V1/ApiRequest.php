<?php

namespace App\Http\Requests\Web\V1;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

abstract class ApiRequest extends FormRequest
{
    
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    abstract  public function authorize();
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    abstract public function rules();

    protected function failedValidation(Validator $validator)
    { 
        $errors = (new ValidationException($validator))->errors();
        if(!empty($errors)){
            $customErrors = [];
            foreach($errors as $field => $error){
                $customErrors[] = $error[0] ;
            }       
            throw new HttpResponseException(
                response()->json(
                    ['code' => 422,'status'=>"error" , 'message' => $customErrors[0]],
                    422
                )
            );
        }
    }

}
