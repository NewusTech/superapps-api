<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreMobilRentalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nopol' => 'required|string|max:20',
            'type' => 'required|string|max:50',
            'jumlah_kursi' => 'required|integer|min:1',
            'fasilitas' => 'nullable|string|max:255',
            'image_url' => 'nullable|url|max:255',
            'biaya_sewa' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string|max:1000',
            'mesin' => 'nullable|string|max:50',
            'transmisi' => 'nullable|string|in:manual,automatic',
            'kapasitas_bagasi' => 'nullable|integer|min:0',
            'bahan_bakar' => 'nullable|string|max:50',
            'biaya_all_in' => 'nullable|numeric|min:0',
        ];
    }
    public function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->all();
        $errorMessages = implode(' | ', $errors);

        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation errors',
            'data' => $errorMessages,
        ], 422));
    }
}
