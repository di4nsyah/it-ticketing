<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

/*
 * MVC form request: request yg ngecek diri sendiri sebelum masuk controller
 * rules() jalan dulu, kalo gagal user langsung dibalikin ke form
 *
 * alur: POST /login -> rules() cek format -> controller store() -> authenticate()
 */
class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * semua orang boleh kirim request login, makanya true
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * dicek dulu sebelum password sekelu dicocokin ke database
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        // Auth::attempt nyari user bedasarkan email lalu bandingin hash passwordnya
        // kalo cocok, sekaligus nandain user ini udah login
        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            // pesannya dibikin generik, kalo dijelasin bisa dipake buat nebak email terdaftar
            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        // berhasil, reset hitungan biar nggak ikut ke-limit gara-gara percobaan gagal
        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * batasnya 5x percobaan, lebih dari itu disuruh nunggu
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     *
     * digabung email + ip, biar satu orang nggak bisa dikunciin
     * gara-gara percobaan orang lain dari ip yg sama
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}
