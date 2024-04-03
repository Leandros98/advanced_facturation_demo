<?php

namespace App\Http\Livewire\Stocks;

use App\Models\Product;
use App\Models\Stocke;
use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class ProductStockComponent extends Component
{
    use LivewireAlert;
    public $stock ;
    public $searchProduct ;
    public $searchStockProduct;
    public $products;
    public $pendingProducts;

    public function mount($stock){
        $this->stock = Stocke::with('products')->find($stock);
    }
    public function render()
    {
        $this->getProducts();
        return view('livewire.stocks.product-stock-component');
    }



    public function getProducts(){

        $this->products = $this->stock->products()->where('products.name', 'like', '%' . $this->searchProduct . '%')->get();
        $this->pendingProducts = Product::whereNotIn('id', $this->stock->products->pluck('id'))->get();
    }

    public function addProduct($id){
        try{
            $this->stock->products()->attach($id, [
                'quantity' => 0,
                'prix_revient' => 0,
                'prix_vente' => 0,
                'user_id' => auth()->user()->id
            ]);
            $this->getProducts();
            $this->alert('success', 'Le produit a été bien ajouté');
        }catch(\Exception $e){
            $this->alert('warning', $e->getMessage(),[
                'position' => 'center',
                'timer' => 3000,
                'toast' => true,
                'width' => '500',
            ]);
        }


    }



}
