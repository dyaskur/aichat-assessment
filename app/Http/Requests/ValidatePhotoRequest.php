<?php

namespace App\Http\Requests;

use App\Models\Customer;
use App\Services\Recognition;
use Illuminate\Foundation\Http\FormRequest;

class ValidatePhotoRequest extends FormRequest
{
    private mixed $code;

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
    public function rules()
    {
        return [
            //
            "customer_id"    => "required_without:customer_email|exists:customers,id",
            "customer_email" => "required_without:customer_id|email|exists:customers,email",
            "photo"          => "required|image|max:2048",
        ];
    }

    public function isRecognized(): bool
    {
        return Recognition::recognize($this->file('photo'));
    }

    public function customer(): ?Customer
    {
        //todo: only use auth middleware
        if ($this->has('customer_id')) {
            return Customer::find($this->input('customer_id'));
        }
        if ($this->has('customer_email')) {
            return Customer::where('email', $this->input('customer_email'))->first();
        }

        return $this->user();
    }
}
