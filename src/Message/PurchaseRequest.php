<?php

/*
 * PayPal driver for Omnipay PHP payment library
 *
 * @link      https://github.com/hiqdev/omnipay-paypal
 * @package   omnipay-paypal
 * @license   MIT
 * @copyright Copyright (c) 2015-2016, HiQDev (http://hiqdev.com/)
 */

namespace Omnipay\BitPay\Message;

use BitPaySDKLight\Model\Invoice\Invoice;
use Omnipay\BitPay\Gateway;
use Omnipay\Common\Exception\InvalidRequestException;

/**
 * PayPal Complete Purchase Request.
 */
class PurchaseRequest extends AbstractRequest
{
    /**
     * Get the data for this request.
     *
     * @return array request data
     * @throws InvalidRequestException
     */
    public function getData()
    {
        $this->validate(
            'token',
            'transactionId',
            'description',
            'amount',
            'currency',
            'returnUrl',
            'cancelUrl',
            'notifyUrl'
        );

        return [
            'amount' => $this->getAmount(),
            'currency_code' => strtoupper($this->getCurrency()),
            'notifyUrl' => $this->getNotifyUrl(),
            'closeUrl' => $this->getCancelUrl(),
            'return' => $this->getReturnUrl(),
            'item_number' => $this->getTransactionId(),
            'item_name' => $this->getDescription(),
        ];
    }

    /**
     * Send the request with specified data.
     *
     * @param mixed $data The data to send
     *
     * @return PurchaseResponse
     * @throws InvalidRequestException
     * @throws \BitPaySDKLight\Exceptions\BitPayException
     * @throws \BitPaySDKLight\Exceptions\InvoiceCreationException
     */
    public function sendData($data)
    {
        $buyer = new Buyer();

        $bitpay = $this->getClient();
        $basicInvoice = new Invoice((float)$this->getAmount(), $this->getCurrency());
        $basicInvoice->setFullNotifications(true);
        $basicInvoice->setTransactionSpeed(Gateway::TRANSACTION_SPEED_HIGH);
        $basicInvoice->setRedirectURL($this->getReturnUrl());
        $basicInvoice->setAutoRedirect(true);
        $basicInvoice->setCloseURL($this->getCancelUrl());
        $basicInvoice->setNotificationURL($this->getNotifyUrl());
        $basicInvoice->setPosData(json_encode($this->buildPosData()));
        $basicInvoice->setBuyer($buyer);
        $basicInvoice->setItemDesc($this->getDescription());

        $invoice = $bitpay->createInvoice($basicInvoice);

        return $this->response = new PurchaseResponse($this, $invoice);
    }

    /**
     * @param array $data
     *
     * @return array
     * @throws InvalidRequestException
     */
    protected function buildPosData($data = [])
    {
        return parent::buildPosData([
            'c' => $this->getDescription(),
            's' => $this->getAmount(),
            'u' => $this->getTransactionId(),
        ]);
    }
}
