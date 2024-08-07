<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log; // Add this line to import the Log class
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\User;



class AppUninstalledJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */

    protected $domain;
    protected $data;

    public function __construct($domain, $data=[])
    {
        $this->domain = $domain;
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('AppUninstalledJob-------------', ['domain' => $this->domain, 'data' => $this->data]);
        //update password to null in database user table
        $user = User::where('name', $this->domain)->first();
        if($user){
            //log user fount in database
            Log::info('AppUninstalledJob - user exists ------------', ['user' => $user]);
            $user->password = '';
            $user->save();
        } else {
            //log user not found in database
            Log::info('AppUninstalledJob - user not found ------------', ['user' => $user]);
        }

        
    }
}
