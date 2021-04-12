<?php

namespace App\Http\Requests\RolePermission\Permission;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Validation\Validator;
use DB;

class EditRequest extends FormRequest
{ 
    private $group_names;

    function __construct()
    {
        $group_names = DB::table('permissions')
            ->groupBy('group_name')
            ->pluck('group_name')
            ->toArray();

        $this->group_names = $group_names;
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
                function ($attribute, $value, $fail) {
                    if ($value) {
                        $group_name = $this->request->get('group_name');
                        if (!in_array($group_name, $this->group_names)) {
                            $fail('The group name is invalid.');
                        }
                        
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
