<?php

namespace App\Livewire\User;

use App\Models\Investment;
use App\Models\Wallet;
use Auth;
use Livewire\Component;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Pagination\Cursor;
use App\Models\VMM as VmmModel;

class VMM extends Component
{
    public $vmmList, $singleVmmData, $user_wallet, $vmm_id, $invest_amount, $title, $lifetime, $user_invest, $minimum_invest, $distribute_coin, $execution_time, $preparation_time, $start_time, $status, $search;

    public $editMode = false;
    public $nextCursor;
    protected $currentCursor;
    public $hasMorePages;



    public function mount()
    {
        $this->vmmList = new EloquentCollection();

        $this->loadVmm();
    }


    public function resetInputFields()
    {
        $this->title = '';
        $this->lifetime = null;
        $this->minimum_invest = null;
        $this->distribute_coin = null;
        $this->execution_time = null;
        $this->preparation_time = null;
        $this->start_time = null;
        $this->status = '';
        $this->user_invest = null;
        $this->vmm_id = null;
        $this->user_wallet = '';
        $this->resetErrorBag();

    }


    public function refresh()
    {
        /* if search query or order filter is empty */
        if ($this->search == '') {
            $this->vmmList = $this->vmmList->fresh();
        }
    }


    public function render()
    {
        return view('livewire.user.v-m-m');
    }




    public function loadVmm()
    {
        if ($this->hasMorePages !== null && !$this->hasMorePages) {
            return;
        }
        $dataList = $this->filterData();
        $this->vmmList->push(...$dataList->items());
        if ($this->hasMorePages = $dataList->hasMorePages()) {
            $this->nextCursor = $dataList->nextCursor()->encode();
        }
        $this->currentCursor = $dataList->cursor();
    }



    public function reloadVmm()
    {
        $this->vmmList = new EloquentCollection();
        $this->nextCursor = null;
        $this->hasMorePages = null;
        if ($this->hasMorePages !== null && !$this->hasMorePages) {
            return;
        }
        $data = $this->filterData();
        $this->vmmData->push(...$data->items());
        if ($this->hasMorePages = $data->hasMorePages()) {
            $this->nextCursor = $data->nextCursor()->encode();
        }
        $this->currentCursor = $data->cursor();
    }


    public function filterData()
    {
        $userFinishedVmm = $this->userHasFinishedInvestments()->pluck('vmm_id');

        if ($this->search || $this->search != '') {
            $data = VmmModel::where('status', '!=', 'draft')->where(function ($query) use ($userFinishedVmm) {
                $query->whereIn('id', $userFinishedVmm)
                    ->orWhere('status', '!=', 'finished');
            })->where('title', 'like', '%' . $this->search . '%')
                ->latest()
                ->cursorPaginate(10, ['*'], 'cursor', Cursor::fromEncoded($this->nextCursor));
            return $data;

        } else {
            $data = VmmModel::where('status', '!=', 'draft')
                ->where(function ($query) use ($userFinishedVmm) {
                    $query->whereIn('id', $userFinishedVmm)
                        ->orWhere('status', '!=', 'finished');
                })
                ->latest()
                ->cursorPaginate(10, ['*'], 'cursor', Cursor::fromEncoded($this->nextCursor));
            return $data;

        }
    }


    public function details($id)
    {
        $this->resetInputFields();


        $this->singleVmmData = VmmModel::where('id', $id)->first();
        $investment = Investment::where('vmm_id', $id)->where('user_id', Auth::id())->first();
        $wallet = Wallet::where('user_id', Auth::id())->first();

        $this->title = $this->singleVmmData->title;
        $this->lifetime = $this->singleVmmData->lifetime;
        $this->minimum_invest = $this->singleVmmData->minimum_invest;
        $this->distribute_coin = $this->singleVmmData->distribute_coin;
        $this->execution_time = $this->singleVmmData->execution_time;
        $this->preparation_time = $this->singleVmmData->preparation_time;
        $this->start_time = $this->singleVmmData->start_time;
        $this->user_invest = $investment->amount ?? 0;
        $this->vmm_id = $this->singleVmmData->id;
        $this->user_wallet = $wallet;
    }



    public function submitInvestment()
    {
        $this->validate([
            'invest_amount' => 'required|integer|min:1',
        ]);

        $userInvestment = Investment::where('user_id', Auth::id())->where('vmm_id', $this->vmm_id)->first();


        if (!$this->user_wallet || $this->user_wallet->taka < $this->invest_amount) {
            return $this->addError('invest_amount', 'You do not have enough balance for investing.');
        }


        if (!$userInvestment) {
            if ($this->singleVmmData->minimum_invest > $this->invest_amount) {
                return $this->addError('invest_amount', 'You need to invest at least minimum amount.');
            }


            Investment::create([
                'user_id' => Auth::id(),
                'vmm_id' => $this->vmm_id,
                'amount' => $this->invest_amount,
            ]);
        } else {
            $userInvestment->amount += $this->invest_amount;
            $userInvestment->save();
        }

        $this->user_wallet->taka -= $this->invest_amount;
        $this->user_wallet->save();


        $this->resetInputFields();
        $this->dispatch('toast', message: 'You have invested on this VMM.', notify: 'success');
        $this->dispatch('closemodal');

    }




    protected function userHasFinishedInvestments()
    {
        return Investment::where('user_id', Auth::id())
            ->where('status', 'finished')
            ->get();
    }

}