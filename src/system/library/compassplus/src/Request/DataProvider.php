<?php
 namespace Compassplus\Sdk\Request; use Compassplus\Sdk\Operation\Operation; abstract class DataProvider { protected $operation; public function __construct(Operation $operation) { $this->operation = $operation; } abstract public function getRequestData(); } 