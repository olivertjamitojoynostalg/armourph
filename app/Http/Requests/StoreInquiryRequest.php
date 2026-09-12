<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreInquiryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<mixed>>
     */
    public function rules(): array
    {
        return [
            'branch_id' => ['required', 'integer', Rule::exists('branches', 'id')->where('is_published', true)],
            'interest' => ['required', 'string', Rule::in(['products', 'packages', 'installation', 'general'])],
            'name' => ['required', 'string', 'min:2', 'max:100', "regex:/^[\\pL\\pM .'-]+$/u"],
            'contact' => ['required', 'string', 'regex:/^\\+?[0-9][0-9 ()-]{6,20}$/'],
            'website' => ['prohibited'],
            'form_token' => ['required', 'string', 'max:1000'],
        ];
    }

    /** @return array<callable(Validator): void> */
    public function after(): array
    {
        return [function (Validator $validator): void {
            try {
                $startedAt = (int) Crypt::decryptString($this->string('form_token')->toString());
            } catch (DecryptException) {
                $validator->errors()->add('form_token', 'Please refresh the page and try again.');

                return;
            }

            $elapsedSeconds = now()->timestamp - $startedAt;
            if ($elapsedSeconds < 2 || $elapsedSeconds > 7200) {
                $validator->errors()->add('form_token', 'Please refresh the page and try again.');
            }
        }];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => preg_replace('/\\s+/u', ' ', trim($this->string('name')->toString())),
            'contact' => trim($this->string('contact')->toString()),
        ]);
    }
}
