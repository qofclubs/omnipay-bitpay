<?php

namespace Omnipay\BitPay;

use Omnipay\BitPay\Message\CompletePurchaseRequest;
use Omnipay\BitPay\Message\PurchaseRequest;
use Omnipay\Common\AbstractGateway;

/**
 * @method \Omnipay\Common\Message\RequestInterface authorize(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface completeAuthorize(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface capture(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface refund(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface void(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface createCard(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface updateCard(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface deleteCard(array $options = array())
 */
class Gateway extends AbstractGateway
{

    public function getName()
    {
        return 'BitPay';
    }

    public function getDefaultParameters()
    {
        return [
            'testMode' => false,
            'transactionSpeed' => 'high',
        ];
    }

    public function getToken()
    {
        return $this->getParameter('token');
    }

    public function setToken($value)
    {
        return $this->setParameter('token', $value);
    }

    /**
     * @param array $parameters
     * @return PurchaseRequest|\Omnipay\Common\Message\AbstractRequest
     */
    public function purchase(array $parameters = [])
    {
        return $this->createRequest(PurchaseRequest::class, $parameters);
    }

    /**
     * @param array $parameters
     * @return CompletePurchaseRequest|\Omnipay\Common\Message\AbstractRequest
     */
    public function completePurchase(array $parameters = [])
    {
        return $this->createRequest(CompletePurchaseRequest::class, $parameters);
    }

    /**
     * @return string
     */
    public function getTransactionSpeed(): string
    {
        return $this->getParameter('transactionSpeed');
    }

    /**
     * @param string $transactionSpeed high|medium|low
     */
    public function setTransactionSpeed(string $transactionSpeed)
    {
        return $this->setParameter('transactionSpeed', $transactionSpeed);
    }
}
