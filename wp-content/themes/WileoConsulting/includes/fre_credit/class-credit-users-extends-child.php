<?php
//$paypal_class = get_stylesheet_directory() . '/includes/escrow/paypal.php';
//require_once($paypal_class);
if( ! class_exists('FRE_Credit_Users_Child') ):
   class FRE_Credit_Users_Child extends FRE_Credit_Users{

      /**
      * charge
      *
      * @param array $charge_obj
      * @return void
      * @since 1.0
      * @package FREELANCEENGINE
      * @category FRE CREDIT
      * @author Jack Bui
      */
        public function charge_child($charge_obj = array()){ 
          //echo "in";exit;
            global $user_ID;

            $default = array(
                'amount' => 0,
                'currency' => fre_credit_get_payment_currency(),
                'customer' => $user_ID,
                'history_type'=> 'charge',
                'status'=> 'completed'
            );

            $charge_obj = wp_parse_args($charge_obj, $default);

            $user_wallet = FRE_Credit_Users()->getUserWallet($charge_obj['customer']);

            $number = FRE_Credit_Currency_Exchange()->exchange($charge_obj['amount'], $charge_obj['currency'], $user_wallet->currency);

            $wallet = new FRE_Credit_Wallet($number, $user_wallet->currency);

            $result = FRE_Credit_Users()->checkBalance($charge_obj['customer'], $wallet);

           
            if( !empty($charge_obj['check_project_type'] && $charge_obj['check_project_type'] == 'time-based') ){

                //$this->updateUserBalance($user_ID, 0);
                $froze_balance = FRE_Credit_Users()->getUserWallet($user_ID, 'freezable');
                $froze_balance->balance +=  $wallet->balance;
                //$this->updateUserBalance($user_ID, $froze_balance->balance, 'freezable');
                $charge_id = FRE_Credit_History()->saveHistory($charge_obj);
                $response = array(
                    'success'=> true,
                    'msg'=> __("Payment success!", ET_DOMAIN),
                    'id'=> $charge_id
                );
            }else if( $result >= 0 ){
                $this->updateUserBalance($user_ID, $result);
                $froze_balance = FRE_Credit_Users()->getUserWallet($user_ID, 'freezable');
                $froze_balance->balance +=  $wallet->balance;
                $this->updateUserBalance($user_ID, $froze_balance->balance, 'freezable');
                $charge_id = FRE_Credit_History()->saveHistory($charge_obj);
                $response = array(
                    'success'=> true,
                    'msg'=> __("Payment success!", ET_DOMAIN),
                    'id'=> $charge_id
                );
            }else{
                $response = array(
                    'success'=> false,
                    'msg'=> __("You don't have enough money in your wallet!", ET_DOMAIN)
                );
            }
            return $response;
        }
   }
endif;
?>