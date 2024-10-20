<?php

namespace App\Livewire\User;

use App\Models\Wallet;
use App\Models\Withdrawal;
use Auth;
use Livewire\Component;



class Balance extends Component
{

    public $coin_balance, $userWallet, $balance, $amount, $search;



    public function mount()
    {
        $this->loadWallet();
    }

    public function render()
    {
        return view('livewire.user.balance');
    }




    // public function sendWithdrawRequest()
    // {
    //     $this->validate([
    //         'amount' => 'required|integer|min:1',
    //     ]);


    //     if (!$this->userWallet || $this->userWallet->vmm_coin < $this->amount) {
    //         return $this->addError('amount', 'The withdrawal amount exceeds your current balance.');
    //     }


    //     Withdrawal::create([
    //         'user_id' => Auth::id(),
    //         'amount' => $this->amount,
    //         'status' => 'pending',
    //     ]);

    //     $this->amount = null;
    //     $this->dispatch('toast', message: 'Withdraw request sent.', notify: 'success');
    // }


    public function loadWallet()
    {

        $this->userWallet = Wallet::where('user_id', Auth::id())->first();
        $this->coin_balance = $this->userWallet->vmm_coin ?? 0;
        $this->balance = $this->userWallet->taka ?? 0;

    }

}