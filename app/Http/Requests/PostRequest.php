<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // storeかupdateかを取得
        $route = $this->route()->getName();

        // 両方のアクションで利用するルール記述
        // 編集時は画像が必須ではないのでifで記述
        $rule = [
            'title' => 'required|string|max:50',
            'body' => 'required|string|max:2000',
        ];
        
        // 登録時: posts.store
        // 更新時: posts.update
        if (
            $route === 'posts.store' ||
            ($route === 'posts.update' && $this->file('image'))
        ) {
            $rule['image'] = 'required|file|image|mimes:jpg,png';
        }

        return $rule;
    }
}
