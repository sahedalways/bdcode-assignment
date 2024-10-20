<?php

namespace App\Console\Commands;

use App\Models\Investment;
use App\Models\VMM;
use App\Models\Wallet;
use App\Models\WinningHistory;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DistributeCoin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'coin:distribute';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Distribute coins at every execution time';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $vmmList = VMM::all();

        foreach ($vmmList as $vmm) {
            $preparationTime = $vmm->preparation_time;
            $lifetime = $vmm->lifetime;

            $now = Carbon::now('Asia/Dhaka');
            $startTime = Carbon::parse($vmm->start_time);

            $updatePreparationTime = $startTime->copy()->subMinutes($preparationTime);
            $endTime = $startTime->copy()->addMinutes($lifetime);


            if ($now->gte($endTime)) {
                $this->info('Operation executed after 60 minutes from start time.');
            }


            if ($now == $startTime) {
                $totalCoins = $vmm->distribute_coin;
                $executionTime = $vmm->execution_time;
                $vmm->status = 'running';
                $vmm->save();

                $coinDistribution = $this->distributeCoins($totalCoins, $executionTime);


                $investments = Investment::where('vmm_id', $vmm->id)->get();

                $totalInvestments = $investments->count();

                foreach ($coinDistribution as $index => $coinsToAdd) {
                    $investment = $investments[$index % $totalInvestments];


                    $wallet = Wallet::where('user_id', $investment->user_id)->first();
                    if ($wallet) {
                        $wallet->coin += $coinsToAdd;
                        $wallet->save();
                    }

                    WinningHistory::create([
                        'user_id' => $investment->user_id,
                        'vmm_id' => $vmm->id,
                        'coins' => $$coinsToAdd,
                    ]);

                }

            }

            if ($now->gte($updatePreparationTime)) {
                $vmm->status = 'in_preparation';
                $vmm->save();
            }
        }



        $this->info('Coins have been distributed successfully!');
    }


    function distributeCoins($totalCoins, $executionTime)
    {
        $coinsPerSecond = floor($totalCoins / $executionTime);
        $remainingCoins = $totalCoins - ($coinsPerSecond * $executionTime);

        $distribution = array_fill(0, $executionTime, $coinsPerSecond);

        while ($remainingCoins > 0) {
            $randomSecond = rand(0, $executionTime - 1);
            $distribution[$randomSecond]++;
            $remainingCoins--;
        }

        return $distribution;
    }


    function cloneVMM()
    {
        $vmms = VMM::where('status', 'running')
            ->where('start_time', '<=', Carbon::now()->subSeconds('execution_time'))
            ->get();

        foreach ($vmms as $vmm) {
            $vmm->status = 'finished';
            $vmm->save();

            VMM::create([
                'title' => $vmm->title,
                'lifetime' => $vmm->lifetime,
                'minimum_invest' => $vmm->minimum_invest,
                'distribute_coin' => $vmm->distribute_coin,
                'execution_time' => $vmm->execution_time,
                'preparation_time' => $vmm->preparation_time,
                'start_time' => Carbon::now()->addMinutes($vmm->lifetime),
                'status' => 'draft',
            ]);
        }
    }
}