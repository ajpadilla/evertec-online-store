<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\PaymentAttempt;
use App\Repositories\OrderRepository;
use App\Repositories\PaymentAttemptRepository;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    private $paymentAttemptRepository;

    /** @var OrderRepository */
    private $orderRepository;

    /** @var PaymentService */
    private $paymentService;

    /** @var PlacetopayWebCheckoutService */
    private $placetopayWebCheckoutService;

    /**
     * PaymentController constructor.
     * @param PaymentAttemptRepository $paymentAttemptRepository
     * @param OrderRepository $orderRepository
     * @param PaymentService $paymentService
     * @param PlacetopayWebCheckoutService $placetopayWebCheckoutService
     */
    public function __construct(
        PaymentAttemptRepository $paymentAttemptRepository,
        OrderRepository $orderRepository,
        PaymentService $paymentService,
        PlacetopayWebCheckoutService $placetopayWebCheckoutService
    )
    {
        $this->paymentAttemptRepository = $paymentAttemptRepository;
        $this->orderRepository = $orderRepository;
        $this->paymentService = $paymentService;
        $this->placetopayWebCheckoutService = $placetopayWebCheckoutService;
    }

    public function show()
    {
        /** @var Order $order */
        $order = $this->orderRepository->getByUserId(Auth::user()->id);

        return view('layouts.orders.pay', compact('order'));
    }

    public function process(Request $request)
    {

        try {
            DB::beginTransaction();
            /** @var Order $order */
            $order = $this->orderRepository->getByUserId(Auth::user()->id);

            /** @var PaymentAttempt $paymentAttemp */
            $paymentAttempt = $order->getFirstPaymentAttempt();

            $data = $this->paymentService->generateRequestData($order);

            $responseCreateRequest = $this->placetopayWebCheckoutService->createRequest($data);
            $responseGetRequestInformation = $this->placetopayWebCheckoutService->getRequestInformation($responseCreateRequest->requestId);

            $this->paymentAttemptRepository->update($paymentAttempt,[
                'external_id' => $responseCreateRequest->requestId,
                'url_process' => $responseCreateRequest->processUrl,
                'state' => $responseGetRequestInformation->status->status,
                'order_id' => $order->id
            ]);
            DB::commit();

            return redirect()->to($responseCreateRequest->processUrl);

        } catch (Exception $exception) {
            DB::rollBack();

            return redirect()->route('pay_order')->withErrors(["order_error"=>"{$exception->getMessage()}"]);
        } catch (GuzzleException $exception) {
            return redirect()->route('pay_order')->withErrors(["order_error"=>"{$exception->getMessage()}"]);
        }

    }

    public function updateOrderState(Request $request)
    {
        try {
            DB::beginTransaction();

            /** @var Order $order */
            $order = $this->orderRepository->getByUserId(Auth::user()->id);

            /** @var PaymentAttempt $paymentAttemp */
            $paymentAttempt = $order->getFirstPaymentAttempt();

            $responseGetRequestInformation = $this->placetopayWebCheckoutService->getRequestInformation($paymentAttempt->external_id);

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

        } catch (Exception $exception) {
            DB::rollBack();

            return redirect()->route('pay_order')->withErrors(["order_error"=>"{$exception->getMessage()}"]);


        } catch (GuzzleException $exception) {
            return redirect()->route('pay_order')->withErrors(["order_error"=>"{$exception->getMessage()}"]);
        }
    }
}
