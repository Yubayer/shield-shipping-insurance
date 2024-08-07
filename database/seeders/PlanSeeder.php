<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;
use Osiset\ShopifyApp\Storage\Models\Plan;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Plan::truncate();

        $plansData = [
            [
                "type" => "RECURRING",
                "name" => "Starter",
                "price" => 24.99,
                "interval" => "EVERY_30_DAYS",
                "capped_amount" => 1000,
                "terms" => "Extra charges are applied based on Protection Revenue",
                "test" => true,
                "trial_days" => 7,
                "on_install" => false,
                "is_fixed_plan" => true,
            ],  
            // [
            //     "type" => "RECURRING",
            //     "name" => "Gold",
            //     "price" => 49.99,
            //     "interval" => "EVERY_30_DAYS",
            //     "capped_amount" => 2000,
            //     "terms" => "Extra charges are applied based on Protection Revenue",
            //     "test" => true,
            //     "trial_days" => 7,
            //     "on_install" => false,
            //     "is_fixed_plan" => true,
            // ],  
            // [
            //     "type" => "RECURRING",
            //     "name" => "Platinum",
            //     "price" => 99.99,
            //     "interval" => "EVERY_30_DAYS",
            //     "capped_amount" => 5000,
            //     "terms" => "Extra charges are applied based on Protection Revenue",
            //     "test" => true,
            //     "trial_days" => 7,
            //     "on_install" => false,
            //     "is_fixed_plan" => true,
            // ],
        ];

        foreach ($plansData as $planData) {
            $plan = new Plan();
            $plan->fill($planData);

            $plan->save();
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
