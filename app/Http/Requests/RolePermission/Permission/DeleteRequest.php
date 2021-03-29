<?php

namespace App\Http\Requests\RolePermission\Permission;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Validation\Validator;
use DB;

class DeleteRequest extends FormRequest
{
    private $permissions;

    function __construct()
    {
        $this->permissions = DB::table('permissions')
            ->groupBy('group_name')
            ->pluck('group_name')
            ->toArray();
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
            'checked' => [
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
