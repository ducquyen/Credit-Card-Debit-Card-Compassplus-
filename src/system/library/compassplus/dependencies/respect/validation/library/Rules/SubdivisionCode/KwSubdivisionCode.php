<?php
 namespace Respect\Validation\Rules\SubdivisionCode; use Respect\Validation\Rules\AbstractSearcher; class KwSubdivisionCode extends AbstractSearcher { public $haystack = [ 'AH', 'FA', 'HA', 'JA', 'KU', 'MU', ]; public $compareIdentical = true; } 