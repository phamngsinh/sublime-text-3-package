<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPackage;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Repositories\UserSubscriptionRepository;
use Carbon\Carbon;
use Faker\Provider\Address;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use PayPal\Api\Amount;
use PayPal\Api\BillingInfo;
use PayPal\Api\Cost;
use PayPal\Api\Currency;
use PayPal\Api\Details;
use PayPal\Api\InputFields;
use PayPal\Api\Invoice;
use PayPal\Api\InvoiceAddress;
use PayPal\Api\InvoiceItem;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\MerchantInfo;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentTerm;
use PayPal\Api\Phone;
use PayPal\Api\RedirectUrls;
use PayPal\Api\ShippingInfo;
use PayPal\Api\Transaction;
use Modules\PayPalAPI\Facades\PayPal;


/**
 * Class SubscriptionController
 * @package App\Http\Controllers
 */
class SubscriptionController extends Controller
{

    /**
     * @var
     */
    private $_apiContext;
    /**
     * @var UserRepository
     */
    protected $user;
    /**
     * @var Payer
     */
    protected $payer;
    /**
     * @var Item
     */
    protected $item;
    /**
     * @var ItemList
     */
    protected $itemList;
    /**
     * @var RedirectUrls
     */
    protected $urls;
    /**
     * @var Payment
     */
    protected $payment;
    /**
     * @var Amount
     */
    protected $amount;
    /**
     * @var Transaction
     */
    protected $transaction;
    /**
     * @var InputFields
     */
    protected $inputFields;
    /**
     * @var UserSubscriptionRepository
     */
    protected $userSubscription;

    /**
     * SubscriptionController constructor.
     * @param UserRepository $userRepository
     * @param Payer $payer
     * @param Item $item
     * @param ItemList $itemList
     * @param RedirectUrls $urls
     * @param Payment $payment
     * @param Amount $amount
     * @param Transaction $transaction
     * @param InputFields $inputFields
     * @param UserSubscriptionRepository $userSubscriptionRepository
     */

    public function __construct(UserRepository $userRepository, Payer $payer,
                                Item $item, ItemList $itemList, RedirectUrls $urls,
                                Payment $payment, Amount $amount, Transaction $transaction,InputFields $inputFields,
                                UserSubscriptionRepository $userSubscriptionRepository
    )
    {

        $this->middleware('auth');
        $this->user = $userRepository;
        $this->setApiContext();
        $this->payer = $payer;
        $this->item = $item;
        $this->itemList = $itemList;
        $this->urls = $urls;
        $this->payment = $payment;
        $this->amount = $amount;
        $this->transaction = $transaction;
        $this->inputFields = $inputFields;
        $this->userSubscription = $userSubscriptionRepository;
    }

    /**
     * Action for create new
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $user = User::with('subscriptionPackages')->findOrFail(Auth::user()->id);

        if(!$this->validNewUser($user)){
            return redirect()->route('users.edit', Auth::user()->id)->with('message', trans('messages.subscription_note_expired'));
        }
        $package = SubscriptionPackage::getAll()->first();
        $expired = Carbon::now()->addDays($package->expiration);
        return view('subscriptions.index')->with(['user'=>$user,'expired'=>$expired,'package'=>$package]);
    }

    /**
     * Post to create new
     * @param Request $request
     * @return mixed
     */

    public function postPayment(Request $request)
    {
        $package = SubscriptionPackage::findOrFail($request->get('subscription_package_id'));
        $this->payer->setPaymentMethod($request->get('payment'));
        $item = $this->item->setName($package->name)
            ->setCurrency(strtoupper(Auth::user()->currency))
            ->setQuantity(1)
            ->setSku($package->id)// Similar to `item_number` in Classic API
            ->setPrice($request->get('total'));




        $this->itemList->setItems(array($item));
        $this->amount->setCurrency(strtoupper(Auth::user()->currency))
            ->setTotal($request->get('total'));

        $this->transaction->setAmount($this->amount)
            ->setItemList($this->itemList)
            ->setDescription("Payment for " . $package->name)
            ->setInvoiceNumber(uniqid());
        $this->urls->setReturnUrl(route('payment.done'))
            ->setCancelUrl(route('payment.cancel'));
        $this->payment->setIntent("sale")
            ->setPayer($this->payer)
            ->setRedirectUrls($this->urls)
            ->setTransactions(array($this->transaction));
        $this->payment->create($this->_apiContext);
        session(['subscription_package_id'=>$request->get('subscription_package_id')]);
        session(['total'=>$request->get('total')]);
        session(['expiration'=>$request->get('expiration')]);
        return Redirect::to($this->payment->getApprovalLink());

    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getDone(Request $request)
    {
        $user = User::with('subscriptionPackages')->findOrFail(Auth::user()->id);
        $executePayment = $this->userSubscription->createPackage($request,$this->_apiContext,Auth::user());
        // Clear the shopping cart, write to database, send notifications, etc.
        if(!$executePayment){
            return redirect()->route('subscriptions.index');
        }
        return view('subscriptions.success')->with('user',$user);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     */
    public function getCancel()
    {
        // Curse and humiliate the user for cancelling this most sacred payment (yours)
        return redirect()->route('subscriptions.index')->with('success', trans('messages.payment_cancel'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $package = SubscriptionPackage::findOrFail($request->get('subscription_package_id'));
        $this->payer->setPaymentMethod($request->get('payment'));
        $item = $this->item->setName($package->name)
            ->setCurrency(strtoupper(Auth::user()->currency))
            ->setQuantity(1)
            ->setSku($package->id)// Similar to `item_number` in Classic API
            ->setPrice($request->get('total'));

        $this->itemList->setItems(array($item));
        $this->amount->setCurrency(strtoupper(Auth::user()->currency))
            ->setTotal($request->get('total'));

        $this->transaction->setAmount($this->amount)
            ->setItemList($this->itemList)
            ->setDescription("Payment for " . $package->name)
            ->setInvoiceNumber(uniqid());
        $this->urls->setReturnUrl(route('payment.done'))
            ->setCancelUrl(route('payment.cancel'));
        $this->payment->setIntent("sale")
            ->setPayer($this->payer)
            ->setRedirectUrls($this->urls)
            ->setTransactions(array($this->transaction));
        $this->payment->create($this->_apiContext);
        session(['subscription_package_id'=>$request->get('subscription_package_id')]);
        session(['total'=>$request->get('total')]);
        session(['expiration'=>$request->get('expiration')]);
        return Redirect::to($this->payment->getApprovalLink());
    }

    /**
     * Display the specified resource.
     * @TODO update config price
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $package = SubscriptionPackage::findOrFail($id);
        $package->expire = \Carbon\Carbon::now()->addDays($package->expiration)->format('d/m/Y');
        $package->amount =  render_price($package->price);
        return response()->json($package->toArray());
    }


    /**
     * @param $id
     */
    public function upgrade($id)
    {

        $user = User::with(['subscriptionPackages'])->findOrFail(Auth::user()->id);
        if(empty($user->subscriptionPackages)){
            return redirect()->route('subscriptions.index');
        }
        if(!$this->validUpgradeUser($user)){
            return redirect()->route('users.edit', Auth::user()->id)->with('message', trans('messages.subscription_note_expired'));
        }
        $creditUnused = $this->user->getCreditUnused($user);

        $package = SubscriptionPackage::getAll($user->subscriptionPackages->price);
        $expired = Carbon::now()->addDays($package->first()->expiration);
        return view('subscriptions.upgrade')
            ->with(['user' => $user, 'creditUnused' => $creditUnused,'package'=> $package,'expired'=> $expired]);
    }
    public function calculateUpgrade($id)
    {
        $user = User::with(['subscriptionPackages'])->findOrFail(Auth::user()->id);
        $package = SubscriptionPackage::findOrFail($id);
        $creditUnused = $this->user->getCreditUnused($user);
        $package->expired = Carbon::now()->addDays($package->expiration)->format('d/m/Y');
        $package->amount = render_price($package->price);
        $package->creditUnused = $creditUnused;
        $package->renderCreditUnused = render_price($package->creditUnused);
        $package->amountDue = amount_due($package->price,$creditUnused);
        $package->renderTotal = render_total($package->amountDue);
        $package->renderAmountDue = render_price($package->amountDue);

        return response()->json($package->toArray());
    }

    /**
     * @param $paypal
     */
    public function setApiContext()
    {
        $paypal = config('services.paypal');
        $this->_apiContext = PayPal::ApiContext($paypal['client_id'], $paypal['secret']);
        $this->_apiContext->setConfig([
            'mode' => $paypal['mode'],
            'service.EndPoint' => $paypal['end_point'],
            'http.ConnectionTimeOut' => $paypal['timeout'],
            'log.LogEnabled' => $paypal['log'],
            'log.FileName' => $paypal['log_file'],
            'log.LogLevel' => $paypal['log_level']
        ]);
    }

    /**
     * @param $user
     * @return bool
     */
    public function validNewUser($user)
    {
        $validate = false;
        if (empty($user->subscriptionPackages) || ($user->expiration->timestamp < Carbon::now()->timestamp)) {
            $validate =  true;
        }
        if (!empty($user->subscriptionPackages) && $user->countClient == $user->subscriptionPackages->max_client) {
            $validate =  true;
        }
        return $validate;

    }
    /**
     * @param $user
     * @return bool
     */
    public function validUpgradeUser($user)
    {

        if (!empty($user->subscriptionPackages) && ($user->expiration->timestamp < Carbon::now()->timestamp)
            &&  ($user->countClient < $user->subscriptionPackages->max_client) ) {
            return   false;
        }
        return true;

    }

}
