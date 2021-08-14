<?php

namespace App\Http\Requests;

use App\Models\Product;
use App\Models\User;
use App\Repositories\RepositoryInterface\ProductRepositoryInterface;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class BuyProductRequest extends FormRequest
{

    /** @var User  $user*/
    private $user;

    /** @var Product $product */
    private $product;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            //
        ];
    }

    protected function getValidatorInstance(): Validator
    {
        $validator = parent::getValidatorInstance();

        $productRepository = app(ProductRepositoryInterface::class);

        if (!$this->user = Auth::user()){
            $validator->after( function (Validator $validator) {
                $validator->errors()->add('user', 'You must create a new account or log in to make the purchase.');
            });
        }

        if (!$this->product = $productRepository->find($this->route('id'))){
            $validator->after( function (Validator $validator) {
                $validator->errors()->add('product', 'Producto not found.');
            });
        }

        return $validator;
    }

    protected function failedValidation(Validator $validator)
    {
        logger('Validation errors on'. get_class($this));
        logger($validator->errors()->toJson());

        $jsonResponse = response()->json(['errors' => $validator->errors()], 422);

        throw new HttpResponseException($jsonResponse);
    }
}
