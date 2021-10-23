<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Type;
use Livewire\WithPagination;

class types extends Component
{
    use WithPagination;

    //public $types;
    public $search;
    public $isOpen = 0;
    public $typeId, $type, $bentuk_obat;
    public $confirmingTypeDeletion = false;
    public function render()
    {
        $searchParams = '%' . $this->search . '%';
        //$this->types = type::all();
        return view('livewire.types', [
            'types' => Type::where('type', 'like', $searchParams)->latest()
                ->orWhere('bentuk_obat', 'like', $searchParams)->latest()->paginate(5)
        ]);
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
        $this->validate(
            [
                'type' => 'required',
                'bentuk_obat' => 'required',
            ]
        );

        Type::updateOrCreate(['id' => $this->typeId], [
            'type' => $this->type,
            'bentuk_obat' => $this->bentuk_obat,
        ]);

        $this->hideModal();

        session()->flash('types', $this->typeId ? 'type Update Successfully' : 'type Created Successfully');

        $this->typeId = '';
        $this->type = '';
        $this->benuk_obat = '';
    }

    public function edit($id)
    {
        $type = Type::findOrFail($id);
        $this->typeId = $id;
        $this->type = $type->type;
        $this->bentuk_obat = $type->buah_obat;


        $this->showModal();
    }

    public function delete($id)
    {
        Type::find($id)->delete();
        $this->confirmingTypeDeletion = false;
        session()->flash('delete', 'type Deleted Successfully');
    }

    public function confirmTypeDeletion($id)
    {
        $this->confirmingTypeDeletion = $id;
    }
}
