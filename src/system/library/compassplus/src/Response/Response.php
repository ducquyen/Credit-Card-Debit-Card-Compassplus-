<?php
 namespace Compassplus\Sdk\Response; use Exception; class Response { private $response; public function __construct($data) { $this->response = new ResponseStrategy($data); } public function getStatus() { return $this->getString('Status'); } protected function getString($fieldName) { return $this->response->get($fieldName); } public function getOperation() { return $this->getString('Operation'); } public function getAttributeName($parentNode, $fieldName, $attributeValue) { return (string)$this->response->getAttributeName($parentNode, $fieldName, $attributeValue); } protected function getInteger($fieldName) { return (int) $this->response->get($fieldName); } } 