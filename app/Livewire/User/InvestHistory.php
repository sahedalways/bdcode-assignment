<?php

namespace App\Livewire\User;

use App\Models\Investment;
use Auth;
use Livewire\Component;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Pagination\Cursor;

class InvestHistory extends Component
{

    public $investData, $search;
    public $nextCursor;
    protected $currentCursor;
    public $hasMorePages;


    public function mount()
    {
        $this->investData = new EloquentCollection();

        $this->loadInvestments();
    }
    public function render()
    {
        return view('livewire.user.invest-history');
    }


    public function updated($name, $value)
    {
        if ($name == 'search' && $value != '') {
            $this->investData = Investment::with('vmm')->where('user_id', Auth::id())->whereHas('vmm', function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%');
            })
                ->get();
            $this->reloadInvestmentData();
        } elseif ($name == 'search' && $value == '') {

            $this->investData = new EloquentCollection();
            $this->reloadInvestmentData();
        }
        /*if the updated element is address */
        if ($name == 'address' && $value != '') {
            $this->address = $value;
        }
    }


    public function loadInvestments()
    {
        if ($this->hasMorePages !== null && !$this->hasMorePages) {
            return;
        }
        $dataList = $this->filterData();
        $this->investData->push(...$dataList->items());
        if ($this->hasMorePages = $dataList->hasMorePages()) {
            $this->nextCursor = $dataList->nextCursor()->encode();
        }
        $this->currentCursor = $dataList->cursor();
    }


    public function reloadInvestmentData()
    {
        $this->investData = new EloquentCollection();
        $this->nextCursor = null;
        $this->hasMorePages = null;
        if ($this->hasMorePages !== null && !$this->hasMorePages) {
            return;
        }
        $data = $this->filterData();
        $this->investData->push(...$data->items());
        if ($this->hasMorePages = $data->hasMorePages()) {
            $this->nextCursor = $data->nextCursor()->encode();
        }
        $this->currentCursor = $data->cursor();
    }




    public function filterData()
    {
        if ($this->search || $this->search != '') {
            $data = Investment::with('vmm')->where('user_id', Auth::id())->whereHas('vmm', function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%');
            })
                ->latest()->cursorPaginate(10, ['*'], 'cursor', Cursor::fromEncoded($this->nextCursor));
            return $data;

        } else {

            $data = Investment::with('vmm')->where('user_id', Auth::id())->latest()
                ->cursorPaginate(10, ['*'], 'cursor', Cursor::fromEncoded($this->nextCursor));
            return $data;

        }
    }
}