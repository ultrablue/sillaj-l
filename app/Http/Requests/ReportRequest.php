<?php

namespace App\Http\Requests;

use Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Carbon\CarbonImmutable;

class ReportRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'start_date' => 'date',
            'end_date'   => 'date',
        ];
    }
}
