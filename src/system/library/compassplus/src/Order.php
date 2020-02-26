<?php
 namespace Compassplus\Sdk; use DateTime; use InvalidArgumentException; use Respect\Validation\Validator as v; use function htmlentities; class Order { private $orderId; private $xId; private $orderDate; private $amount; private $currency; private $description; private $orderStatus; private $sessionId; public function getAmount() { return $this->amount; } public function setAmount($amount) { if (!v::numeric()->positive()->validate($amount)) { throw new InvalidArgumentException('Amount is not numeric'); } $this->amount = $amount * 100; } public function getCurrency() { return $this->currency; } public function setCurrency($currency) { if (!v::numeric()->length(3, 3)->positive()->validate($currency)) { throw new InvalidArgumentException('Currency not in ISO 4217 numeric-3 format'); } $this->currency = (int)$currency; } public function getDescription() { return $this->description; } public function setDescription($description) { $this->description = htmlentities($description); } public function getOrderDate() { return $this->orderDate; } public function setOrderDate(DateTime $orderDate) { $this->orderDate = $orderDate; } public function getOrderId() { return $this->orderId; } public function setOrderId($orderId) { $this->orderId = $orderId; } public function getXId() { return $this->xId; } public function setXId($xId) { $this->xId = $xId; } public function getOrderStatus() { return $this->orderStatus; } public function setOrderStatus($orderStatus) { $this->orderStatus = $orderStatus; } public function getOperationType() { } public function getSessionId() { return $this->sessionId; } public function setSessionId($sessionId) { $this->sessionId = $sessionId; } } 