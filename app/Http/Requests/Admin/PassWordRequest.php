<?php

namespace App\Http\Requests\Admin;

class PassWordRequest extends Base
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return match ($this->route()?->getActionMethod()) {
            'login' => $this->loginRules(),
            'index' => parent::rules(),
        };
    }

    public function loginRules(): array
    {
        return [
            'email' => 'required|string|max:255',
            'password' => 'required',
            //'captcha' => 'prohibited|captcha',
            'captcha' => 'nullable',
        ];
    }

    public function xxxwithValidator($validator)
    {
        $validator->after(function ($validator) {
            // 如果前面账号密码已经验证失败了，就没必要再查验证码了
            if ($validator->errors()->any()) {
                return;
            }

            $captcha = $this->input('captcha');

            // 调用你的验证码检查函数
            if (!captcha_check($captcha)) {
                // 手动向错误收集器添加一条错误
                $validator->errors()->add('captcha', '验证码输入不正确');
            }
        });
    }

    public function messages(): array
    {
        return [
            'captcha.required' => '图形验证码不能为空。',
            'captcha.captcha' => '图形验证码输入错误或已过期，请重新刷新。',
            'email.required' => '请输入您的邮箱。',
            'password.required' => '请输入您的密码。',
        ];
    }
}
