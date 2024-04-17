<?php

namespace App\Models;

use App\Jobs\ObrSendInvoince;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

/**
*
* « EN » : Entrée Normales
* « ER » : Entrée Retour marchandises
* « EI » : Entrée Inventaire
* « EAJ » : Entrées Ajustement
* « ET » : Entrées Transfert
* « EAU » : Entrées Autres
* « SN » : Sorties Normales
* « SP » : Sorties Perte
* « SV » : Sorties Vol
* « SD » : Sorties Désuétude
* « SC » : Sorties Casse
* « SAJ » : Sorties Ajustement
* « ST » : Sorties Transfert
* « SAU » : Sorties Autres
*/

class ObrMouvementStock extends Model
{
    use HasFactory;
    protected $guarded = [];
    public static function getMouvouments(){
        return [
            'EN' => 'Entrée Normales',
            'ER' => 'Entrée Retour',
            'EI' => 'Entrée Inventaire',
            'EAJ' => 'Entrées Ajustement',
            'ET' => 'Entrées Transfert',
            'EAU' => 'Entrées Autres',
            'SN' => 'Sorties Normales',
            'SP' => 'Sorties Perte',
            'SV' => 'Sorties Vol',
            'SD' => 'Sorties Désuétude',
            'SC' => 'Sorties Casse',
            'SAJ' => 'Sorties Ajustement',
            'ST' => 'Sorties Transfert',
            'SAU' => 'Sorties Autres',
        ];
    }

    public function produit(){
        return $this->hasMany(Product::class, 'item_code');
    }
    public static function saveMouvement(Product $produit, string $mouvement, float $price,float $qte, $item_movement_description = null, $item_movement_invoice_ref = null ){

        Session::put('cancel_syncronize', false);
        $item_movement_date = now();

        $active_data = [
            'system_or_device_id' => env('OBR_USERNAME'),
            'item_code'=> $produit->id,
            'item_designation' => $produit->name,
            'item_quantity' => $qte,
            'item_measurement_unit' => $produit->unite_mesure,
            'item_purchase_or_sale_price' => $price,
            'item_purchase_or_sale_currency' => 'BIF',
            'item_movement_type' => $mouvement,
            'item_movement_invoice_ref' => $item_movement_invoice_ref,
            'item_movement_description' => $item_movement_description,
            'item_movement_date' => $item_movement_date,
            'is_send_to_obr' => false,
            'user_id' => auth()->user()->id,
        ];

        if( in_array( $mouvement, ['SN' ,'SP','SV', 'SD','SC','SAJ','ST','SAU'])){
            $reste = $qte;
            foreach($produit->productDetails as $detail){
                $tmp = $reste;
                $reste =  $reste - $detail->quantite_restant;
                if($reste > 0){
                    //table.push(product.value)
                    self::create( array_merge($active_data, [
                        'item_quantity' =>  $detail->quantite_restant,
                        'item_purchase_or_sale_price' => $detail->prix_revient,
                    ]));
                    $detail->quantite_restant = 0;
                    $detail->save();
                }else if( $reste <= 0){
                    // table.push(tmp)
                    self::create( array_merge($active_data, [
                        'item_quantity' =>   $tmp,
                        'item_purchase_or_sale_price' => $detail->prix_revient,
                    ]));
                    $detail->quantite_restant -= $tmp;
                    $reste = 0;
                    $detail->save();
                    break;
                }
            }

        }else{
            self::create( $active_data);
        }

        $item_movement_date = now();


        ObrSendInvoince::dispatch();

    }
}



