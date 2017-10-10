<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostReaction extends FormRequest
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
     * Validate the post_id and ensure that it exists.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'post_id' => 'required|integer|exists:posts,id'
        ];
    }
}
