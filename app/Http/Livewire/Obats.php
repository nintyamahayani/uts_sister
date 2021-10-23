<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Obat;
use App\Models\Type;
use Livewire\WithPagination;

class Obats extends Component
{
    use WithPagination;
    public $search;
    //public $mobils;
    public $obatId, $obat, $type, $harga;
    public $isOpen = 0;
    public function render()
    {
        $types = Type::all();
        $this->obats = Obat::with('type');
        $searchParams = '%' . $this->search . '%';
        //$this->mobils = Mobil::all();
        return view('livewire.obats', [
            'obats' => Obat::where('obat', 'like', $searchParams)->latest()
                ->orWhere('type', 'like', $searchParams)->latest()->paginate(5)
        ], compact('types'));
    }

    public function showModal()
    {
        $this->isOpen = true;
    }

    public function hideModal()
    {
        $this->isOpen = false;
    }

    public function store()
    {


        $types = Type::all();

        $this->validate(
            [
                'obat' => 'required',
                'type' => 'required',
                'harga' => 'required',
            ]
        );

        Obat::updateOrCreate(['id' => $this->obatId], [
            'obat' => $this->obat,
            'type' => $this->type,
            'harga' => $this->harga,
        ]);

        $this->hideModal();

        session()->flash('info', $this->obatId ? 'Obat Update Successfully' : 'Post Created Successfully');

        $this->obatId = '';
        $this->obat = '';
        $this->type = '';
        $this->harga = '';
    }

    public function edit($id)
    {
        $obats = Obat::findOrFail($id);
        $this->obatId = $id;
        $this->obat = $obat->obat;
        $this->type = $obat->type;
        $this->harga = $obat->harga;

        $this->showModal();
    }

    public function delete($id)
    {
        Obat::find($id)->delete();
        session()->flash('delete', 'Obat Deleted Successfully');
    }
}
