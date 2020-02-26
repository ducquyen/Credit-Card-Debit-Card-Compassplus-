<?php
namespace GuzzleHttp\Cookie; use GuzzleHttp\Message\RequestInterface; use GuzzleHttp\Message\ResponseInterface; use GuzzleHttp\ToArrayInterface; class CookieJar implements CookieJarInterface, ToArrayInterface { private $cookies = []; private $strictMode; public function __construct($strictMode = false, $cookieArray = []) { $this->strictMode = $strictMode; foreach ($cookieArray as $cookie) { if (!($cookie instanceof SetCookie)) { $cookie = new SetCookie($cookie); } $this->setCookie($cookie); } } public static function fromArray(array $cookies, $domain) { $cookieJar = new self(); foreach ($cookies as $name => $value) { $cookieJar->setCookie(new SetCookie([ 'Domain' => $domain, 'Name' => $name, 'Value' => $value, 'Discard' => true ])); } return $cookieJar; } public static function getCookieValue($value) { if (substr($value, 0, 1) !== '"' && substr($value, -1, 1) !== '"' && strpbrk($value, ';,') ) { $value = '"' . $value . '"'; } return $value; } public function toArray() { return array_map(function (SetCookie $cookie) { return $cookie->toArray(); }, $this->getIterator()->getArrayCopy()); } public function clear($domain = null, $path = null, $name = null) { if (!$domain) { $this->cookies = []; return; } elseif (!$path) { $this->cookies = array_filter( $this->cookies, function (SetCookie $cookie) use ($path, $domain) { return !$cookie->matchesDomain($domain); } ); } elseif (!$name) { $this->cookies = array_filter( $this->cookies, function (SetCookie $cookie) use ($path, $domain) { return !($cookie->matchesPath($path) && $cookie->matchesDomain($domain)); } ); } else { $this->cookies = array_filter( $this->cookies, function (SetCookie $cookie) use ($path, $domain, $name) { return !($cookie->getName() == $name && $cookie->matchesPath($path) && $cookie->matchesDomain($domain)); } ); } } public function clearSessionCookies() { $this->cookies = array_filter( $this->cookies, function (SetCookie $cookie) { return !$cookie->getDiscard() && $cookie->getExpires(); } ); } public function setCookie(SetCookie $cookie) { $result = $cookie->validate(); if ($result !== true) { if ($this->strictMode) { throw new \RuntimeException('Invalid cookie: ' . $result); } else { $this->removeCookieIfEmpty($cookie); return false; } } foreach ($this->cookies as $i => $c) { if ($c->getPath() != $cookie->getPath() || $c->getDomain() != $cookie->getDomain() || $c->getName() != $cookie->getName() ) { continue; } if (!$cookie->getDiscard() && $c->getDiscard()) { unset($this->cookies[$i]); continue; } if ($cookie->getExpires() > $c->getExpires()) { unset($this->cookies[$i]); continue; } if ($cookie->getValue() !== $c->getValue()) { unset($this->cookies[$i]); continue; } return false; } $this->cookies[] = $cookie; return true; } public function count() { return count($this->cookies); } public function getIterator() { return new \ArrayIterator(array_values($this->cookies)); } public function extractCookies( RequestInterface $request, ResponseInterface $response ) { if ($cookieHeader = $response->getHeaderAsArray('Set-Cookie')) { foreach ($cookieHeader as $cookie) { $sc = SetCookie::fromString($cookie); if (!$sc->getDomain()) { $sc->setDomain($request->getHost()); } $this->setCookie($sc); } } } public function addCookieHeader(RequestInterface $request) { $values = []; $scheme = $request->getScheme(); $host = $request->getHost(); $path = $request->getPath(); foreach ($this->cookies as $cookie) { if ($cookie->matchesPath($path) && $cookie->matchesDomain($host) && !$cookie->isExpired() && (!$cookie->getSecure() || $scheme == 'https') ) { $values[] = $cookie->getName() . '=' . self::getCookieValue($cookie->getValue()); } } if ($values) { $request->setHeader('Cookie', implode('; ', $values)); } } private function removeCookieIfEmpty(SetCookie $cookie) { $cookieValue = $cookie->getValue(); if ($cookieValue === null || $cookieValue === '') { $this->clear( $cookie->getDomain(), $cookie->getPath(), $cookie->getName() ); } } } 