<?php

/**
 * JVZoo Rest Api for PHP
 *
 * You can use this class to interface with the JVZoo Rest API.
 *
 * JVZoo API Signup: https://www.jvzoo.com/myaccount/api
 * API Documentation: http://api.jvzoo.com
 *
 * @author Nate Sanden <natesanden@gmail.com>
 *
 * @link http://www.natesanden.com
 * @copyright Copyright (c) 2015 Nate Sanden
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace nsanden\jvzoo;

class JvzooRestApi {

    public $api_key = 'your-api-key-here';
    public $account_password = 'your-account-password-here';

    const API_URL = 'https://api.jvzoo.com/v2.0';

    public function __construct($api_key, $account_password)
    {
        $this->api_key = $api_key;
        $this->account_password = $account_password;
    }

    public function beginCurl($end_point)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::API_URL . $end_point);
        curl_setopt($ch, CURLOPT_USERPWD, $this->api_key . ':' . $this->account_password);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        return $ch;
    }

    protected function endCurl($ch)
    {
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    public function getAffiliateStatus($product_id, $affiliate_id)
    {
        if(trim($product_id) == '')
        {
            throw new \Exception('$product_id must not be empty.');
        }
        if(trim($affiliate_id) == '')
        {
            throw new \Exception('$affiliate_id must not be empty.');
        }
        $ch = $this->beginCurl('/products/' . $product_id . '/affiliates/' . $affiliate_id);
        $response = $this->endCurl($ch);
        return $response;
    }

    public function getTransactionSummary($pay_key)
    {
        if(trim($pay_key) == '')
        {
            throw new \Exception('$pay_key must not be empty.');
        }
        $ch = $this->beginCurl('/transactions/summaries/' . $pay_key);
        $response = $this->endCurl($ch);
        return $response;
    }

    public function getRecurringPayment($pre_key)
    {
        if(trim($pre_key) == '')
        {
            throw new \Exception('$pre_key must not be empty.');
        }
        $ch = $this->beginCurl('/recurring_payment/' . $pre_key);
        $response = $this->endCurl($ch);
        return $response;
    }

    public function cancelRecurringPayment($pre_key)
    {
        $response = false;
        if(trim($pre_key) != '') {
            $ch = $this->beginCurl('/recurring_payment/PA-' . $pre_key);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            $post_fields = http_build_query(['status' => 'CANCEL']);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
            $response = $this->strip_tags_content($this->endCurl($ch));
            $re = json_decode($response);
            if ($re->results->canceled == 'false') {
                //try again
                $ch = $this->beginCurl('/recurring_payment/SR-' . $pre_key);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($ch, CURLOPT_TIMEOUT, 30);
                $post_fields = http_build_query(['status' => 'CANCEL']);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
                $response = $this->strip_tags_content($this->endCurl($ch));
            }
        }
        return $response;
    }

}
