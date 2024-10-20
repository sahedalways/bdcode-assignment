<?php

namespace App\Livewire\Admin;

use App\Models\Investment;
use Livewire\Component;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Pagination\Cursor;

class Investments extends Component
{
    public $investmentsData, $search;
    public $nextCursor;
    protected $currentCursor;
    public $hasMorePages;


    public function mount()
    {
        $this->investmentsData = new EloquentCollection();

        $this->loadInvestments();
    }


    public function render()
    {
        return view('livewire.admin.investments');
    }





    public function updated($name, $value)
    {
        if ($name == 'search' && $value != '') {
            $this->investmentsData = Investment::with(['user', 'vmm'])->whereHas('user', function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
                ->orWhereHas('v_m_m_s', function ($query) {
                    $query->where('title', 'like', '%' . $this->search . '%');
                })
                ->get();
            $this->reloadInvestmentData();
        } elseif ($name == 'search' && $value == '') {

            $this->investmentsData = new EloquentCollection();
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
        $this->investmentsData->push(...$dataList->items());
        if ($this->hasMorePages = $dataList->hasMorePages()) {
            $this->nextCursor = $dataList->nextCursor()->encode();
        }
        $this->currentCursor = $dataList->cursor();
    }


    public function reloadInvestmentData()
    {
        $this->investmentsData = new EloquentCollection();
        $this->nextCursor = null;
        $this->hasMorePages = null;
        if ($this->hasMorePages !== null && !$this->hasMorePages) {
            return;
        }
        $data = $this->filterData();
        $this->investmentsData->push(...$data->items());
        if ($this->hasMorePages = $data->hasMorePages()) {
            $this->nextCursor = $data->nextCursor()->encode();
        }
        $this->currentCursor = $data->cursor();
    }




    public function filterData()
    {
        if ($this->search || $this->search != '') {
            $data = Investment::with(['user', 'vmm'])->whereHas('user', function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
                ->orWhereHas('v_m_m_s', function ($query) {
                    $query->where('title', 'like', '%' . $this->search . '%');
                })
                ->latest()->cursorPaginate(10, ['*'], 'cursor', Cursor::fromEncoded($this->nextCursor));
            return $data;

        } else {

            $data = Investment::with(['user', 'vmm'])->latest()
                ->cursorPaginate(10, ['*'], 'cursor', Cursor::fromEncoded($this->nextCursor));
            return $data;

        }
    }
}