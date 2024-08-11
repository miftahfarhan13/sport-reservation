<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionItem extends Model
{
    use HasFactory;

      /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'transaction_items';


    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    public function sport_activities()
    {
        return $this->belongsTo(
            'App\Models\SportActivity',
            'sport_activity_id'
        );
    }
}
