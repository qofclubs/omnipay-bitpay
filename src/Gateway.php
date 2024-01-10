<?php
/**
 * Yandex.Money driver for Omnipay PHP payment library
 *
 * @link      https://github.com/hiqdev/omnipay-yandexmoney
 * @package   omnipay-yandexmoney
 * @license   MIT
 * @copyright Copyright (c) 2017, HiQDev (http://hiqdev.com/)
 */

namespace Omnipay\BitPay;

use Omnipay\BitPay\Message\CompletePurchaseRequest;
use Omnipay\BitPay\Message\PurchaseRequest;
use Omnipay\Common\AbstractGateway;

/**
 */
class Gateway extends AbstractGateway
{

    const TRANSACTION_SPEED_HIGH = 'high';
    const TRANSACTION_SPEED_MEDIUM = 'medium';
    const TRANSACTION_SPEED_LOW = 'low';


    public function getName()
    {
        return 'BitPay';
    }

    public function getDefaultParameters()
    {
        return [
            'testMode' => false,
            'transactionSpeed' => self::TRANSACTION_SPEED_LOW
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
