<?php
 namespace Compassplus\Sdk\Request\XML; use Compassplus\Sdk\Operation\Operation; use Compassplus\Sdk\Request\DataProvider; use Compassplus\Sdk\Service; use UnexpectedValueException; class OrderInformation extends DataProvider { public function __construct(Operation $operation) { $this->operation = $operation; } public function getRequestData() { return $this->getOrderInformation(); } protected function getOrderInformation() { $service = new Service(); $xml = $service->write("TKKPG", [ "Request" => [ "Operation" => "GetOrderInformation", "Language" => $this->operation->merchant->getLanguage(), "Order" => [ "Merchant" => $this->operation->merchant->getId(), "OrderID" => $this->operation->order->getOrderId() ], "SessionID" => $this->operation->order->getSessionId(), "ShowParams" => "true", "ShowOperations" => "true", "ClassicView" => "true" ] ]); if ($xml) { return $xml; } else { throw new UnexpectedValueException("XML is not generated"); } } } 