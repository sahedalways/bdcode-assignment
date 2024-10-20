<?php

namespace App\Livewire\Admin;

use App\Models\Withdrawal;
use Livewire\Component;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Pagination\Cursor;

class Withdrawals extends Component
{
    public $withdrawalsData, $search;
    public $nextCursor;
    protected $currentCursor;
    public $hasMorePages;


    public function mount()
    {
        $this->withdrawalsData = new EloquentCollection();

        $this->loadWithdrawals();
    }



    public function render()
    {
        return view('livewire.admin.withdrawals');
    }


    public function changeWithdrawalStatus($id, $newStatus)
    {
        $item = Withdrawal::find($id);

        if ($item) {
            $item->status = $newStatus;
            $item->save();

            $this->dispatch('toast', message: 'Status updated successfully!', notify: 'success');
        } else {
            $this->dispatch('toast', message: 'Item not found!', notify: 'error');
        }
    }

    public function updated($name, $value)
    {
        if ($name == 'search' && $value != '') {
            $this->withdrawalsData = Withdrawal::with('user')->whereHas('user', function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })->get();
            $this->reloadWithData();
        } elseif ($name == 'search' && $value == '') {

            $this->withdrawalsData = new EloquentCollection();
            $this->reloadWithData();
        }
        /*if the updated element is address */
        if ($name == 'address' && $value != '') {
            $this->address = $value;
        }
    }


    public function loadWithdrawals()
    {
        if ($this->hasMorePages !== null && !$this->hasMorePages) {
            return;
        }
        $dataList = $this->filterData();
        $this->withdrawalsData->push(...$dataList->items());
        if ($this->hasMorePages = $dataList->hasMorePages()) {
            $this->nextCursor = $dataList->nextCursor()->encode();
        }
        $this->currentCursor = $dataList->cursor();
    }




    public function reloadWithData()
    {
        $this->withdrawalsData = new EloquentCollection();
        $this->nextCursor = null;
        $this->hasMorePages = null;
        if ($this->hasMorePages !== null && !$this->hasMorePages) {
            return;
        }
        $data = $this->filterData();
        $this->withdrawalsData->push(...$data->items());
        if ($this->hasMorePages = $data->hasMorePages()) {
            $this->nextCursor = $data->nextCursor()->encode();
        }
        $this->currentCursor = $data->cursor();
    }




    public function filterData()
    {
        if ($this->search || $this->search != '') {
            $data = Withdrawal::with('user')->whereHas('user', function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })->latest()->cursorPaginate(10, ['*'], 'cursor', Cursor::fromEncoded($this->nextCursor));
            return $data;

        } else {

            $data = Withdrawal::with('user')->latest()
                ->cursorPaginate(10, ['*'], 'cursor', Cursor::fromEncoded($this->nextCursor));
            return $data;

        }
    }
}