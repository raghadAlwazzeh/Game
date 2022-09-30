<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Http\Request;

class DailyUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Artisan Command to Update Rolls Count';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        /*$user = User::where('token', "r7JV")->first();
        $user->remain_rolls=$user->rolls_count;
        $user->remain_ads_count=$user->ads_count;
        $user->update();*/
        $users = User::all();
        foreach ($users as $user) {
            $user->remain_rolls=$user->rolls_count;
            $user->remain_ads_count=$user->ads_count;
            $count=$user->days_count;
            if($count==0){
                $user->days_count=30;
                $user->subscribe_plan=0;
                $user->rolls_count=3;
                $user->remain_rolls=3;
                $user->ads_count=3;
                $user->remain_ads_count=3;
            }
            else{
                $user->decrement("days_count");
            }
            
            $user->update();
        }

    }
}
