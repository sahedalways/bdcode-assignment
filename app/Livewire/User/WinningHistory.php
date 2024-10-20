<?php

namespace App\Livewire\User;

use Auth;
use Livewire\Component;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Pagination\Cursor;

class WinningHistory extends Component
{


    public $historyData;
    public $nextCursor;
    protected $currentCursor;
    public $hasMorePages;

    public function mount()
    {
        $this->historyData = new EloquentCollection();

        $this->loadWinHistory();
    }


    public function render()
    {
        return view('livewire.user.winning-history');
    }



    public function loadWinHistory()
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
        $data = \App\Models\WinningHistory::with(['user', 'vmm'])->where('user_id', Auth::id())->latest()
            ->cursorPaginate(10, ['*'], 'cursor', Cursor::fromEncoded($this->nextCursor));
        return $data;


    }
}