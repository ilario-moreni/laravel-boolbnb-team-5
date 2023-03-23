<?php

namespace App\Http\Controllers\Admin;


use Illuminate\Support\Facades\Storage;
//Richiamo il Model
use App\Models\Apartment;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreApartmentRequest;
use App\Http\Requests\UpdateApartmentRequest;
use Illuminate\Support\Facades\Storage;

class ApartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $apartments = Apartment::all();
        return view('admin.apartments.index', compact('apartments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        /* indirizzamento alla pagina di creazione di un nuovo apartment */
        return view('admin.apartments.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreApartmentRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreApartmentRequest $request)
    {
        /* recupero dati validati */
        $form_data = $request->validated();

        /* controllo e salvataggio dell'immagine */
        if($request->has('image')){
            $path = Storage::disk('public')->put('apartment_images', $request->image);
            
            $form_data['image'] = $path;
        }
        
        /* generazione e assegnazione slug */
        $slug = Apartment::generateSlug($request->title);
        $form_data['slug'] = $slug;

        /* creazione riempimento e salvataggio istanza di apartment */
        $newApartment = new Apartment();
        $newApartment->fill($form_data);
        $newApartment->save();
        
        /* reindirizzamento alla pagina index una volta completate le operazioni precedenti */
        return redirect()->route('admin.apartments.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Apartment  $apartment
     * @return \Illuminate\Http\Response
     */
    public function show(Apartment $apartment)
    {
        /* indirizzamento alla pagina di visualizzazione del un nuovo apartment */
        return view('admin.apartments.show', compact('apartment'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Apartment  $apartment
     * @return \Illuminate\Http\Response
     */
    public function edit(Apartment $apartment)
    {
        return view('admin.apartments.edit', compact('apartment'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateApartmentRequest  $request
     * @param  \App\Models\Apartment  $apartment
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateApartmentRequest $request, Apartment $apartment)
    {
        //VIENE VALIDATO IL FORM INVIATO DALL'UTENTE ATTRAVERSO LA CLASSE "UpdateApartmentRequest" CHE CONTROLLA CHE I DATI SIANO CORRETTI E COERENTI CON LE REGOLE DI VALIDAZIONE DEFINITE
        $form_data = $request->validated();

        //VIENE GENERATO UNO "slug" UNIVOCO PER L'APPARTAMENTO UTILIZZANDO IL METODO STATICO "generateSlug()" NEL MODELLO "Apartment".
        $slug = Apartment::generateSlug($request->title, '-');

        $form_data['slug'] = $slug;
        if ($request->hasFile('image')) {
            Storage::delete($apartment->image);
        }

        $path = Storage::disk('public')->put('apartment_images', $request->image);
        $form_data['image'] = $path;

        $apartment->update($form_data);

        return redirect()->route('admin.apartments.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Apartment  $apartment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Apartment $apartment)
    {
        //Elimino il progetto specificato
        $apartment->delete();

        //Reindirizza alla pagina index.
        return redirect()->route('admin.apartments.index');
    }
}
