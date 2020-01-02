<?php

namespace Rockers\PlaidStripe\Http\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;



class PaymentController extends Controller {

    public function pay()
    {
       return view('payment::plaid');
    }

    public function success(Request $request)
    {
       var_dump($request->all());die;
    }


}