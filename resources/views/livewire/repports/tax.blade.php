<div>
    {{-- If you look to others for fulfillment, you will never truly be fulfilled. --}}


   <div class="d-flex gap-3">
    <span>DU</span>
    <input type="date" class="form-control  form-control-sm" wire:model="startDate">
    <span>Au</span>
    <input type="date" class="form-control form-control-sm" wire:model="endDate">
    <button class="btn btn-info btn-sm">
        Ok
    </button>
   </div>

   <div class="card">
    <p>Total du TVA Collect dans la periode {{ $startDate  }} Au {{ $endDate }}</p>
    <h2>{{ getPrice($amountTax ) }} #FBU</h2>
    <p>Nombre Total des Factures <b>{{ $totalFacture }}</b></p>
   </div>
</div>
