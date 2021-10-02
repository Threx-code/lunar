<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;
use Gate;

/**
 * @group
 * @bodyParam  utc_date datetime required A valid UTC datetime. Example (2021-01-05 12:10:34)
 * 
 */
 
class LunarTimeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * @queryParam  user_id required The id of the user. Example: me
     * @return bool
     */
    public function authorize()
    {
        //abort_if(Gate::denies('lunar_time'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     * @param utc_date holds the UTC date validation rules
     * @return array
     */
    public function rules()
    {
        return [
            'utc_date' => ['required', 'date_format:"Y-m-d H:i:s"']
        ];
    }


    /**
     * [messages get the validation messages]
     * @return array
     */
    public function messages()
    {
        return[
            'utc_date.date_format' => 'Valid UTC (y/m/d h:m:s) date required. Example (2021-01-05 12:10:34)',
            'utc_date.required' => 'UTC datetime is required',
        ];
    }

 /**
     * [failedValidation this handles the validation error if no parameter was set]
     * @param  Validator $validator [The Validation class was injected as a dependency for validating the required parameters and $validator was created as an object of the Validation class which calls the errors method]
     * @return [it throws an HttpResponseexception which shows that no parameter was set yet]
     */
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
