<?php
 namespace Respect\Validation\Rules\SubdivisionCode; use Respect\Validation\Rules\AbstractSearcher; class UaSubdivisionCode extends AbstractSearcher { public $haystack = [ '05', '07', '09', '12', '14', '18', '21', '23', '26', '30', '32', '35', '40', '43', '46', '48', '51', '53', '56', '59', '61', '63', '65', '68', '71', '74', '77', ]; public $compareIdentical = true; } 