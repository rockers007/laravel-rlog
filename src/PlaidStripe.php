<?php 
namespace Rockers\PlaidStripe;
use Cartalyst\Stripe\Laravel\Facades\Stripe;
class PlaidStripe 
{
	public $currency_code;
	public $gateway_id;
	public $amount;
	public $plaid_env;
	public $plaid_client_id;
	public $plaid_public;
	public $plaid_secret;
	public $plaid_url;
	public $CI;
	public $stripe_key;
	public $callback_url;
	public $webhook_url;
	public $email;
	public $description;
    public $customer_name;
    public $stripe;

	function __construct(Stripe $stripe) 
	{
		$this->stripe= $stripe;
	}
	/**
 * initilize all stripe required parameters
 */
    public function init($configure)
    {
    	try
    	{   
    		$this->plaid_env = isset($configure['plaid_env'])?$configure['plaid_env']:config('credentials.plaid_env');
	        $this->plaid_client_id =isset($configure['plaid_client_id'])?$configure['plaid_client_id']:config('credentials.plaid_client_id');
	        $this->plaid_public  =isset($configure['plaid_public'])?$configure['plaid_public']:config('credentials.plaid_public');
	        $this->plaid_secret  = isset($configure['plaid_secret'])?$configure['plaid_secret']:config('credentials.plaid_secret');
	        $this->plaid_url  =  isset($configure['plaid_url'])?$configure['plaid_url']:config('credentials.plaid_url');
	        $this->stripe_key  =  isset($configure['stripe_key'])?$configure['stripe_key']:config('credentials.stripe_key');
            $this->currency_code  = isset($configure['currency_code'])?$configure['currency_code']:config('credentials.currency_code');
	        if (!in_array($this->currency_code, array('JPY','NZD','BIF', 'DJF', 'KRW', 'PYG', 'VND', 'XAF','XPF',
            'CLP',  'GNF',  'KMF',  'MGA', 'RWF',   'VUV',   'XOF'  )))
	        {
	            $this->amount =$this->amount  * 100;
	        }

            return true;
    	}catch (Exception $e)
        {
            return $e->getMessage();
        }
    	
    }/**
 * stripe credit card form generation code
 */
    public function success()
     {
     	 $data['status']  = 'fail';$data['message'] ='';

       try {
       	
            $description= $this->description;
            $email= $this->email;
       		$customer_id = $_POST['id'];
      		$metadata = $_POST['metadata'];
      		//Exchange public token for Plaid access_token
      		$banktoken = $this->get_plaid_token($_POST['token']);
            if(!isset($banktoken->stripe_bank_account_token))
            {
                throw new Exception('Invalid Bank');
            }
      		//print_r($banktoken);die;
           // \Stripe\Stripe::setApiKey( $this->stripe_key);
           
            //new token
             $token=$banktoken->stripe_bank_account_token;

              $customer = $stripe->customers()->create(array(
                'email' => $email,
                'source' => $token
            ));
             $stripe_customer_id = $customer->id;
              $source = false;
             $token_data = $stripe->token()->retrieve( $token );
		    //print_r( $token_data);
		    $this_bank_account = $token_data['bank_account'];
		    $cust_banks = $customer['sources']['data'];

		    foreach ( $cust_banks as $bank ) {
		      if ( $bank['fingerprint'] == $this_bank_account['fingerprint'] ) {
		        $source = $bank['id'];
		      }
		    }

		    // If this bank is not an existing one, we'll add it.
		    if ( $source === false ) {
		      $new_source = $customer->sources->create( array( 'source' => $token ) );
		      $source = $new_source['id'];
		    }
		   // $stripe_account='acct_17nRdpDG4QepoXp5';
		    // Try to authorize the bank.
		    $charge_args = array(
		      'amount' => $this->amount,
		      'currency' => $this->configure->currency_code,
		      'description' => $description,
		      'customer' => $stripe_customer_id,
		      'source' => $source,
		    );
		     $charge   =$stripe->charge()->create( $charge_args);
            //$charge   = \Stripe\Charge::create( $charge_args, ["stripe_account" => $stripe_account]);
            if ($charge->captured == true) {
                $data['status']  = 'success';
                $data['message'] = $charge->id;
            } else {
                $data['message'] = $charge->failure_message;
            }
       		 $data['response'] =  json_encode($charge);
        } catch (Exception $e) {

		      // Something else happened, completely unrelated to Stripe.
		      $return =$e->getMessage();

		    }
		    if (isset($return)) {
                $ex_message      =  $return;
                $data['message'] = $ex_message;
                 $data['response'] =  $return;
            }
        return $data;
     	
     }

     
  
    //will use for get tokens
     function get_plaid_token($public_token, $metadata="")
    {
            if($_POST)
            {
                $metadata = $_POST['metadata'];$account_id=$metadata['account_id'];   
            }else
            {
                $account_id=$metadata['account_id'];   
            }
       
       
        $data = array(
            "client_id" => $this->plaid_client_id,
            "secret" => $this->plaid_secret,
            "public_token" => $public_token
        );

        $data_fields = json_encode($data);        

        //initialize session
        $ch=curl_init($this->plaid_url . "/item/public_token/exchange");

        //set options
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_fields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
          'Content-Type: application/json',                                                                                
          'Content-Length: ' . strlen($data_fields))                                                                       
        );   

        //execute session
        $token_json = curl_exec($ch);
        $exchange_token = json_decode($token_json,true);  
        if(!isset($exchange_token['access_token']))
        {
            throw new Exception('Invalid Bank');
        }
        $btok_params = array(
          'client_id'    => $this->plaid_client_id,
          'secret'       => $this->plaid_secret ,
          'access_token' => $exchange_token['access_token'],
          'account_id'   => $account_id
         );
          $headers[] = 'Content-Type: application/json';
	      $ch = curl_init();
	      curl_setopt($ch, CURLOPT_URL, $this->plaid_url ."/processor/stripe/bank_account_token/create");
	      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	      curl_setopt($ch, CURLOPT_POST, 1);
	      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($btok_params));
	      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	      curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
	      curl_setopt($ch, CURLOPT_TIMEOUT, 80);
	      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	      $result = curl_exec($ch);
	      if(!$result) {
	        //trigger_error(curl_error($ch));
	      }
	      curl_close($ch);

	      $btoken = json_decode($result); 
          if(!isset($btoken->stripe_bank_account_token))
            {
                throw new Exception('Invalid Bank');
            }
	       
	      return $btoken;     
             

    }

    public function create_customer($email,$token)
     {
               
            //new token
        $customer = $stripe->customers()->create(array(
                'email' => $email,
                'source' => $token
        ));
         return $customer;
     }

 
}