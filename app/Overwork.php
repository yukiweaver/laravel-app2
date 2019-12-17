<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Overwork extends Model
{
  /**
   * 残業データを所有するユーザー取得
   */
  public function user()
  {
    return $this->belongsTo('App\User');
  }
}
