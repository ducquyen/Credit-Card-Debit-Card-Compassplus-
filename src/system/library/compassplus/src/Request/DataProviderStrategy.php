<?php
 namespace Compassplus\Sdk\Request; use Compassplus\Sdk\Config\Config; use Compassplus\Sdk\Operation\Operation; use Exception; class DataProviderStrategy { private $data; private $operation; private $operationType; private $dataFormat; public function __construct($operationType, Operation $operation) { $this->operationType = $operationType; $this->operation = $operation; $this->dataFormat = Config::getDataFormat(); $this->payload(); } private function payload() { switch ($this->dataFormat) { case Config::XML: $this->data = new \Compassplus\Sdk\Request\XML\DataOperationStrategy( $this->operationType, $this->operation ); break; default: throw new Exception('Invalid format'); } } public function getPayload() { return $this->data->getRequestPayload(); } } 