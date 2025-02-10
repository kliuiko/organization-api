<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetOrganizationsInBoundsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'min_lat' => ['required', 'numeric', 'between:-90,90'],
            'max_lat' => ['required', 'numeric', 'between:-90,90', 'gte:min_lat'],
            'min_lng' => ['required', 'numeric', 'between:-180,180'],
            'max_lng' => ['required', 'numeric', 'between:-180,180', 'gte:min_lng'],
        ];
    }
}
