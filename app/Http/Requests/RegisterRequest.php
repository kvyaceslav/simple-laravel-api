<?php

namespace App\Http\Requests;

use App\Constants\ValidationConstants;
use App\Http\Controllers\API\BaseController;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterRequest extends FormRequest
{
    /**
     * @var BaseController
     */
    private BaseController $baseController;

    /**
     * @param BaseController $baseController
     */
    public function __construct(BaseController $baseController)
    {
        $this->baseController = $baseController;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'confirm_password' => 'required|same:password'
        ];
    }

    /**
     * @param Validator $validator
     * @return HttpResponseException
     */
    public function failedValidation(Validator $validator): HttpResponseException
    {
        throw new HttpResponseException(
            $this->baseController->sendError(
                ValidationConstants::ERROR,
                $validator->errors()->messages()
            )
        );
    }
}
