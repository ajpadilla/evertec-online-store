<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\PaymentAttempt;
use App\Repositories\OrderRepository;
use App\Repositories\PaymentAttemptRepository;
use App\Repositories\RepositoryInterface\OrderRepositoryInterface;
use App\Repositories\RepositoryInterface\PaymentAttemptRepositoryInterface;
use App\Services\Payment\PaymentService;
use App\Services\Placetopay\WebCheckout\PlaceToPayWebCheckoutService;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use PDOException;

class PaymentController extends Controller
{
    /** @var PaymentAttemptRepositoryInterface  */
    private $paymentAttemptRepository;

    /** @var OrderRepositoryInterface */
    private $orderRepository;

    /** @var PaymentService */
    private $paymentService;

    /** @var PlaceToPayWebCheckoutService */
    private $placeToPayWebCheckoutService;

    /**
     * PaymentController constructor.
     * @param PaymentAttemptRepositoryInterface $paymentAttemptRepository
     * @param OrderRepositoryInterface $orderRepository
     * @param PaymentService $paymentService
     * @param PlaceToPayWebCheckoutService $placeToPayWebCheckoutService
     */
    public function __construct(
        PaymentAttemptRepositoryInterface $paymentAttemptRepository,
        OrderRepositoryInterface $orderRepository,
        PaymentService $paymentService,
        PlaceToPayWebCheckoutService $placeToPayWebCheckoutService
    )
    {
        $this->paymentAttemptRepository = $paymentAttemptRepository;
        $this->orderRepository = $orderRepository;
        $this->paymentService = $paymentService;
        $this->placeToPayWebCheckoutService = $placeToPayWebCheckoutService;
    }

    /**
     * @return Application|Factory|View
     */
    public function show(): View
    {
        /** @var Order $order */
        $order = $this->orderRepository->getByUserId(Auth::user()->id);

        return view('layouts.orders.pay', compact('order'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function process(Request $request): RedirectResponse
    {

        try {
            DB::beginTransaction();
            /** @var Order $order */
            $order = $this->orderRepository->getByUserId(Auth::user()->id);

            /** @var PaymentAttempt $paymentAttemp */
            $paymentAttempt = $order->getFirstPaymentAttempt();

            $data = $this->paymentService->generateRequestData($order);

            $responseCreateRequest = $this->placeToPayWebCheckoutService->createRequest($data);
            $responseGetRequestInformation = $this->placeToPayWebCheckoutService->getRequestInformation($responseCreateRequest->requestId);

            $this->paymentAttemptRepository->update($paymentAttempt,[
                'external_id' => $responseCreateRequest->requestId,
                'url_process' => $responseCreateRequest->processUrl,
                'state' => $responseGetRequestInformation->status->status,
                'order_id' => $order->id
            ]);
            DB::commit();

            return redirect()->to($responseCreateRequest->processUrl);

        } catch (QueryException | PDOException | ClientException | GuzzleException | ConnectException  $exception) {
            DB::rollBack();
            logger($exception->getMessage());
            logger($exception->getTraceAsString());

            return redirect()->route('pay_order')->withErrors(["order_error"=>"{$exception->getMessage()}"]);
        }
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function updateOrderState(Request $request): RedirectResponse
    {
        try {
            DB::beginTransaction();

            /** @var Order $order */
            $order = $this->orderRepository->getByUserId(Auth::user()->id);

            /** @var PaymentAttempt $paymentAttemp */
            $paymentAttempt = $order->getFirstPaymentAttempt();

            $responseGetRequestInformation = $this->placeToPayWebCheckoutService->getRequestInformation($paymentAttempt->external_id);

            $this->paymentAttemptRepository->update($paymentAttempt,[
                'external_id' => $responseGetRequestInformation->requestId,
                'state' => $responseGetRequestInformation->status->status,
                'order_id' => $order->id
            ]);

            $status = $responseGetRequestInformation->status->status;

            if($status == 'APPROVED'){
                $this->orderRepository->update($order, ['status' => 'PAYED']);
            }

            if($status == 'REJECTED' || $status == 'FAILED'){
                $this->orderRepository->update($order, ['status' => 'REJECTED']);
            }

            DB::commit();

            return redirect()->route('pay_order');

        } catch (ClientException | GuzzleException | ConnectException $exception) {
            DB::rollBack();
            logger($exception->getMessage());
            logger($exception->getTraceAsString());

            return redirect()->route('pay_order')->withErrors(["order_error"=>"{$exception->getMessage()}"]);
        }
    }
}
