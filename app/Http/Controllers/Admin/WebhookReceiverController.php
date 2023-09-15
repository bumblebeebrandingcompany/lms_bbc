<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Source;
use App\Utils\Util;
use Symfony\Component\HttpFoundation\Response;

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
        $source = Source::where('webhook_secret', $secret)
                    ->firstOrFail();
                    
        if(!empty($source) && !empty($request->all())) {
            $response = $this->util->createLead($source, $request->all());
            return response()->json($response['msg']); 
        }
        
    }

    public function incomingWebhookList(Request $request)
    {
        abort_if(!auth()->user()->is_superadmin, Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.webhook.index');
    }
}
