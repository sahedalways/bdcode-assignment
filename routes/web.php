<?php

use App\Livewire\Admin\Investments;
use App\Livewire\Admin\VmmManagemnt;
use App\Livewire\Admin\Withdrawals;
use App\Livewire\Login;
use App\Livewire\Admin\Dashboard;
use App\Livewire\User\Dashboard as UserDashbaord;
use App\Livewire\User\InvestHistory;
use App\Livewire\User\VMM as UserVmm;
use App\Livewire\User\WinningHistory;
use App\Livewire\User\WithdrawHistory;
use App\Livewire\User\Balance;
use Illuminate\Support\Facades\Route;

// login route
Route::get('/', [Login::class, '__invoke'])->name('login');


// for admin
Route::group(['prefix' => 'admin', 'middleware' => 'admin'], function () {
    /* Admin Dashboard */
    Route::get('dashboard', Dashboard::class)->name('admin.dashboard');

    // vmm management route
    Route::get('vmm', VmmManagemnt::class)->name('admin.vmm');

    // withdrawals management route
    Route::get('manage-widthdrawals', Withdrawals::class)->name('admin.manage-widthdrawals');

    // investments management route
    Route::get('invests', Investments::class)->name('admin.invests');
});


// for users
Route::group(['prefix' => 'dashboard', 'middleware' => 'user'], function () {
    /* Admin Dashboard */
    Route::get('/', UserDashbaord::class)->name('dashboard');

    // user vmm route
    Route::get('vmm', UserVmm::class)->name('dashboard.vmm');

    // user withdraw history route
    Route::get('withdraw-history', WithdrawHistory::class)->name('dashboard.withdraw-history');


    // user invest history route
    Route::get('invest-history', InvestHistory::class)->name('dashboard.invest-history');


    // user balance request route
    Route::get('balance', Balance::class)->name('dashboard.balance');


    // user winninf history route
    Route::get('win-history', WinningHistory::class)->name('dashboard.win-history');
});