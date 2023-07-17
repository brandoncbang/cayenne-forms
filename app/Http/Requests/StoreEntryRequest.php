<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\App;

class StoreEntryRequest extends FormRequest
{
    public function ip(): string
    {
        return App::environment('demo') ? '(Removed for privacy)' : parent::ip();
    }

    public function userAgent(): string
    {
        return App::environment('demo') ? '(Removed for privacy)' : parent::userAgent();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }
}
