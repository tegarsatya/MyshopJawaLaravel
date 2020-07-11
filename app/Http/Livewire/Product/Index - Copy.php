<?php

namespace App\Http\Livewire\Product;

use App\Product;
use Livewire\Component;
use Livewire\WithPagination;


class Index extends Component
{
    use WithPagination;

    public $paginate = 10;
    public $search;
    public $formVisible;

    protected $updateQueryString = [
        ['search'   => ['except ' => '']],
    ];

    protected $listeners = [
        'formClose' => 'formCloseHadler',
        'productStored' => 'productStoredHandler',
        'productUpdated' => 'productUpdatedHandler'
    ];

    public function mount()
    {
        $this->search = request()->query('search', $this->search);
    }

    public function render()
    {
        return view('livewire.product.index',[
            'products'   => $this->search === null ?
                Product::latest()->paginate($this->paginate) :
                Product::latest()->where('title', 'like', '%' .$this->search. '%')
                                 ->paginate($this->paginate)
        ]);
    }

    public function formCloseHadler()
    {
        $this->formVisible = false;
    }

    public function productStoredHandler()
    {
        $this->formVisible = false;
        session()->flash('message', 'Your product was stored');
    }

    public function productUpdatedHandler()
    {
        $this->formVisible = false;
        session()->flash('message', 'Your product was updated');
    }
}
