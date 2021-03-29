<?php

namespace App\Http\Requests\RolePermission\Permission;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;

class CreateRequest extends FormRequest
{ 
    private $permissions;

    function __construct()
    {
        $this->permissions = ['create', 'edit', 'view', 'delete'];
    }

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
        return [
            'name' => [
                'required', 
                function ($attribute, $values, $fail) {
                    if ($values) {
                        $diff = array_diff($values, $this->permissions);
                        if (!empty($diff)) {
                            $fail('The name is invalid.');
                        }
                    }
                },
            ],
            'group_name' => [
                'required', 
                'string',
                'unique:permissions,group_name',
                function ($attribute, $value, $fail) {
                    if (!(strtolower($value) === $value)) {
                        $fail('The group name must be lower case and no special characters or utf8.');
                    }
                },
            ]
        ];
    }

    protected function failedValidation(Validator $validator) 
    {

        $errors = (new ValidationException($validator))->errors();
        throw new HttpResponseException(response()->json([
            'messages' => $errors,
            'error' => true
        ]));
    }
}
