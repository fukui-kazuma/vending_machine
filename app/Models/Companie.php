<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Companie extends Model
{
     // テーブル名
    protected $table = 'companies';

    // 可変項目
    protected $fillable =
    [
        'company_name',
        'street_address',
        'representative_name',
    ];
    
    public function product(){
        return $this->hasMany('App\Models\product');
    }
}
