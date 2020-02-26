<?php
 namespace Monolog\Formatter; use Exception; use Monolog\Utils; class NormalizerFormatter implements FormatterInterface { const SIMPLE_DATE = "Y-m-d H:i:s"; protected $dateFormat; public function __construct($dateFormat = null) { $this->dateFormat = $dateFormat ?: static::SIMPLE_DATE; if (!function_exists('json_encode')) { throw new \RuntimeException('PHP\'s json extension is required to use Monolog\'s NormalizerFormatter'); } } public function format(array $record) { return $this->normalize($record); } public function formatBatch(array $records) { foreach ($records as $key => $record) { $records[$key] = $this->format($record); } return $records; } protected function normalize($data, $depth = 0) { if ($depth > 9) { return 'Over 9 levels deep, aborting normalization'; } if (null === $data || is_scalar($data)) { if (is_float($data)) { if (is_infinite($data)) { return ($data > 0 ? '' : '-') . 'INF'; } if (is_nan($data)) { return 'NaN'; } } return $data; } if (is_array($data)) { $normalized = array(); $count = 1; foreach ($data as $key => $value) { if ($count++ > 1000) { $normalized['...'] = 'Over 1000 items ('.count($data).' total), aborting normalization'; break; } $normalized[$key] = $this->normalize($value, $depth+1); } return $normalized; } if ($data instanceof \DateTime) { return $data->format($this->dateFormat); } if (is_object($data)) { if ($data instanceof Exception || (PHP_VERSION_ID > 70000 && $data instanceof \Throwable)) { return $this->normalizeException($data); } if (method_exists($data, '__toString') && !$data instanceof \JsonSerializable) { $value = $data->__toString(); } else { $value = $this->toJson($data, true); } return sprintf("[object] (%s: %s)", Utils::getClass($data), $value); } if (is_resource($data)) { return sprintf('[resource] (%s)', get_resource_type($data)); } return '[unknown('.gettype($data).')]'; } protected function normalizeException($e) { if (!$e instanceof Exception && !$e instanceof \Throwable) { throw new \InvalidArgumentException('Exception/Throwable expected, got '.gettype($e).' / '.Utils::getClass($e)); } $data = array( 'class' => Utils::getClass($e), 'message' => $e->getMessage(), 'code' => (int) $e->getCode(), 'file' => $e->getFile().':'.$e->getLine(), ); if ($e instanceof \SoapFault) { if (isset($e->faultcode)) { $data['faultcode'] = $e->faultcode; } if (isset($e->faultactor)) { $data['faultactor'] = $e->faultactor; } if (isset($e->detail) && (is_string($e->detail) || is_object($e->detail) || is_array($e->detail))) { $data['detail'] = is_string($e->detail) ? $e->detail : reset($e->detail); } } $trace = $e->getTrace(); foreach ($trace as $frame) { if (isset($frame['file'])) { $data['trace'][] = $frame['file'].':'.$frame['line']; } } if ($previous = $e->getPrevious()) { $data['previous'] = $this->normalizeException($previous); } return $data; } protected function toJson($data, $ignoreErrors = false) { return Utils::jsonEncode($data, null, $ignoreErrors); } } 