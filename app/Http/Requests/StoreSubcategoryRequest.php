<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubcategoryRequest extends FormRequest
{
    /**
     * @OA\Info(
     *     description="Reglas definidas para la creacion de categorias"
     * )
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'description' => 'required',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
