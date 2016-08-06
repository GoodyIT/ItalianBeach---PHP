<?php
/**
 * File Paypal.php.
 *
 * @author Marcio Camello <marciocamello@outlook.com>
 * @see https://github.com/paypal/rest-api-sdk-php/blob/master/sample/
 * @see https://developer.paypal.com/webapps/developer/applications/accounts
 */

namespace common\component;

//define('PP_CONFIG_PATH', __DIR__);

use Yii;
use yii\base\ErrorException;
use yii\helpers\ArrayHelper;
use yii\base\Component;

use PayPal\Api\Address;
use PayPal\Api\CreditCard;
use PayPal\Api\Amount;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\Transaction;
use PayPal\Api\FundingInstrument;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\RedirectUrls;
use PayPal\Rest\ApiContext;
use PayPal\Api\PaymentExecution;

class Paypal extends Component
{
    //region Mode (production/development)
    const MODE_SANDBOX = 'sandbox';
    const MODE_LIVE    = 'live';
    //endregion

    //region Log levels
    /*
     * Logging level can be one of FINE, INFO, WARN or ERROR.
     * Logging is most verbose in the 'FINE' level and decreases as you proceed towards ERROR.
     */
    const LOG_LEVEL_FINE  = 'FINE';
    const LOG_LEVEL_INFO  = 'INFO';
    const LOG_LEVEL_WARN  = 'WARN';
    const LOG_LEVEL_ERROR = 'ERROR';
    //endregion

    //region API settings
    public $clientId;
    public $clientSecret;
    public $isProduction = false;
    public $currency = 'USD';
    public $config = [];

    /** @var ApiContext */
    private $_apiContext = null;

    /**
     * @setConfig 
     * _apiContext in init() method
     */
    public function init()
    {
        $this->setConfig();
    }

    /**
     * @inheritdoc
     */
    private function setConfig()
    {
        // ### Api context
        // Use an ApiContext object to authenticate
        // API calls. The clientId and clientSecret for the
        // OAuthTokenCredential class can be retrieved from
        // developer.paypal.com

        $this->_apiContext = new ApiContext(
            new OAuthTokenCredential(
                $this->clientId,
                $this->clientSecret
            ), 'Request' . time()
        );

        // #### SDK configuration

        // Comment this line out and uncomment the PP_CONFIG_PATH
        // 'define' block if you want to use static file
        // based configuration
        $this->_apiContext->setConfig(ArrayHelper::merge(
            [
                'mode'                      => self::MODE_LIVE, // development (sandbox) or production (live) mode
                'http.ConnectionTimeOut'    => 30,
                'http.Retry'                => 1,
                'log.LogEnabled'            => YII_DEBUG ? 1 : 0,
                'log.FileName'              => Yii::getAlias('@runtime/logs/paypal.log'),
                'log.LogLevel'              => self::LOG_LEVEL_FINE,
                'validation.level'          => 'log',
                'cache.enabled'             => 'true'
            ],$this->config)
        );

        // Set file name of the log if present
        if (isset($this->config['log.FileName'])
            && isset($this->config['log.LogEnabled'])
            && ((bool)$this->config['log.LogEnabled'] == true)
        ) {
            $logFileName = \Yii::getAlias($this->config['log.FileName']);

            if ($logFileName) {
                if (!file_exists($logFileName)) {
                    if (!touch($logFileName)) {
                        throw new ErrorException('Can\'t create paypal.log file at: ' . $logFileName);
                    }
                }
            }

            $this->config['log.FileName'] = $logFileName;
        }

        return $this->_apiContext;
    }

    public function saveCard($params) {

        $card = new CreditCard();
        $card->setType($params['type']);
        $card->setNumber($params['number']);
        $card->setExpireMonth($params['expire_month']);
        $card->setExpireYear($params['expire_year']);
        $card->setCvv2($params['cvv2']);

        $card->create(getApiContext());
        return $card;
    }

    /**
     *
     * @param string $cardId credit card id obtained from
     * a previous create API call.
     */
    public function getCreditCard($cardId) {
        return CreditCard::get($cardId, getApiContext());
    }


    /**
     * Create a payment using a previously obtained
     * credit card id. The corresponding credit
     * card is used as the funding instrument.
     *
     * @param string $creditCardId credit card id
     * @param string $total Payment amount with 2 decimal points
     * @param string $currency 3 letter ISO code for currency
     * @param string $paymentDesc
     */

    public function makePaymentUsingCC($creditCard, $total, $currency, $paymentDesc) {

        $ccToken = new CreditCardToken();
        $ccToken->setCreditCardId($creditCardId);

        $fi = new FundingInstrument();
        $fi->setCreditCardToken($ccToken);

        $payer = new Payer();
        $payer->setPaymentMethod("credit_card");
        $payer->setFundingInstruments(array($fi));

        // Specify the payment amount.
        $amount = new Amount();
        $amount->setCurrency($currency);
        $amount->setTotal($total);
        // ###Transaction
        // A transaction defines the contract of a
        // payment - what is the payment for and who
        // is fulfilling it. Transaction is created with
        // a `Payee` and `Amount` types
        $transaction = new Transaction();
        $transaction->setAmount($amount);
        $transaction->setDescription($paymentDesc);

        $payment = new Payment();
        $payment->setIntent("sale");
        $payment->setPayer($payer);
        $payment->setTransactions(array($transaction));

        $payment->create(getApiContext());
        return $payment;
    }

    /**
     * Create a payment using the buyer's paypal
     * account as the funding instrument. Your app
     * will have to redirect the buyer to the paypal
     * website, obtain their consent to the payment
     * and subsequently execute the payment using
     * the execute API call.
     *
     * @param string $total	payment amount in DDD.DD format
     * @param string $currency	3 letter ISO currency code such as 'USD'
     * @param string $paymentDesc	A description about the payment
     * @param string $returnUrl	The url to which the buyer must be redirected
     * 				to on successful completion of payment
     * @param string $cancelUrl	The url to which the buyer must be redirected
     * 				to if the payment is cancelled
     * @return \PayPal\Api\Payment
     */
    public function makePaymentUsingPayPal($total, $currency, $paymentDesc, $returnUrl, $cancelUrl) {
        $payer = new Payer();
        $payer->setPaymentMethod("paypal");

        // Specify the payment amount.
        $amount = new Amount();
        $amount->setCurrency($currency);
        $amount->setTotal($total);

        // ###Transaction
        // A transaction defines the contract of a
        // payment - what is the payment for and who
        // is fulfilling it. Transaction is created with
        // a `Payee` and `Amount` types
        $transaction = new Transaction();
        $transaction->setAmount($amount);
        $transaction->setDescription($paymentDesc);

        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl($returnUrl);
        $redirectUrls->setCancelUrl($cancelUrl);

        $payment = new Payment();
        $payment->setRedirectUrls($redirectUrls);
        $payment->setIntent("sale");
        $payment->setPayer($payer);
        $payment->setTransactions(array($transaction));

        try {
            $payment->create($this->_apiContext);
        } catch (payPalConnectionException $ex) {
            echo $ex->getCode(); // Prints the Error Code
            echo $ex->getData(); // Prints the detailed error message
            die($ex);
        } catch (Exception $ex) {
            die($ex);
        }

        return $payment;
    }

    public function payDemo(){
        $payer = new Payer();
        $payer->setPaymentMethod("paypal");

        $amount = new Amount();
        $amount->setCurrency("USD");
        $amount->setTotal("12");

        $transaction = new Transaction();
        $transaction->setDescription("creating a payment");
        $transaction->setAmount($amount);

      //  $baseUrl = getBaseUrl();
        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl("https://devtools-paypal.com/guide/pay_paypal/php?success=true");
        $redirectUrls->setCancelUrl("https://devtools-paypal.com/guide/pay_paypal/php?cancel=true");

        $payment = new Payment();
        $payment->setIntent("sale");
        $payment->setPayer($payer);
        $payment->setRedirectUrls($redirectUrls);
        $payment->setTransactions(array($transaction));

        return $payment->create($this->_apiContext);
    }


    /**
     * Completes the payment once buyer approval has been
     * obtained. Used only when the payment method is 'paypal'
     *
     * @param string $paymentId id of a previously created
     * 		payment that has its payment method set to 'paypal'
     * 		and has been approved by the buyer.
     *
     * @param string $payerId PayerId as returned by PayPal post
     * 		buyer approval.
     */
    public function executePayment($paymentId, $payerId) {

        $payment = $this->getPaymentDetails($paymentId);
        $paymentExecution = new PaymentExecution();
        $paymentExecution->setPayerId($payerId);
        $payment = $payment->execute($paymentExecution, $this->_apiContext);

        return $payment;
    }

    /**
     * Retrieves the payment information based on PaymentID from Paypal APIs
     *
     * @param $paymentId
     *
     * @return Payment
     */
    public function getPaymentDetails($paymentId) {
        $payment = Payment::get($paymentId, $this->_apiContext);
        return $payment;
    }

}
