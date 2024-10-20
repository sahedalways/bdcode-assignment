<?php

namespace App\Livewire\User;


use App\Models\Withdrawal;
use Auth;
use Livewire\Component;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Pagination\Cursor;

class WithdrawHistory extends Component
{

    public $historyData;
    public $nextCursor;
    protected $currentCursor;
    public $hasMorePages;


    public function mount()
    {
        $this->historyData = new EloquentCollection();

        $this->loadWithHistory();
    }
    public function render()
    {
        return view('livewire.user.withdraw-history');
    }




    public function loadWithHistory()
    {
        if ($this->hasMorePages !== null && !$this->hasMorePages) {
            return;
        }
        $dataList = $this->filterData();
        $this->historyData->push(...$dataList->items());
        if ($this->hasMorePages = $dataList->hasMorePages()) {
            $this->nextCursor = $dataList->nextCursor()->encode();
        }
        $this->currentCursor = $dataList->cursor();
    }




    public function filterData()
    {
        $data = Withdrawal::where('user_id', Auth::id())->latest()
            ->cursorPaginate(10, ['*'], 'cursor', Cursor::fromEncoded($this->nextCursor));
        return $data;


    }
}