<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use URL;
use Session;
use Redirect;
use Input;
use Log;
use Mail;
use Carbon\Carbon;

use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use PayPal\Api\PaymentExecution;

use App\Settings;
use App\Social;
use App\Courses;
use App\Marathons;
use App\User;
use App\UserSoul;
use App\Order;

class PaymentController extends Controller
{
	public function __construct()
    {
	/** PayPal api context **/
	    $paypal_conf = \Config::get('paypal');
	    $this->_api_context = new ApiContext(new OAuthTokenCredential(
	        $paypal_conf['client_id'],
	        $paypal_conf['secret'])
	    );
	    $this->_api_context->setConfig($paypal_conf['settings']);
	}

	public function index() {
		return view('front/pages/oplata/testpaypal');
	}

	public function payWithpaypal(Request $request)
    {    	
    	$id_user_order = $request->session()->get('id_user_order');
    	$currentOrder = Order::find($id_user_order);
    	$currentOrder->update(['type_pay' => 1]);    	

		$payer = new Payer();
		        $payer->setPaymentMethod('paypal');

		$item_1 = new Item();
		$item_1->setName('Item 1') 
		            ->setCurrency('RUB')
		            ->setSku("12")
		            ->setQuantity(1)
		            ->setPrice($request->get('amount')); 

		$item_list = new ItemList();
		        $item_list->setItems(array($item_1));

		$amount = new Amount();
		        $amount->setCurrency('RUB')
		            ->setTotal($request->get('amount'));		


		$transaction = new Transaction();
		        $transaction->setAmount($amount)
		            ->setItemList($item_list)
		            ->setDescription('Transaction description')
		            ->setCustom($id_user_order);

		$redirect_urls = new RedirectUrls();
		        $redirect_urls->setReturnUrl(URL::route('paypal_status'))
		            ->setCancelUrl(URL::route('paypal_status'));
		$payment = new Payment();
		        $payment->setIntent('Sale')
		            ->setPayer($payer)
		            ->setRedirectUrls($redirect_urls)
		            ->setTransactions(array($transaction));		        
		try {			
			$payment->create($this->_api_context);
		}  catch (\PayPal\Exception\PPConnectionException $ex) {

            if (\Config::get('app.debug')) {                
                Log::error('PayPal - Заказ № '. $id_user_order .' не оплачен. Время соединения вышло');
                return Redirect::route('error_oplata');
            } else {                
                Log::error('PayPal - Заказ № '. $id_user_order .' не оплачен. Произошла какая-то ошибка');
                return Redirect::route('error_oplata');
            }
        }    
		foreach ($payment->getLinks() as $link) {
			if ($link->getRel() == 'approval_url') {
				$redirect_url = $link->getHref();
			    break;
			}
		}
		/** add payment ID to session **/
		        Session::put('paypal_payment_id', $payment->getId());
		if (isset($redirect_url)) {
		/** redirect to paypal **/
		            return Redirect::away($redirect_url);
		}
		Log::error('PayPal - Заказ № '. $id_user_order .' не оплачен. Произошла неизвестная ошибка');
		return Redirect::route('error_oplata');
	}

	public function getPaymentStatus(Request $request)
    {
    	
		/** Get the payment ID before session clear **/
		$payment_id = Session::get('paypal_payment_id');
		/** clear the session payment ID **/
		Session::forget('paypal_payment_id');
		if (empty(Input::get('PayerID')) || empty(Input::get('token'))) {
			Log::error('PayPal Платеж не прошел');
		    return Redirect::route('error_oplata');
		}
		$paymentCurrentId  = Input::get('paymentId');
		$payerCurrentId    = Input::get('PayerID');

		$payment = Payment::get($payment_id, $this->_api_context);
		$obj = json_decode($payment);
		$id_order = $obj->transactions[0]->custom;

		
        $execution = new PaymentExecution();
        $execution->setPayerId(Input::get('PayerID'));

		/**Execute the payment **/
		$result = $payment->execute($execution, $this->_api_context);
		if ($result->getState() == 'approved') {			
            $currentOrder = Order::find($id_order);      
            if(isset($currentOrder->user_status) && $currentOrder->user_status)
            { 
                self::succesOplataRegisteredUser($currentOrder->user_id, $currentOrder->course_id, $currentOrder); 
                Log::info('PayPal - Заказ № '. $id_order .' оплачен');
                return Redirect::route('success_oplata');
            } else if (isset($currentOrder->user_status) && !$currentOrder->user_status) {
                self::succesOplataNotRegisteredUser($currentOrder->user_id, $currentOrder->course_id, $currentOrder);
                Log::info('PayPal - Заказ № '. $id_order .' оплачен');
                return Redirect::route('success_oplata');  
            }  

		}

		Log::error('PayPal - Заказ № '. $id_order .' не оплачен. Произошла неизвестная ошибка');
		return Redirect::route('error_oplata');
	}

	    protected static function  succesOplataNotRegisteredUser($user_id, $course_id, $currentOrder)
    {
        $settings = Settings::first();
        $user_soul = UserSoul::find($user_id);
        $new_user = array();
        $new_user['status_id'] = 0;    // Выключен. Еще не вводил результати.         
        $newUserPass = str_random(8);
        $new_user['password'] = bcrypt($newUserPass);   
        $new_user['role_id'] = 3; 
        $new_user['name'] = $user_soul->name;
        $new_user['phone'] = $user_soul->phone;
        $new_user['email'] = $user_soul->email; 
        $new_user['course_id']  = $course_id;
        $currentCourse = Courses::find($course_id);
        if(isset($currentCourse)) {
            if($currentCourse->type == 'cours') {
              $new_user['data_start_course']  = Carbon::now();   
            } elseif ($currentCourse->type == 'marathon') {
              $new_user['data_start_course']  = $currentCourse->date_end_selection;           
            } 
        }
        $user = User::create($new_user);  
        $currentOrder->update([
            'status_id' => 1,
            'user_status' => 1,
            'user_id' => $user->id,
        ]);    
        $user_soul->delete();      
        $user->roles()->attach([
            $new_user['role_id']
        ]);  

        $params = array();
        $params['user_email'] = $new_user['email'];
        $params['admin_email'] = $settings->email; 

        Mail::send('emails.user',array('user_name' =>$new_user['email'], 'user_password'=>$newUserPass), function($message) use ($params)
        {
            $message->from($params['admin_email']);
            $message->to($params['user_email'])->subject('gizerskaya - Фитнесс Тренер');
        });

         /* mail($new_user['email'],
          "gizerskaya - Фитнесс Тренер",
          "Спасибо, что нас выбрали. \nВаши данные для входа в Ваш Личный Кабинет:\nЛогин: " . $new_user['email'] . "\nПароль: " . $newUserPass . "",
          "From:".$settings->email."\r\n"."X-Mailer: PHP/" . phpversion());*/

        Mail::send('emails.admin',array('user_name' =>$new_user['name'], 'user_email'=>$new_user['email'], 'user_tel' => $new_user['phone']), function($message) use ($params)
        {
            $message->from('site@gizerskaya.com');
            $message->to($params['admin_email'])->subject('gizerskaya - Фитнесс Тренер');

        }); 

        /* mail($settings->email,
          "gizerskaya - Фитнесс Тренер",
          "У Вас появился новичок : " . $new_user['email'],
          "From:"."gizerskaya - Фитнесс Тренер"."\r\n"."X-Mailer: PHP/" . phpversion());*/
    }

    protected static function  succesOplataRegisteredUser($user_id, $course_id, $currentOrder)
    {
        $settings = Settings::first();
        $currentOrder->update(['status_id' => 1]);
        $currentUser = User::find($user_id);
        $currentUser->update(['course_id' => $course_id]);   
        $currentCourse = Courses::find($course_id);
        if(isset($currentCourse)) {
            if($currentCourse->type == 'cours') {
                $currentDate = Carbon::now();
                $currentUser->update(['data_start_course' => $currentDate]);              
            } elseif ($currentCourse->type == 'marathon') {
                $currentUser->update(['data_start_course' => $currentCourse->date_end_selection]);        
            } 
        }
    }
}
