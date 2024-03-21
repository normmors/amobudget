<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class AmoCrmHookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->all();
        dump($payload);
        Storage::put('amocrm/payload.json', json_encode($payload));
        return response()->json(['status' => 'success']);
    }
}
