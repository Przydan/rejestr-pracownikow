<?php

declare(strict_types=1);

namespace App\Http\Requests\Contact;

use Illuminate\Foundation\Http\FormRequest;

class ReplyThreadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Authorized in controller via Policy
    }

    public function rules(): array
    {
        return [
            'content' => 'required|string',
        ];
    }
}
