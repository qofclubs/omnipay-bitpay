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
use BitPaySDKLight\Model\Invoice\InvoiceStatus;
use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

/**
 * BitPay Complete Purchase Response.
 */
class CompletePurchaseResponse extends AbstractResponse
{
    /**
     * @var CompletePurchaseRequest
     */
    public $request;

    /**
     * @var Invoice
     */
    protected $data;

    public function __construct(RequestInterface $request, Invoice $data)
    {
        parent::__construct($request, $data);

        $this->checkPosData();
    }

    protected function checkPosData()
    {
        $data = $this->getPosData();
        if ($data === null || !isset($data['posData'])) {
            throw new InvalidResponseException('Failed to decode JSON');
        }
    }

    /**
     * @return array
     */
    public function getPosData()
    {
        return @json_decode($this->data->getPosData(), true);
    }

    /**
     * Whether the payment is successful.
     * @return boolean
     */
    public function isSuccessful()
    {
        return $this->data->getStatus() === InvoiceStatus::Confirmed || $this->data->getStatus(
            ) === InvoiceStatus::Complete;
    }

    /**
     * {@inheritdoc}
     * @return string
     */
    public function getTransactionId()
    {
        return $this->getPosData()['posData']['u'];
    }

    /**
     * Retruns the transatcion status.
     * @return string
     */
    public function getTransactionStatus()
    {
        return $this->data->getStatus();
    }

    /**
     * {@inheritdoc}
     * @return string
     */
    public function getAmount()
    {
        if ($this->isStatusExceptional()) {
            return (string)$this->data->getAmount();
        }

        return (string)$this->data->getPrice();
    }

    /**
     * @return bool
     */
    public function isStatusExceptional()
    {
        return in_array($this->data->getExceptionStatus(), ['paidPartial', 'paidOver']);
    }

    /**
     * @return string
     */
    public function getRequestedAmount()
    {
        return $this->data->getPrice();
    }

    /**
     * {@inheritdoc}
     * @return string
     */
    public function getFee()
    {
        return '';
    }

    /**
     * Returns the currency.
     * @return string
     */
    public function getCurrency()
    {
        return strtoupper($this->data->getCurrency());
    }

    /**
     * Returns the payer "name/email".
     * @return string
     */
    public function getPayer()
    {
        $buyer = $this->data->getBuyer();

        return $this->getTransactionReference() . ' ' . $buyer->getName() . ' / ' . $buyer->getEmail();
    }

    /**
     * {@inheritdoc}
     * @return string
     */
    public function getTransactionReference()
    {
        return $this->data->getId();
    }

    /**
     * Returns the payment date.
     * @return string
     */
    public function getTime()
    {
        $time = $this->data->getInvoiceTime();

        if ($time instanceof \DateTime) {
            return $time->format('c');
        }

        return date('c', $time / 1000); // time in ms
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data->toArray();
    }
}
