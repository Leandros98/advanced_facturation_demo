<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class PaiementDette extends Model
{
    use HasFactory;
    use Sortable;

    protected $fillable =['montant','montant_restant','order_id','status'];

    public $sortable = ['montant','montant_restant','order_id','status'];

    public function order(){
    	return $this->belongsTo('App\Models\Order');
    }
}
