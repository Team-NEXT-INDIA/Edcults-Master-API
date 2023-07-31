<?php

namespace App\Http\Controllers;

use App\Crypto;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CCavenueController extends Controller
{
    public function ccAvenueRequestHandler(Request $request)
    {
        $merchant_data = '';
        // Paramaters from API request
        $email = $request->input('email');
        $amount = $request->input('amount');
        $coins = $request->input('coins');

        $working_key = env('CC_WORKING_KEY'); //replace with your WORKING_KEY
        $access_code = env('CC_ACCESS_CODE'); //REPLACE WITH YOUR ACCESS CODE
        $merchant_id = env('CC_MERCHANT_ID'); //REPLACE WITH YOUR MERCHANT_ID
        $response_url = 'http://10.0.2.2:8000/api/ccav-response'; //Redirect URL or CANCEL URL
        $order_id = 'ORD' . rand(10000, 99999999) . time(); //GENERATE RANDOM ORDER ID

        foreach ($_POST as $key => $value) {
            $merchant_data .= $key . '=' . $value . '&';
        }

        $merchant_data .= 'merchant_id=' . $merchant_id . '&';
        $merchant_data .= 'order_id=' . $order_id . '&';
        $merchant_data .= 'redirect_url=' . $response_url . '&';
        $merchant_data .= 'merchant_param1=' . $email . '&';
        $merchant_data .= 'merchant_param2=' . $amount . '&';
        $merchant_data .= 'merchant_param3=' . $coins . '&';
        $encrypted_data = Crypto::encrypt($merchant_data, $working_key);

        $access_code = urlencode($access_code);
        $encrypted_data = urlencode($encrypted_data);

        $data = [
            'enc_val' => $encrypted_data,
            'access_code' => $access_code,
        ];

        return response()->json($data);
    }

    public function ccavResponseHandler(Request $request)
    {
        $working_key = env('CC_WORKING_KEY'); //Replace with your working keys
        $encResponse = $request->input('encResp'); // This is the response sent by the CCAvenue Server
        $rcvdString = Crypto::decrypt($encResponse, $working_key);

        $order_status = '';
        $decryptValues = explode('&', $rcvdString);
        $dataSize = sizeof($decryptValues);
        $responseMap = [];

        for ($i = 0; $i < $dataSize; $i++) {
            $information = explode('=', $decryptValues[$i]);
            $responseMap[$information[0]] = $information[1];
        }

        $order_status = $responseMap['order_status'];

        // Save the CCAvenue response in the "orders" table
        $order = Order::create([
            'order_id' => $responseMap['order_id'],
            'tracking_id' => $responseMap['tracking_id'],
            'bank_ref_no' => $responseMap['bank_ref_no'],
            'order_status' => $responseMap['order_status'],
            'failure_message' => $responseMap['failure_message'],
            'payment_mode' => $responseMap['payment_mode'],
            'card_name' => $responseMap['card_name'],
            'status_code' => $responseMap['status_code'],
            'status_message' => $responseMap['status_message'],
            'currency' => $responseMap['currency'],
            'amount' => $responseMap['amount'],
            'billing_name' => $responseMap['billing_name'],
            'billing_address' => $responseMap['billing_address'],
            'billing_city' => $responseMap['billing_city'],
            'billing_state' => $responseMap['billing_state'],
            'billing_zip' => $responseMap['billing_zip'],
            'billing_country' => $responseMap['billing_country'],
            'billing_tel' => $responseMap['billing_tel'],
            'billing_email' => $responseMap['billing_email'],
            'delivery_name' => $responseMap['delivery_name'],
            'delivery_address' => $responseMap['delivery_address'],
            'delivery_city' => $responseMap['delivery_city'],
            'delivery_state' => $responseMap['delivery_state'],
            'delivery_zip' => $responseMap['delivery_zip'],
            'delivery_country' => $responseMap['delivery_country'],
            'delivery_tel' => $responseMap['delivery_tel'],
            'merchant_param1' => $responseMap['merchant_param1'],
            'merchant_param2' => $responseMap['merchant_param2'],
            'merchant_param3' => $responseMap['merchant_param3'],
            'merchant_param4' => $responseMap['merchant_param4'],
            'merchant_param5' => $responseMap['merchant_param5'],
            'vault' => $responseMap['vault'],
            'offer_type' => $responseMap['offer_type'],
            'offer_code' => $responseMap['offer_code'],
            'discount_value' => $responseMap['discount_value'],
            'mer_amount' => $responseMap['mer_amount'],
            'eci_value' => $responseMap['eci_value'],
            'retry' => $responseMap['retry'],
            'response_code' => $responseMap['response_code'],
            'billing_notes' => $responseMap['billing_notes'],
            'trans_date' => str_replace('/', '-', $responseMap['trans_date']),
            'bin_country' => $responseMap['bin_country'],
        ]);

        // Check if the order_status is 'Success'
        if ($responseMap['order_status'] === 'Success') {
            // Prepare the data for the POST request
            $postData = [
                'user_id' => $responseMap['merchant_param1'],
                'amount' => $responseMap['merchant_param3'],
            ];

            $apiResponse = Http::post(
                'http://172.18.169.253:8001/api/users/coins/add',
                $postData
            );
        }

        return response()->json($responseMap);
    }
}
