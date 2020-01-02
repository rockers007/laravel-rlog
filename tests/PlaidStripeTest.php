<?php

namespace Rockers\PlaidStripe\Test;

use Illuminate\Log\Logger;
use Monolog\Processor\GitProcessor;
use Monolog\Processor\WebProcessor;
use Monolog\Processor\MemoryUsageProcessor;
use Rockers\PlaidStripe\PlaidStripe;
use Cartalyst\Stripe\Laravel\Facades\Stripe;
class PlaidStripeTest extends TestCase
{
    /** @test */
    public function default_test()
    {
         $this->withOutExceptionHandling();
         $config=array();
         $key="sk_test_GfOHdfKo7a1b1BG7uhmUWuJP00NmE4K3IJ";
         $stripe = new Stripe($key, '2019-12-03');

         $pstripe = new PlaidStripe( $stripe);
         $pstripe->init( $config);
       //  var_dump($pstripe);
          $this->assertEquals($pstripe->plaid_env,"sandbox");
            $this->assertEquals($pstripe->stripe_key,$key);

    }

   
    
}
