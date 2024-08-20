<?php

namespace App\Http\Requests\Rental;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreRentalRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'durasi_sewa' => 'required|numeric',
            'area' => 'required',
            'tanggal_mulai_sewa' => 'required',
            'tanggal_akhir_sewa' => 'required',
            'alamat_keberangkatan' => 'required',
            'nama' => 'required',
            'email' => 'required|email',
            'mobil_rental_id' => 'required|numeric',
            'nik' => 'required|min:16|max:16',
            'no_telp' => 'required|min:8|max:14',
            'alamat' => 'required',
            'metode_id' => 'required|numeric',
            'all_in' => 'nullable|numeric',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation errors',
            'data' => $validator->errors()
        ], 422));
    }

    public function messages(): array
    {
        return [
            'durasi_sewa.required' => 'Durasi sewa wajib diisi',
            'durasi_sewa.numeric' => 'Durasi sewa harus berupa angka',
            'area.required' => 'Area wajib diisi',
            'tanggal_mulai_sewa.required' => 'Tanggal mulai sewa wajib diisi',
            'tanggal_akhir_sewa.required' => 'Tanggal akhir sewa wajib diisi',
            'tanggal_akhir_sewa.after_or_equal' => 'Tanggal akhir sewa harus setelah atau sama dengan tanggal mulai sewa',
            'alamat_keberangkatan.required' => 'Alamat keberangkatan wajib diisi',
            'nama.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Email harus berupa email yang valid',
            'nik.required' => 'NIK wajib diisi',
            'nik.min' => 'NIK harus terdiri dari 16 karakter',
            'nik.max' => 'NIK harus terdiri dari 16 karakter',
            'no_telp.required' => 'Nomor telepon wajib diisi',
            'no_telp.min' => 'Nomor telepon harus minimal 8 karakter',
            'no_telp.max' => 'Nomor telepon harus maksimal 14 karakter',
            'alamat.required' => 'Alamat wajib diisi',
            'metode_id.required' => 'Metode pembayaran wajib diisi',
            'all_in.numeric' => 'All in harus berupa angka',
            'mobil_rental_id.numeric' => 'All in harus berupa angka',
            'mobil_rental_id' => 'Mobil wajib diisi',

        ];
    }
}
