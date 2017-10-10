<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePost extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Validate the content of a post.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'postContent' => 'required|min:1|max:140'
        ];
    }
}
