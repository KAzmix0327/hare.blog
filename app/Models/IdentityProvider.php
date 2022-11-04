<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IdentityProvider extends Model
{
    use HasFactory;
// IdentityProviderモデルとUserモデルのリレーションを設定
// $fillableも追加
    protected $fillable = [
        'uid',
        'provider',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
