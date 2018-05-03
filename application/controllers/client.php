<?php
// *************************************************************************
// *                                                                       *
// * iBilling -  Accounting, Billing Software                              *
// * Copyright (c) Sadia Sharmin. All Rights Reserved                      *
// *                                                                       *
// *************************************************************************
// *                                                                       *
// * Email: sadiasharmin3139@gmail.com                                                *
// * Website: http://www.sadiasharmin.com                                  *
// *                                                                       *
// *************************************************************************
// *                                                                       *
// * This software is furnished under a license and may be used and copied *
// * only  in  accordance  with  the  terms  of such  license and with the *
// * inclusion of the above copyright notice.                              *
// * If you Purchased from Codecanyon, Please read the full License from   *
// * here- http://codecanyon.net/licenses/standard                         *
// *                                                                       *
// *************************************************************************
$ui->assign('_application_menu', 'invoices');
$ui->assign('_st', 'Invoices');
$ui->assign('_title', $config['CompanyName']);

if(isset($routes[1]) && ($routes[1] != '')){
    $action = $routes[1];
}
else{
    $action = 'login';
}



$ui->assign('tplheader', 'sections/client_header');
$ui->assign('tplfooter', 'sections/client_footer');


Event::trigger('client',array($action));


switch ($action) {


    case 'iview':

        Event::trigger('client/iview/');

        $xfooter = Asset::js(array('numeric'));

        $id  = $routes['2'];
        $d = ORM::for_table('sys_invoices')->find_one($id);
        if($d){
            $token = $routes['3'];
            $token = str_replace('token_','',$token);
            $vtoken = $d['vtoken'];
            if($token != $vtoken){
                echo 'Sorry Token does not match!';
                exit;
            }


            $items = ORM::for_table('sys_invoiceitems')->where('invoiceid',$id)->order_by_asc('id')->find_many();
            $ui->assign('items',$items);
            //find related transactions
            $trs_c = ORM::for_table('sys_transactions')->where('iid', $id)->count();

            $trs = ORM::for_table('sys_transactions')->where('iid', $id)->order_by_desc('id')->find_many();
            $ui->assign('trs', $trs);
            $ui->assign('trs_c', $trs_c);
//find the user
            $a = ORM::for_table('crm_accounts')->find_one($d['userid']);
            $ui->assign('a',$a);
            $ui->assign('d',$d);

            $i_credit = $d['credit'];
            $i_due = '0.00';
            $i_total = $d['total'];
            if($d['credit'] != '0.00'){
                $i_due = $i_total - $i_credit;
            }
            else{
                $i_due =  $d['total'];
            }




            $ui->assign('i_due', $i_due);
            $pgs = ORM::for_table('sys_pg')->where('status','Active')->order_by_asc('sorder')->find_many();
            $ui->assign('pgs',$pgs);
            $cf = ORM::for_table('crm_customfields')->where('showinvoice','Yes')->order_by_asc('id')->find_many();
            $ui->assign('cf',$cf);

            $x_html = '';

            Event::trigger('view_invoice');

            $ui->assign('xfooter', $xfooter);

            $ui->assign('xjq',' $(\'.amount\').autoNumeric(\'init\', {

    aSign: \''.$config['currency_code'].' \',
    dGroup: '.$config['thousand_separator_placement'].',
    aPad: '.$config['currency_decimal_digits'].',
    pSign: \''.$config['currency_symbol_position'].'\',
    aDec: \''.$config['dec_point'].'\',
    aSep: \''.$config['thousands_sep'].'\'

    });');


            $ui->assign('x_html',$x_html);

            $ui->display('client-iview.tpl');

        }
        else{
            r2(U . 'customers/list', 'e', $_L['Account_Not_Found']);
        }

        break;


    case 'q':

        Event::trigger('client/q/');

        $id  = $routes['2'];
        $d = ORM::for_table('sys_quotes')->find_one($id);
        if($d){
            $token = $routes['3'];
            $token = str_replace('token_','',$token);
            $vtoken = $d['vtoken'];
            if($token != $vtoken){
                echo 'Sorry Token does not match!';
                exit;
            }


            $items = ORM::for_table('sys_quoteitems')->where('qid',$id)->order_by_asc('id')->find_many();
            $ui->assign('items',$items);

            $a = ORM::for_table('crm_accounts')->find_one($d['userid']);
            $ui->assign('a',$a);
            $ui->assign('d',$d);

            $cf = ORM::for_table('crm_customfields')->where('showinvoice','Yes')->order_by_asc('id')->find_many();
            $ui->assign('cf',$cf);

            $x_html = '';




            $ui->assign('x_html',$x_html);

            $ui->display('client-quote.tpl');

        }
        else{
            r2(U . 'customers/list', 'e', $_L['Account_Not_Found']);
        }

        break;




    case 'iprint':

        Event::trigger('client/iprint/');

        $id  = $routes['2'];
        $d = ORM::for_table('sys_invoices')->find_one($id);
        if($d){

            $token = $routes['3'];
            $token = str_replace('token_','',$token);
            $vtoken = $d['vtoken'];
            if($token != $vtoken){
                echo 'Sorry Token does not match!';
                exit;
            }

            //find all activity for this user
            $items = ORM::for_table('sys_invoiceitems')->where('invoiceid',$id)->order_by_asc('id')->find_many();
            $trs_c = ORM::for_table('sys_transactions')->where('iid', $id)->count();

            $trs = ORM::for_table('sys_transactions')->where('iid', $id)->order_by_desc('id')->find_many();
//find the user
            $a = ORM::for_table('crm_accounts')->find_one($d['userid']);
            $i_credit = $d['credit'];
            $i_due = '0.00';
            $i_total = $d['total'];
            if($d['credit'] != '0.00'){
                $i_due = $i_total - $i_credit;
            }
            require 'application/lib/invoices/render.php';

        }
        else{
            r2(U . 'customers/list', 'e', $_L['Account_Not_Found']);
        }

        break;

    case 'ipdf':

        Event::trigger('client/ipdf/');


        $id  = $routes['2'];
        $d = ORM::for_table('sys_invoices')->find_one($id);
        if($d){
            $token = $routes['3'];
            $token = str_replace('token_','',$token);
            $vtoken = $d['vtoken'];
            if($token != $vtoken){
                echo 'Sorry Token does not match!';
                exit;
            }
            //find all activity for this user
            $items = ORM::for_table('sys_invoiceitems')->where('invoiceid',$id)->order_by_asc('id')->find_many();

            $trs_c = ORM::for_table('sys_transactions')->where('iid', $id)->count();

            $trs = ORM::for_table('sys_transactions')->where('iid', $id)->order_by_desc('id')->find_many();

//find the user
            $a = ORM::for_table('crm_accounts')->find_one($d['userid']);
            $i_credit = $d['credit'];
            $i_due = '0.00';
            $i_total = $d['total'];

            if($d['credit'] != '0.00'){
                $i_due = $i_total-$i_credit;
            }
            else{
                $i_due = $i_total;
            }



          //  $i_due = number_format($i_due,2,$config['dec_point'],$config['thousands_sep']);
            $cf = ORM::for_table('crm_customfields')->where('showinvoice','Yes')->order_by_asc('id')->find_many();

            define('_MPDF_PATH','application/lib/mpdf/');

            require('application/lib/mpdf/mpdf.php');

            $pdf_c = '';
            $ib_w_font = 'dejavusanscondensed';
            if($config['pdf_font'] == 'default'){
                $pdf_c = 'c';
                $ib_w_font = 'Helvetica';
            }
            elseif($config['pdf_font'] == 'default'){
                $ib_w_font = 'Helvetica';
            }
            else{



            }



            $mpdf=new mPDF($pdf_c,'A4','','',20,15,15,25,10,10);
//            $mpdf->SetProtection(array('print'));
            $mpdf->SetTitle($config['CompanyName'].$_L['Invoice']);
            $mpdf->SetAuthor($config['CompanyName']);
            $mpdf->SetWatermarkText(ib_lan_get_line($d['status']));
            $mpdf->showWatermarkText = true;
            $mpdf->watermark_font = $ib_w_font;

            // For chinese language uncomment below
            // $mpdf->watermark_font = 'Sun-ExtA';

            //
            $mpdf->watermarkTextAlpha = 0.1;
            $mpdf->SetDisplayMode('fullpage');

            if($config['pdf_font'] == 'AdobeCJK'){
                $mpdf->useAdobeCJK = true;
                $mpdf->autoScriptToLang = true;
                $mpdf->autoLangToFont = true;
            }

            /*


$mpdf->autoLangToFont = true;

$mpdf->watermark_font = 'Sun-ExtA';

             */

            $pdf_tpl = 'application/lib/invoices/pdf-x2.php';

            Event::trigger('invoices/before_pdf_render/',array($id));


            ob_start();

            require $pdf_tpl;

            $html = ob_get_contents();


            ob_end_clean();

            $mpdf->WriteHTML($html);

            if (isset($routes['4']) AND ($routes['4'] == 'dl')) {
                $mpdf->Output(date('Y-m-d') . _raid(4) . '.pdf', 'D'); # D
            } else {
                $mpdf->Output(date('Y-m-d') . _raid(4) . '.pdf', 'I'); # D
            }
        }
        else{
            r2(U . 'customers/list', 'e', $_L['Account_Not_Found']);
        }

        break;

    case 'qpdf':

        Event::trigger('client/qpdf/');


        $id  = $routes['2'];

        $d = ORM::for_table('sys_quotes')->find_one($id);
        if ($d) {

            //find all activity for this user
            $items = ORM::for_table('sys_quoteitems')->where('qid', $id)->order_by_asc('id')->find_many();


            $a = ORM::for_table('crm_accounts')->find_one($d['userid']);



            $cf = ORM::for_table('crm_customfields')->where('showinvoice', 'Yes')->order_by_asc('id')->find_many();


            define('_MPDF_PATH','application/lib/mpdf/');

            require('application/lib/mpdf/mpdf.php');

            $pdf_c = '';
            $ib_w_font = 'dejavusanscondensed';
            if($config['pdf_font'] == 'default'){
                $pdf_c = 'c';
                $ib_w_font = 'Helvetica';
            }

            $mpdf=new mPDF($pdf_c,'A4','','',20,15,15,25,10,10);
            $mpdf->SetProtection(array('print'));
            $mpdf->SetTitle($config['CompanyName'].' Invoice');
            $mpdf->SetAuthor($config['CompanyName']);
            $mpdf->SetWatermarkText($d['status']);
            $mpdf->showWatermarkText = true;
            $mpdf->watermark_font = $ib_w_font;
            $mpdf->watermarkTextAlpha = 0.1;
            $mpdf->SetDisplayMode('fullpage');

            if($config['pdf_font'] == 'AdobeCJK'){
                $mpdf->useAdobeCJK = true;
                $mpdf->autoScriptToLang = true;
                $mpdf->autoLangToFont = true;
            }

            ob_start();

            require 'application/lib/invoices/q-x2.php';

            $html = ob_get_contents();


            ob_end_clean();

            $mpdf->WriteHTML($html);

            if (isset($routes[4]) AND ($routes[4] == 'dl')) {
                $mpdf->Output(date('Y-m-d') . _raid(4) . '.pdf', 'D'); # D
            } else {
                $mpdf->Output(date('Y-m-d') . _raid(4) . '.pdf', 'I'); # D
            }
            // $mpdf->Output();



        }
        break;


    case 'ipay':

        Event::trigger('client/ipay/');


        $id  = $routes[2];

        $token = $routes[3];

        $pg = _post('pg');

        Event::trigger('client/ipay/pg',array($pg,$id,$token));

        $d = ORM::for_table('sys_invoices')->find_one($id);
        if($d){

            $token = str_replace('token_','',$token);
            $vtoken = $d['vtoken'];
            if($token != $vtoken){
                echo 'Sorry Token does not match!';
                exit;
            }

            //check pg
            $ui->assign('d',$d);


            $i_credit = $d['credit'];
            $i_due = '0.00';
            $i_total = $d['total'];


            $amount = $i_total-$i_credit;
            $invoiceid = $d['id'];
            $vtoken = $d['vtoken'];
            $ptoken = $d['ptoken'];





            //get user details

            $u = ORM::for_table('crm_accounts')->find_one($d['userid']);




            switch ($pg){

                case 'paypal':

                    $p = ORM::for_table('sys_pg')->where('processor', 'paypal')->find_one();

                    if($p){

                        // get currency

                        $currency_id = $d['currency'];

                        $currency_find = Model::factory('Models_Currency')->find_one($currency_id);

                        if($currency_find){

                            $currency = $currency_id;
                            $currency_code = $currency_find->cname;
                            $currency_rate = $currency_find->rate;


                        }
                        else{

                            $currency = 0;
                            $currency_code = $p['c1'];
                            $currency_rate = 1.0000;

                        }

                        $ppemail = $p['value'];
//

                        $c2 = $p['c2'];
                        if(($c2 != '') AND (is_numeric($c2)) AND($c2 != '1')){
                            $amount = $amount/$c2;
                            $amount = round($amount,2);
                        }

                        $url = 'https://www.paypal.com/cgi-bin/webscr';

//                        $params = array(
//                            array('name' => "business",
//                                'value' => $ppemail
//                            ),
//                            array('name' => "return",
//                                'value' => U . "client/ipay_submitted/$invoiceid/token_$vtoken/",
//                            ),
//                            array('name' => "cancel_return",
//                                'value' => U . "client/ipay_cancel/$invoiceid/token_$vtoken/",
//                            ),
//                            array('name' => "notify_url",
//                                'value' => U . "client/ipay_ipn/$invoiceid/token_$ptoken/",
//                            ),
//                            array('name' => "item_name_1",
//                                'value' => "Payment For INV # $invoiceid"
//                            ),
//                            array('name' => "amount_1",
//                                'value' => $amount
//                            ),
//                            array('name' => "item_number_1",
//                                'value' => $invoiceid
//                            ),
//                            array('name' => "quantity_1",
//                                'value' => '1'
//                            ),
//                            array('name' => "upload",
//                                'value' => '1'
//                            ),
//                            array('name' => "cmd",
//                                'value' => '_cart'
//                            ),
//                            array('name' => "txn_type",
//                                'value' => 'cart'
//                            ),
//                            array('name' => "num_cart_items",
//                                'value' => '1'
//                            ),
//                            array('name' => "rm",
//                                'value' => '2'
//                            ),
//                            array('name' => "payment_gross",
//                                'value' => $amount
//                            ),
//                            array('name' => "currency_code",
//                                'value' => $currency_code
//                            )
//                        );



                        $params = array(
                            array('name' => "business",
                                'value' => $ppemail
                            ),
                            array('name' => "return",
                                'value' => U . "client/ipay_submitted/$invoiceid/token_$vtoken/",
                            ),
                            array('name' => "cancel_return",
                                'value' => U . "client/ipay_cancel/$invoiceid/token_$vtoken/",
                            ),
                            array('name' => "notify_url",
                                'value' => U . "client/ipay_ipn/$invoiceid/token_$ptoken/",
                            ),
                            array('name' => "item_name",
                                'value' => "Payment For INV # $invoiceid"
                            ),
                            array('name' => "amount",
                                'value' => $amount
                            ),
                            array('name' => "cmd",
                                'value' => '_xclick'
                            ),
                            array('name' => "no_shipping",
                                'value' => '1'
                            ),
                            array('name' => "rm",
                                'value' => '2'
                            ),
                            array('name' => "currency_code",
                                'value' => $currency_code
                            )
                        );


                        Fsubmit::form($url, $params);

                    }

                    else{
                        echo 'Paypal is Not Found!';
                    }


                    break;


                case 'manualpayment':

                    Event::trigger('client/manualpayment/');

                    $p = ORM::for_table('sys_pg')->where('processor', 'manualpayment')->find_one();

                    if($p){
                        $ui->assign('i_due', $amount);
                        $ui->assign('ins',$p['value']);
                        $ui->display('client-ipay.tpl');
                    }


                    break;

                case 'stripe':
                    $p = ORM::for_table('sys_pg')->where('processor', 'stripe')->find_one();

                    if($p){
                        $a = ORM::for_table('crm_accounts')->find_one($d['userid']);
                        $it = $i_total - $i_credit;
                        $amount = $it*100;
                        $ins = ' <script
                                        src="https://checkout.stripe.com/v2/checkout.js" class="stripe-button"
                                        data-key="'.$p['value'].'"
                                        data-amount="'.$amount.'"
                                        data-name="INV #'.$d['id'].'"
                                        data-email="'.$a['email'].'"
                                        data-currency="'.$p['c1'].'"
                                        data-description="Payment for Invoice # '.$d['id'].'">
                                </script>';

                        $ui->assign('ins',$ins);

                        $ui->display('stripe.tpl');
                    }


                    break;


                case 'stripe_post':
                    $p = ORM::for_table('sys_pg')->where('processor', 'stripe')->find_one();
                    if($p){
                        $a = ORM::for_table('crm_accounts')->find_one($d['userid']);
                        $it = $i_total - $i_credit;
                        $amount = $it*100;
                        $currency_code = $p['c1'];

                        require_once('application/lib/stripe/init.php');


                        $description = "Payment For INV # $invoiceid";

                        $cardNumber = _post('cardNumber');

                        $cardExpiry = _post('cardExpiry');

                        $ce = explode('/',$cardExpiry);


                        $cardCVC = _post('cardCVC');

                        $myCard = array('number' => $cardNumber, 'exp_month' => $ce['0'], 'exp_year' => $ce['1']);


                        try {

                            \Stripe\Stripe::setApiKey($p['value']);
                            $charge = \Stripe\Charge::create(array('card' => $myCard, 'amount' => $amount, 'currency' => $currency_code,"description" => $description));


//                       $charge =  '  Stripe\Charge JSON: {
//    "id": "ch_16QJiYAN1GVPX6ZsbBl20gsJ",
//    "object": "charge",
//    "created": 1437319722,
//    "livemode": false,
//    "paid": true,
//    "status": "succeeded",
//    "amount": 193600,
//    "currency": "usd",
//    "refunded": false,
//    "source": {
//        "id": "card_16QJiYAN1GVPX6ZsDKidAMN7",
//        "object": "card",
//        "last4": "4242",
//        "brand": "Visa",
//        "funding": "credit",
//        "exp_month": 5,
//        "exp_year": 2016,
//        "fingerprint": "n0QKFME5XxL1IRG9",
//        "country": "US",
//        "name": null,
//        "address_line1": null,
//        "address_line2": null,
//        "address_city": null,
//        "address_state": null,
//        "address_zip": null,
//        "address_country": null,
//        "cvc_check": null,
//        "address_line1_check": null,
//        "address_zip_check": null,
//        "tokenization_method": null,
//        "dynamic_last4": null,
//        "metadata": [],
//        "customer": null
//    },
//    "captured": true,
//    "balance_transaction": "txn_16QJiYAN1GVPX6Zs24syLCZi",
//    "failure_message": null,
//    "failure_code": null,
//    "amount_refunded": 0,
//    "customer": null,
//    "invoice": null,
//    "description": null,
//    "dispute": null,
//    "metadata": [],
//    "statement_descriptor": null,
//    "fraud_details": [],
//    "receipt_email": null,
//    "receipt_number": null,
//    "shipping": null,
//    "destination": null,
//    "application_fee": null,
//    "refunds": {
//        "object": "list",
//        "total_count": 0,
//        "has_more": false,
//        "url": "\/v1\/charges\/ch_16QJiYAN1GVPX6ZsbBl20gsJ\/refunds",
//        "data": []
//    }
//}';



                            $charge = str_replace('Stripe\Charge JSON:','',$charge);
                           $resp = json_decode($charge,true);
                            $trid = $resp['id'];
                            $last4 = $resp['source']['last4'];
                          $captured = $resp['captured'];

                            if($captured == true){

                                $inv = ORM::for_table('sys_invoices')->find_one($id);
                                if($inv) {

                                    $inv->status = 'Paid';
                                    $inv->save();
                                    Event::trigger('invoices/markpaid/',$invoice=$inv);
                                    _msglog('s','Payment Successful');
                                    r2(U.'client/iview/'.$d['id'].'/'.'token_'.$d['vtoken']);
                                }

                            }

                            else{
                                _msglog('e','This API call cannot be made with a publishable API key. Please use a secret API key. You can find a list of your API keys at https://dashboard.stripe.com/account/apikeys.');
                                r2(U.'client/iview/'.$d['id'].'/'.'token_'.$d['vtoken']);
                            }



                        } catch(\Stripe\Error\Card $e) {
                            // Since it's a decline, \Stripe\Error\Card will be caught
                            $body = $e->getJsonBody();
                            $err  = $body['error'];

                            print('Status is:' . $e->getHttpStatus() . "\n");
                            print('Type is:' . $err['type'] . "\n");
                            print('Code is:' . $err['code'] . "\n");
                            // param is '' in this case
                            print('Param is:' . $err['param'] . "\n");
                            print('Message is:' . $err['message'] . "\n");
                        } catch (\Stripe\Error\InvalidRequest $e) {
                            // Invalid parameters were supplied to Stripe's API
                        } catch (\Stripe\Error\Authentication $e) {
                            // Authentication with Stripe's API failed
                            // (maybe you changed API keys recently)
                        } catch (\Stripe\Error\ApiConnection $e) {
                            // Network communication with Stripe failed
                        } catch (\Stripe\Error\Base $e) {
                            // Display a very generic error to the user, and maybe send
                            // yourself an email
                        } catch (Exception $e) {
                            // Something else happened, completely unrelated to Stripe
                        }

                    }

                    break;


                case 'authorize_net':

                    $p = ORM::for_table('sys_pg')->where('processor', 'authorize_net')->find_one();

                    if($p){

                        $invoiceid = $d['id'];
                        $amount = $i_total - $i_credit;
                        $url = 'https://secure.authorize.net/gateway/transact.dll';
                        $loginID = $p['value'];

                        $transactionKey = $p['c1'];

                        $description = "Invoice Payment - $invoiceid";

                        // an invoice is generated using the date and time
                        $invoice = $invoiceid;
// a sequence number is randomly generated
                        $sequence = rand(1, 1000);
// a timestamp is generated
                        $timeStamp = time();

                        $testMode = "false";
                        if (phpversion() >= '5.1.2') {
                            $fingerprint = hash_hmac("md5", $loginID . "^" . $sequence . "^" . $timeStamp . "^" . $amount . "^", $transactionKey);
                        } else {
                            $fingerprint = bin2hex(mhash(MHASH_MD5, $loginID . "^" . $sequence . "^" . $timeStamp . "^" . $amount . "^", $transactionKey));
                        }
                        $params = array(
                            array('name' => "x_login",
                                'value' => $loginID
                            ),
                            array('name' => "x_amount",
                                'value' => $amount
                            ),
                            array('name' => "x_description",
                                'value' => $description
                            ),
                            array('name' => "x_invoice_num",
                                'value' => $invoice
                            ),
                            array('name' => "x_fp_sequence",
                                'value' => $sequence
                            ),
                            array('name' => "x_fp_timestamp",
                                'value' => $timeStamp
                            ),
                            array('name' => "x_fp_hash",
                                'value' => $fingerprint
                            ),
                            array('name' => "x_test_request",
                                'value' => $testMode
                            ),
                            array('name' => "x_show_form",
                                'value' => "PAYMENT_FORM"
                            )
                        );

                        Fsubmit::form($url, $params);
                    }


                    break;


                case 'ccavenue':

                    $p = ORM::for_table('sys_pg')->where('processor', 'ccavenue')->find_one();

                    if($p){

                        require ('application/lib/misc/ccavenue.php');

                        $currency_code = $p['c2'];
                        $c3 = $p['c3'];

                        if(($c3 != '') AND (is_numeric($c3)) AND($c3 != '1')){
                            $amount = $amount/$c3;
                        }

                        $Merchant_Id = $p['value']; //Given to merchant by ccavenue


                        $WorkingKey = $p['c1']; //Given to merchant by ccavenue

                        $redirect_url = U . "client/ipay_ipn/$invoiceid/token_$ptoken/";


                        require ('application/lib/misc/ccform.php');


                        // Updated Jan 10, 2016

//                        $Checksum = getCheckSum($Merchant_Id,$amount,$invoiceid ,$redirect_url,$WorkingKey);
//
//                        $url = 'https://www.ccavenue.com/shopzone/cc_details.jsp';
//
//
//
//
//                        $params = array(
//
//                            array('name' => "merchant_id",
//                                'value' => $Merchant_Id
//                            ),
//
//                            array('name' => "Redirect_Url",
//                                'value' => $redirect_url
//                            ),
//
//                            array('name' => "amount",
//                                'value' => $amount
//                            ),
//                            array('name' => "order_id",
//                                'value' => $invoiceid
//                            ),
//                            array('name' => "Checksum",
//                                'value' => $Checksum
//                            ),
//                            array('name' => "upload",
//                                'value' => '1'
//                            ),
//                            array('name' => "ActionID",
//                                'value' => 'TXN'
//                            ),
//                            array('name' => "TxnType",
//                                'value' => 'A'
//                            ),
//                            array('name' => "num_cart_items",
//                                'value' => '1'
//                            ),
//                            array('name' => "rm",
//                                'value' => '2'
//                            ),
//                            array('name' => "payment_gross",
//                                'value' => $amount
//                            ),
//                            array('name' => "TxnType",
//                                'value' => 'A'
//                            ),
//                            array('name' => "payment_gross",
//                                'value' => $amount
//                            ),
//                            array('name' => "currency",
//                                'value' => $currency_code
//                            ),
//                            array('name' => "billing_name",
//                                'value' =>$u['account']
//                            ),
//                            array('name' => "billing_address",
//                                'value' =>$u['address']
//                            ),
//                            array('name' => "billing_city",
//                                'value' =>$u['city']
//                            ),
//                            array('name' => "billing_state",
//                                'value' =>$u['state']
//                            ),
//                            array('name' => "billing_zip",
//                                'value' =>$u['zip']
//                            ),
//                            array('name' => "billing_country",
//                                'value' =>'India'
//                            ),
//                            array('name' => "billing_tel",
//                                'value' =>$u['phone']
//                            ),
//                            array('name' => "billing_email",
//                                'value' =>$u['email']
//                            ),
//                            array('name' => "delivery_name",
//                                'value' =>$u['account']
//                            ),
//                            array('name' => "delivery_address",
//                                'value' =>$u['address']
//                            ),
//                            array('name' => "delivery_city",
//                                'value' =>$u['city']
//                            ),
//                            array('name' => "delivery_state",
//                                'value' =>$u['state']
//                            ),
//                            array('name' => "delivery_state",
//                                'value' =>$u['state']
//                            ),
//                            array('name' => "delivery_zip",
//                                'value' =>$u['zip']
//                            ),
//                            array('name' => "delivery_country",
//                                'value' =>$u['country']
//                            ),
//                            array('name' => "delivery_tel",
//                                'value' =>$u['phone']
//                            ),
//                            array('name' => "merchant_param1",
//                                'value' =>''
//                            )
//
//                        );
//
//
//                        Fsubmit::form($url, $params);

                    }



                    break;


                case 'braintree':

                    $p = ORM::for_table('sys_pg')->where('processor', 'braintree')->find_one();
                    Braintree_Configuration::environment($p['c4']);
                    Braintree_Configuration::merchantId($p['value']);
                    Braintree_Configuration::publicKey($p['c1']);
                    Braintree_Configuration::privateKey($p['c2']);

                    if($p){
                        $a = ORM::for_table('crm_accounts')->find_one($d['userid']);
                        $it = $i_total - $i_credit;
                        $amount = $it*100;
                        $clientToken = Braintree_ClientToken::generate(array());
                        $formurl = U . "client/btpay_submitted/$invoiceid/token_$vtoken/";
                        $vamount =  $config['currency_code']. number_format($d['total'],2,$config['dec_point'],$config['thousands_sep']);
                        $ins = '
                      <form id="checkout" method="post" action="'.$formurl.'">
  <div id="payment-form"></div>
  <input type="submit" value="Pay '.$config['currency_code'].' '.$vamount .'">
</form>
                      <script src="https://js.braintreegateway.com/v2/braintree.js"></script>
                      <script>
									var clientToken = "'.$clientToken.'";
									braintree.setup(clientToken, "dropin", {
  									container: "payment-form"
									});
								</script>';
                        $ui->assign('ins',$ins);
                        $ui->display('client-ipay.tpl');
                    }
                    break;



                case 'quickpay':

                    $p = ORM::for_table('sys_pg')->where('processor', 'quickpay')->find_one();

                    if($p){

                        require 'application/lib/misc/quickpay.php';

                        $qp = new Quickpay($p['value'], $p['c1']);

                        $data_fields['msgtype'] = 'authorize';
                        $data_fields['language'] = 'en';
                        $data_fields['ordernumber'] = $invoiceid;
                        $data_fields['amount'] = $amount;
                        $data_fields['currency'] = $p['c3'];
                        $data_fields['continueurl'] = U . "client/ipay_submitted/$invoiceid/token_$vtoken/";
                        $data_fields['cancelurl'] = U . "client/ipay_cancel/$invoiceid/token_$vtoken/";
                        $data_fields['callbackurl'] = U . "client/ipay_ipn/$invoiceid/token_$ptoken/";

//                   echo '
//
//<form action="https://secure.quickpay.dk/form/" method="post">
//
//'.$qp->form_fields($data_fields).'
//
//<input type="submit" value="Open Quickpay payment window" />
//
//</form>
//
//
//';

                        Fsubmit::input('https://secure.quickpay.dk/form/', $qp->form_fields($data_fields));


                    }





                    break;







                default:
                    echo 'Payment Gateway Not Found!';

            }

        }
        else{
            echo 'Sorry Invoice Not Found!';
            exit;
        }

        break;

    /*
     * CCAvenue
     *
     *
     */


    case 'ipay_cancel':

        Event::trigger('client/ipay_cancel/');

        $id  = $routes['2'];
        $token = $routes['3'];
        r2(U."client/iview/$id/$token/",'e',$_L['Payment Cancelled']);

        break;


    case 'ipay_submitted':

        Event::trigger('client/ipay_submitted/');

        $id  = $routes['2'];
        $token = $routes['3'];
        r2(U."client/iview/$id/$token/",'s',$_L['Payment Successful']);


        break;

    case 'ipay_ipn':
        Event::trigger('client/ipay_ipn/');
        $id  = $routes['2'];
        $token = $routes['3'];
        //   r2(U."client/iview/$id/$token/",'s',$_L['Payment Successful']);

        $d = ORM::for_table('sys_invoices')->find_one($id);
        if($d) {
            $token = $routes['3'];
            $token = str_replace('token_', '', $token);
            $ptoken = $d['ptoken'];
            if ($token != $ptoken) {
                echo 'Sorry Token does not match!';
                exit;
            }

            $d->status = 'Paid';
            $d->save();

            Event::trigger('invoices/markpaid/',$invoice=$d);

        }

        break;


    case 'ipay_success':

        Event::trigger('client/ipay_success/');

        $id  = $routes['2'];
        $token = $routes['3'];
        //   r2(U."client/iview/$id/$token/",'s',$_L['Payment Successful']);

        $d = ORM::for_table('sys_invoices')->find_one($id);
        if($d) {
            $token = $routes['3'];
            $token = str_replace('token_', '', $token);
            $ptoken = $d->ptoken;
            $vtoken = $d->vtoken;
            if ($token != $ptoken) {
                echo 'Sorry Token does not match!';
                exit;
            }

            $d->status = 'Paid';
            $d->save();

            Event::trigger('invoices/markpaid/',$invoice=$d);

            r2(U."client/iview/$id/$vtoken/",'s',$_L['Payment Successful']);

        }

        break;





    case 'btpay_submitted':

        Event::trigger('client/btpay_submitted/');

        $id  = $routes['2'];
        $d = ORM::for_table('sys_invoices')->find_one($id);
        $ui->assign('d',$d);
        $token = $routes['3'];
        $p = ORM::for_table('sys_pg')->where('processor', 'braintree')->find_one();
        if($p){
            $merchantId	= $p["value"];
            $publicKey	= $p["c1"];
            $privateKey	= $p["c2"];
            $account 	= $p["c3"];
            $environment = $p["c4"];
            $accountname = $p["name"];

            Braintree_Configuration::environment($environment);
            Braintree_Configuration::merchantId($merchantId);
            Braintree_Configuration::publicKey($publicKey);
            Braintree_Configuration::privateKey($privateKey);
            $nonce = isset( $_POST["payment_method_nonce"] )?$_POST["payment_method_nonce"]:0;
            if ($nonce) {
                // get user
                $a = ORM::for_table('crm_accounts')->find_one($d['userid']);
                // get invoice
                $id  = $routes['2'];
                $iid = $id;// invoice ID
                $i = ORM::for_table('sys_invoices')->find_one($iid);
                $d = ORM::for_table('sys_invoices')->find_one($id);
                if($d){
                    // we have an invoice, validate token...
                    $token = $routes['3'];
                    $token = str_replace('token_','',$token);
                    $vtoken = $d['vtoken'];
                    if($token != $vtoken){
                        echo 'Sorry Token does not match!';
                        exit;
                    } else {
                        // echo 'TOKEN MATCHES!!!!!!!!!!!!!!!!';
                        $i_credit = $d['credit'];
                        $i_due = '0.00';
                        $i_total = $d['total'];
                        $amount = $i_total - $i_credit;
                        $invoiceid = $d['id'];

                        $result = Braintree_Transaction::sale(array(
                            'amount' => $amount,
                            'orderId' => $id,
                            'paymentMethodNonce' => $nonce,
                            'options' => array(
                                'submitForSettlement' => True
                            )
                        ));

                        if ($result->success) {


                            $invoiceview = U . "invoices/pdf/$invoiceid/view/token_$vtoken";
                            $invoiceprint = U . "iview/print/$invoiceid/token_$vtoken";

                            // Thank you! Your payment has been successfully processed for $16.95
                            $ins = "Success!: Thank you for your payment.";
//                            $ins.= "<br />".'To PRINT your invoice click here <br> <a class="btn btn-primary" href="'.$invoiceprint.'" target="_blank">Print Invoice</a>';
//                            $date = $result->transaction->createdAt->date; //"2015-06-15 18:52:57.000000"
//                            $amount = $result->transaction->amount;
//                            $amount = Finance::amount_fix($amount);
//                            $payerid = $a["id"];
//                            $pmethod = 'Braintree';
//                            $amount = str_replace($config['currency_code'], '', $amount);
//                            $amount = str_replace(',', '', $amount);
//                            if (!is_numeric($amount)) {
//                                $msg .= 'Invalid Amount' . '<br>';
//                            }
//                            $cat = 'Consulting'; //77; // Consulting income. This should already be defined on the invoice or line item.

//                            $description = $p["name"]; //'Braintree Payment';
//                            $a = ORM::for_table('sys_accounts')->where('id', $account)->find_one(); // get braintree balance
//                            $cbal = $a['balance']; // customer balance
//                            $nbal = $cbal + $amount;
//                            $a->balance = $nbal;
//                            $a->save(); // update customer balance
//                            $d = ORM::for_table('sys_transactions')->create(); // BOF add a transaction
//                            $d->account = $accountname;
//                            $d->type = 'Income';
//                            $d->payerid = $payerid;
//
//                            $d->amount = $amount;
//                            $d->category = $cat;
//                            $d->method = $pmethod;
//                            $d->description = 'Invoice '.$id .' Payment'; //$description;
//                            $d->date = date('Y-m-d');//"2015-06-15 18:52:57.000000"
//                            $d->dr = '0.00';
//                            $d->cr = $amount;
//                            $d->bal = $nbal;
//                            $d->iid = $iid;
//                            $d->save(); // BOF add a transaction
//                            $tid = $d->id();
//                            // log it...
//                            _log('New Deposit: ' . $description . ' [TrID: ' . $tid . ' | Amount: ' . $amount . ']', 'Admin',$payerid);
//                            _msglog('s', 'Transaction Added Successfully');

                            if ($i) {
                                $pc = $i['credit'];
                                $it = $i['total'];
                                $dp = $it - $pc;
                                if (($dp == $amount) OR (($dp < $amount))) {
                                    $i->status = 'Paid';
                                    $i->datepaid = date('Y-m-d H:i:s');
                                    Event::trigger('invoices/markpaid/',$invoice=$i);
                                } else {
                                    $i->status = 'Partially Paid';
                                }
                                $i->credit = $pc + $amount;
                                $i->paymentmethod = $accountname;
                                $i->save();

                            } //if ($i) {
                        } else if ($result->transaction) {
                            $ins = "Error processing transaction:";
                            $ins .= ("\n  code: " . $result->transaction->processorResponseCode);
                            $ins .= ("\n  text: " . $result->transaction->processorResponseText);
                        } else {
                            $ins = ("Validation errors: \n");
                            $ins .= ($result->errors->deepAll());
                        }
//                        $ui->assign('ins',$ins);
//                        $ui->display('client-ipay.tpl');
                        r2(U.'client/iview/'.$i->id.'/'.$i->vtoken.'/','s',$ins);
                    }
                }
            }
            /* eof bernie changes */
        } else echo 'Payment Gateway Not Found!';


        break;

    case 'ccsubmit':


        $p = ORM::for_table('sys_pg')->where('processor', 'ccavenue')->find_one();

        if($p) {

            require('application/lib/misc/ccavenue.php');

            $currency_code = $p['c2'];
            $c3 = $p['c3'];

            if (($c3 != '') AND (is_numeric($c3)) AND ($c3 != '1')) {
                $amount = $amount / $c3;
            }

            $Merchant_Id = $p['value']; //Given to merchant by ccavenue


            $WorkingKey = $p['c1']; //Given to merchant by ccavenue

            $redirect_url = U . "client/ipay_ipn/$invoiceid/token_$ptoken/";


            require('application/lib/misc/ccsubmit.php');

        }


        break;



    case 'login':

        Event::trigger('client/login/');

        Contacts::isLogged();


        $ui->display('client_login.tpl');


        break;


    case 'register':
        $extra_fields = array();
        $ui->assign('extra_fields',$extra_fields);
        Event::trigger('client/register/');

        Contacts::isLogged();

        $ui->assign('xfooter',Asset::js(array('contacts/register')));


        $ui->display('client_register.tpl');


        break;

    case 'forgot_pw':

        Event::trigger('client/forgot_pw/');

        $ui->display('client_forgot_pw.tpl');


        break;

    case 'forgot_pw_post':

        Event::trigger('client/forgot_pw_post/');

        $username = _post('username');

        $d = ORM::for_table('crm_accounts')->where('email',$username)->find_one();

        if($d){

            //

            $fullname = $d->account;

            $password = Ib_Str::random_string(8);

            $password_hash = Password::_crypt($password);

            $d->password = $password_hash;

            $d->save();

            // Send email notification

            $mail = Notify_Email::_init();
            $mail->AddAddress($username, $fullname);
            $mail->Subject = 'Password Reset for '.$config['CompanyName'];
            $mail->MsgHTML('Your Password has been reset to: '. $password.' Go to this link to login with new password- '.U.'client/login/');
            $mail->Send();

            r2(U.'client/login/','s','New Password has been sent to your email.');



        }

        else{

            r2(U.'client/forgot_pw/','e','No User found with this Email');

        }



        break;

    case 'auth':

        Event::trigger('client/auth/');

        $email = _post('username');
        $password = _post('password');

        $auth = Contacts::login($email,$password);

        if($auth){

            // store authentication key in the cookies

            setcookie('ib_ct', $auth, time() + (86400 * 30), "/"); // 86400 = 1 day

            r2(U.'client/dashboard/');



        }
        else{
            r2(U.'client/login/','e',$_L['Invalid Username or Password']);
        }




        break;


    case 'auto_login':
        Event::trigger('client/auto_login/');



        break;


    case 'register_post':

       // sleep(3);

        if(!isset($_SESSION['recaptcha_verified'])){
            $_SESSION['recaptcha_verified'] = false;
        }

        if($config['recaptcha'] == 1){


            if(!$_SESSION['recaptcha_verified']){

                if(Ib_Recaptcha::isValid($config['recaptcha_secretkey']) == false){

                    ib_die($_L['Recaptcha Verification Failed']);

                }
                else{

                    $_SESSION['recaptcha_verified'] = true;

                }

            }



        }

        $msg = '';

        $data = array();

        Event::trigger('client/register_post/');



        $data['account'] = _post('fullname');
        $data['email'] = _post('email');
        $data['password'] = _post('password');
        $data['password2'] = _post('password2');

        $o_password = $data['password'];

        if($data['account'] == ''){
            $msg .= 'Fullname is required <br>';
        }

        if(Validator::Email($data['email']) == false){
            $msg .= $_L['Invalid Email'].' <br>';
        }
        $f = ORM::for_table('crm_accounts')->where('email',$data['email'])->find_one();

        if($f){
            $msg .= $_L['Email already exist'].' <br>';
        }



        if($data['password'] != ''){

            if(!Validator::Length($data['password'],15,5)){
                $msg .= 'Password should be between 6 to 15 characters'. '<br>';

            }

            if($data['password'] != $data['password2']){
                $msg .= 'Passwords does not match'. '<br>';
            }


            $data['password'] = Password::_crypt($data['password']);


        }
        else{

            $msg .= 'Password is required <br>';

        }

        // API call for extra fields



        //

        // optional params

        $data['phone'] = _post('phone');
        $data['address'] = _post('address');
        $data['city'] = _post('city');
        $data['zip'] = _post('zip');
        $data['state'] = _post('');
        $data['country'] = _post('country');
        $data['company'] = _post('company');
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        $data['email_verified'] = 'No';
        $ip = get_client_ip();
        $data['signed_up_ip'] = $ip;
        $isp = gethostbyaddr($ip);
        if(!$isp){

            $isp = '';

        }

        $data['isp'] = $isp;
        $data['balance'] = '0.00';
        $data['status'] = 'Active';
        $data['notes'] = '';
        $data['token'] = '';
        $data['img'] = '';
        $data['web'] = '';
        $data['facebook'] = '';
        $data['google'] = '';
        $data['linkedin'] = '';
        $data['twitter'] = '';
        $data['skype'] = '';
//        $data[''] = '';


//        $ = _post('');



        Event::trigger('client_register_post_data_posted');


        if($msg == ''){

            // create client




            // try to guess location



            //

            $d = ORM::for_table('crm_accounts')->create();

            $d->account = $data['account'];
            $d->email = $data['email'];
            $d->phone = $data['phone'];
            $d->address = $data['address'];
            $d->city = $data['city'];
            $d->zip = $data['zip'];
            $d->state = $data['state'];
            $d->country = $data['country'];
            $d->tags = '';

            //others
            $d->fname = '';
            $d->lname = '';
            $d->company = $data['company'];
            $d->jobtitle = '';
            $d->cid = '0';
            $d->o = '0';
            $d->balance = $data['balance'];
            $d->status = $data['status'];
            $d->notes = $data['notes'];
            $d->password = $data['password'];
            $d->token = '';
            $d->ts = '';
            $d->img = $data['img'];
            $d->web = $data['web'];
            $d->facebook = $data['facebook'];
            $d->google = $data['google'];
            $d->linkedin = $data['linkedin'];

            // v 4.2

            $d->gname = '';
            $d->gid = 0;

            $d->signed_up_ip = $ip;
            $d->isp = $data['isp'];

            //
            $d->save();
            $cid = $d->id();
            _log($_L['New Contact Added'].' '.$data['account'].' [CID: '.$cid.']','Portal Registration');


            $send_email = Ib_Email::send_client_welcome_email($data);

            $auth = Contacts::login($data['email'],$o_password);

            if($auth){

                // store authentication key in the cookies

                setcookie('ib_ct', $auth, time() + (86400 * 30), "/"); // 86400 = 1 day



            }

            echo $cid;

            Event::trigger('client/client_registered',$data);




        }

        else{

            echo $msg;

        }






        break;


    case 'dashboard':

        // 297

        $dashboard_summary_extras = '';
        $dashboard_extra_row_1 = '';
        $c = Contacts::details();

        Event::trigger('client/dashboard/');

        $ui->assign('_application_menu', 'dashboard');
        $ui->assign('_st', $_L['Dashboard']);
        $ui->assign('_title', $config['CompanyName'].' - '.$_L['Dashboard']);

        $cf = ORM::for_table('crm_customfields')->where('ctype','crm')->order_by_asc('id')->find_many();
        $ui->assign('cf',$cf);




        $ui->assign('user',$c);

        $cid = $c->id;

        $d = ORM::for_table('sys_transactions')
            ->where_any_is(array(
                array('payerid' => $cid),
                array('payeeid' => $cid)))->limit(5)
            ->find_many();
        $ui->assign('t',$d);

        $d = ORM::for_table('sys_invoices')->where('userid',$c->id)->limit(5)->find_array();

        $ui->assign('d',$d);

        $d = ORM::for_table('sys_quotes')->where('userid',$c->id)->limit(5)->find_array();

        $ui->assign('q',$d);

        //  aSign: \''.$config['currency_code'].' \',

        $ui->assign('xjq',' $(\'.amount\').autoNumeric(\'init\', {

    
    dGroup: '.$config['thousand_separator_placement'].',
    aPad: '.$config['currency_decimal_digits'].',
    pSign: \''.$config['currency_symbol_position'].'\',
    aDec: \''.$config['dec_point'].'\',
    aSep: \''.$config['thousands_sep'].'\'

    });');


        $ui->assign('dashboard_summary_extras',$dashboard_summary_extras);
        $ui->assign('dashboard_extra_row_1',$dashboard_extra_row_1);
        $ui->display('client_dashboard.tpl');



        break;

    case 'invoices':
        Event::trigger('client/invoices/');
        $ui->assign('_application_menu', 'invoices');
        $ui->assign('_st', $_L['Invoices']);
        $ui->assign('_title', $config['CompanyName'].' - '.$_L['Invoices']);

        $c = Contacts::details();

        $ui->assign('user',$c);

        $d = ORM::for_table('sys_invoices')->where('userid',$c->id)->find_array();

        $ui->assign('d',$d);

        $ui->assign('total_invoice',count($d));

        //  aSign: \''.$config['currency_code'].' \',

        $ui->assign('xjq',' $(\'.amount\').autoNumeric(\'init\', {

   
    dGroup: '.$config['thousand_separator_placement'].',
    aPad: '.$config['currency_decimal_digits'].',
    pSign: \''.$config['currency_symbol_position'].'\',
    aDec: \''.$config['dec_point'].'\',
    aSep: \''.$config['thousands_sep'].'\'

    });');


        $ui->display('client_invoices.tpl');


        break;

    case 'quotes':
        Event::trigger('client/quotes/');
        $ui->assign('_application_menu', 'quotes');
        $ui->assign('_st', $_L['Quotes']);
        $ui->assign('_title', $config['CompanyName'].' - '.$_L['Quotes']);

        $c = Contacts::details();

        $ui->assign('user',$c);

        $d = ORM::for_table('sys_quotes')->where('userid',$c->id)->find_array();

        $ui->assign('d',$d);

        $ui->assign('total_quotes',count($d));

        $ui->assign('xjq',' $(\'.amount\').autoNumeric(\'init\', {

    aSign: \''.$config['currency_code'].' \',
    dGroup: '.$config['thousand_separator_placement'].',
    aPad: '.$config['currency_decimal_digits'].',
    pSign: \''.$config['currency_symbol_position'].'\',
    aDec: \''.$config['dec_point'].'\',
    aSep: \''.$config['thousands_sep'].'\'

    });');

        $ui->display('client_quotes.tpl');


        break;

    case 'transactions':
        Event::trigger('client/transactions/');
        $ui->assign('_application_menu', 'transactions');
        $ui->assign('_st', $_L['Transactions']);
        $ui->assign('_title', $config['CompanyName'].' - '.$_L['Transactions']);

        $c = Contacts::details();

        $cid = $c->id;

        $ui->assign('user',$c);

        $d = ORM::for_table('sys_transactions')
            ->where_any_is(array(
                array('payerid' => $cid),
                array('payeeid' => $cid)))
            ->find_many();
        $ui->assign('d',$d);

        $ti = ORM::for_table('sys_transactions')
            ->where('payerid',$cid)
            ->sum('cr');
        if($ti == ''){
            $ti = '0';
        }
        $ui->assign('ti',$ti);
        $te = ORM::for_table('sys_transactions')
            ->where('payeeid',$cid)
            ->sum('dr');
        if($te == ''){
            $te = '0';
        }

        $ui->assign('total_quotes',count($d));

        $ui->assign('xjq',' $(\'.amount\').autoNumeric(\'init\', {

    aSign: \''.$config['currency_code'].' \',
    dGroup: '.$config['thousand_separator_placement'].',
    aPad: '.$config['currency_decimal_digits'].',
    pSign: \''.$config['currency_symbol_position'].'\',
    aDec: \''.$config['dec_point'].'\',
    aSep: \''.$config['thousands_sep'].'\'

    });');

        $ui->display('client_transactions.tpl');



        break;


    case 'profile':
        Event::trigger('client/profile/');
        $ui->assign('_application_menu', 'profile');
        $ui->assign('_st', $_L['Profile']);
        $ui->assign('_title', $config['CompanyName'].' - '.$_L['Profile']);

        $c = Contacts::details();

        $ui->assign('user',$c);

        $ui->assign('d',$c);

        $ui->assign('countries',Countries::all($c->country));

        $ui->assign('xfooter',Asset::js(array('contacts/client_profile_edit')));

        $cf = ORM::for_table('crm_customfields')->where('ctype','crm')->order_by_asc('id')->find_many();
        $ui->assign('cf',$cf);


        $ui->display('client_profile.tpl');



        break;


    case 'profile_edit_post':
        Event::trigger('client/profile_edit_post/');
        $c = Contacts::details();
        $id = $c->id;
        $d = ORM::for_table('crm_accounts')->find_one($id);
        if($d){

            $account = _post('account');
            $company = _post('company');

            $email = _post('edit_email');




            $phone = _post('phone');
            $address = _post('address');
            $city = _post('city');
            $state = _post('state');
            $zip = _post('zip');
            $country = _post('country');
            $msg = '';

            if($account == ''){
                $msg .= $_L['Account Name is required']. ' <br>';
            }



            if($email != ($d['email'])){
                $f = ORM::for_table('crm_accounts')->where('email',$email)->find_one();

                if($f){
                    $msg .= $_L['Email already exist'].' <br>';
                }
            }
            if(Validator::Email($email) == false){
                $msg .= $_L['Invalid Email'].' <br>';
            }




            $password = _post('password');




            if($msg == ''){


                $d = ORM::for_table('crm_accounts')->find_one($id);
                $d->account = $account;
                $d->company = $company;


                $d->email = $email;

                $d->phone = $phone;
                $d->address = $address;
                $d->city = $city;
                $d->zip = $zip;
                $d->state = $state;
                $d->country = $country;



                if($password != ''){

                    $d->password = Password::_crypt($password);

                }

                $d->save();





                _msglog('s',$_L['account_updated_successfully']);

                echo $id;
            }
            else{
                echo $msg;
            }

        }
        else{
            r2(U.$myCtrl.'/list', 'e', $_L['Account_Not_Found']);
        }


        break;






    case 'logout':
        Event::trigger('client/logout/');
        $c = Contacts::details();

        Contacts::logout_using_token($c->token);


        setcookie('ib_ut', 'expired', 1, "/");

        r2(U.'client/login/','s','You have successfully logged out.');



        break;

    case 'where':

        r2(U.'client/login/');

        break;


    default:
        echo 'action not defined';
}