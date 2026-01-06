<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;

class ReCaptcha implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $gResponseToken = (string) $value;

        $response = Http::asForm()->post(
            'https://www.google.com/recaptcha/api/siteverify',
            [
                'secret' => env('RECAPTCHA_SECRET_KEY'), 
                'response' => $gResponseToken,
                'remoteip' => request()->ip(),
            ]
        );

        $result = json_decode($response->body(), true);

        if (!isset($result['success']) || $result['success'] !== true) {
            $fail('Invalid recaptcha');
        }
    }
}
