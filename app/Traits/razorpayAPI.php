<?php

namespace App\Traits;

use Razorpay\Api\Api;

trait razorpayAPI
{
    /**
     * Core of response
     * 
     * @param   string          $message
     * @param   array|object    $data
     * @param   integer         $statusCode  
     * @param   boolean         $isSuccess
     */
    public function signatureVerify($_signature,$_paymentId,$_orderId)
        {
            try{
                $api = new Api($this->razorpayId,$this->razorpaykey);
                $attributes  = array('razorpay_signature'  => $_signature,  'razorpay_payment_id'  => $_paymentId ,  'razorpay_order_id' => $_orderId);
                $order  = $api->utility->verifyPaymentSignature($attributes);
                return true;
            }
            catch(\Exception $e)
            {
                return false;
            }
        }
}        