<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

//import model user
use App\Models\User;

//import helper
use App\Helper\DeleteExistsVariant;

use Illuminate\Support\Facades\Log; // Add this line to import the Log class

class ExistingVariantDeleteJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $domain;
    protected $variant_id;

    public function __construct($domain, $variant_id)
    {
        $this->domain = $domain;
        $this->variant_id = $variant_id;
    }


    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //create order on shopify store name Order Protection using rest api
       try{
        $domain = $this->domain;
        $variant_id = $this->variant_id;


        //authShop
        $authShop = User::where('name', $domain)->first();
        DeleteExistsVariant::deleteExistsVariant($authShop, $variant_id);
       } catch (\Exception $e) {
           Log::error('ExistingVariantDeleteJob Error', ['error' => $e->getMessage()]);
       }
    }
}
