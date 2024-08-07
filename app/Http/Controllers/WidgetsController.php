<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Widget;

//use job
use App\Jobs\WidgetStyleMetafieldJob;

class WidgetsController extends Controller
{
    public function index(Request $request)
    {
        $authShop = User::where('name', $request->shop)->first();

        // dump($authShop->widget);

        $data = $this->returnData($authShop);

        if($data['widgets'] == null) {
            $data['widgets']['styles'] = $styleData;
        }

        // dd($data);

        // dump($data);
        return view('widgets.index', $data);
    }

    public function returnData($shop) {
        $widgets = $shop->widget;
        $domain = $shop->name;

        $styleData = [
            "regular_checkout_btn" => [
                "background_color" => "#000000",
                "text_color" => "#ffffff",
                "border_color" => "5px",
                "border_width" => "5px",
                "border_radius" => "5px",
                "font_size" => "16px",
                "font_weight" => "bold",
                "padding_inline" => "20px",
                "padding_block" => "10px",
                "margin_inline" => "0px",
                "margin_block" => "0px",
                "height" => "40px",
            ],
            "protection_checkout_btn" => [
                "background_color" => "#000000",
                "text_color" => "#ffffff",
                "border_color" => "5px",
                "border_width" => "5px",
                "border_radius" => "5px",
                "font_size" => "16px",
                "font_weight" => "bold",
                "padding_inline" => "20px",
                "padding_block" => "10px",
                "margin_inline" => "0px",
                "margin_block" => "0px",
                "height" => "40px",
            ]
        ];

        // dump($widgets);
        if($widgets == null) {
            $widgets = Widget::updateOrCreate(
                ['user_id' => $shop->id, 'user_id' => $shop->id],
                ['styles' => $styleData]
            );
            WidgetStyleMetafieldJob::dispatch($shop);
            // dump($widgets);
        }

        // WidgetStyleMetafieldJob::dispatch($shop);

        return compact('widgets', 'domain');
        
    }
}
