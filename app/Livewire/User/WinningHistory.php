<?php

namespace App\Livewire\User;

use App\Models\Withdrawal;
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





    public function withdraw($id)
    {
        $item = \App\Models\WinningHistory::where('id', $id)->first();


        Withdrawal::create([
            'user_id' => Auth::id(),
            'amount' => $item->coins,
            'status' => 'pending',
        ]);

        $item->delete();

        $this->historyData = new EloquentCollection();

        $this->loadWinHistory();

        $this->dispatch('toast', message: 'Withdraw request sent.', notify: 'success');
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
        $data = \App\Models\WinningHistory::with('vmm')->where('user_id', Auth::id())->latest()
            ->cursorPaginate(10, ['*'], 'cursor', Cursor::fromEncoded($this->nextCursor));
        return $data;


    }

}