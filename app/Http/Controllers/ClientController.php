<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Compte;
use Illuminate\Http\Request;
class ClientController extends Controller
{

    public function index()
    {
        $clients = Client::latest()->paginate(20);

        return view('clients.index', compact('clients'));
    }

    public function abonne($id){
        $customer = Client::find($id);

        $compte = Compte::where('client_id' , $customer->id)->first();

        if(!$compte){
            Compte::create([
                'name' => str_pad($customer->id, 4, '0', STR_PAD_LEFT),
                'montant' => 0,
                'is_active' => true,
                'client_id' => $customer->id
            ]);
        }
        return back();
    }


    public function create()
    {
        return view('clients.create');
    }

    public function getClient($id){
        $client = Client::find($id);

        return response()->json( [
            'client' => $client
        ]);
    }


    public function store(Request $request)
    {

        $request->validate([
            "client_type" => "required",
            "vat_customer_payer" => "required",
            "name" => "required",
            "customer_TIN" => "sometimes|unique:clients,id",
            "telephone" => "nullable",
            "addresse" => "nullable"
        ]);
        // Check if Tin does not exist in database

        if($request->client_type === 'PERSONNE MORAL' && $request->customer_TIN ==""){
            return redirect('clients/create')->with('message', 'NIF EST OBLIGATOIRE POUR LES PERSONNES MORALE ');
        }
        $customer_OBR = '';
        if($request->customer_TIN){
            $check =  Client::where("customer_TIN", $request->customer_TIN)->first();
            // dd( $check);
            if($check){
                $errorMessage = 'Le Client existe deja  '. $request->customer_TIN . ' => '.  $check->name . ' CUSTOMER ID '. $check->id;
                return redirect('clients/create')->with('message',  $errorMessage);
            }
            try {
                $obr = new SendInvoiceToOBR();
                $response = $obr->checkTin($request->customer_TIN);
                if(!$response->success){

                    return redirect('clients/create')->with('message',  $request->customer_TIN . ' => '. $response->msg);

                }
                // }else{

                    //     return redirect('clients/create')->with('message',  ' NIF EST OBLIGATOIRE POUR LES PERSONNES MORALE ');
                    // }

                    // ['result']['taxpayer'][0]['tp_name']
                    $customer_OBR = $response->result->taxpayer[0]->tp_name;

                }catch (\Exception $e){

                    return redirect('clients/create')->with('message',  $request->customer_TIN . ' => pas de connection Internet le Nif ne peut pas etre verfier pour le moment ');
                }

            }
            $data =  $request->all();
            if($customer_OBR){
                $data = array_merge($data, [
                    'name' => $customer_OBR
                ]);
            }

            Client::create( $data );
            return $this->index();
        }

        /**
        * Display the specified resource.
        *
        * @param  \App\Models\Client  $client
        * @return \Illuminate\Http\Response
        */
        public function show(Client $client)
        {
            //
        }

        /**
        * Show the form for editing the specified resource.
        *
        * @param  \App\Models\Client  $client
        * @return \Illuminate\Http\Response
        */
        public function edit(Client $client)
        {
            //

            return view('clients.edit', compact('client'));
        }

        /**
        * Update the specified resource in storage.
        *
        * @param  \Illuminate\Http\Request  $request
        * @param  \App\Models\Client  $client
        * @return \Illuminate\Http\Response
        */
        public function update(Request $request, Client $client)
        {
            //

            $request->validate([
                'first_name' => 'required',
            ]);

            $client->update($request->all());

            return $this->index();
        }

        /**
        * Remove the specified resource from storage.
        *
        * @param  \App\Models\Client  $client
        * @return \Illuminate\Http\Response
        */
        public function destroy(Client $client)
        {
            $client->delete();
        }
    }
