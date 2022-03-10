<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use App\Rules\AlerjEmail;

class UserUpdate extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'required',
            'name' => ['required'],
            'email' => ['email', Rule::unique('users')->ignore($this->get('id')), new AlerjEmail()],
        ];
    }

    /**
     * @return array
     */
    public function sanitize(array $all)
    {
        if (!is_array($all['roles_array'])) {
            $all['roles_array'] = json_decode($all['roles_array'], true);
        }
        $all['username'] = $all['email'];
        return parent::sanitize($all);
    }
}
