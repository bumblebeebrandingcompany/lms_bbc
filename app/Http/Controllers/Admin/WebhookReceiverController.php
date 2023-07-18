<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Campaign;
use App\Utils\Util;

class WebhookReceiverController extends Controller
{
    /**
    * All Utils instance.
    *
    */
    protected $util;

    /**
    * Constructor
    *
    */
    public function __construct(Util $util)
    {
        $this->util = $util;
    }

    public function processor(Request $request, $secret)
    {
        $campaign = Campaign::where('webhook_secret', $secret)
                        ->firstOrFail();

        if(!empty($campaign) && !empty($request->all())) {
            $this->util->createLead($campaign, $request->all());
        }
        
        return response()->json('ok'); 
    }
}
