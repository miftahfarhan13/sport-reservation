<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
      /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'transactions';


    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    public function transaction_items()
    {
        return $this->transaction_items_query()->with('sport_activities');
    }

    public function transaction_items_query()
    {
        return $this->hasOne(TransactionItem::class, 'transaction_id', 'id');
    }
}
