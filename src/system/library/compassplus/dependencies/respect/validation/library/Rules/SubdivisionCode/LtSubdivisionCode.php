<?php
 namespace Respect\Validation\Rules\SubdivisionCode; use Respect\Validation\Rules\AbstractSearcher; class LtSubdivisionCode extends AbstractSearcher { public $haystack = [ 'AL', 'KL', 'KU', 'MR', 'PN', 'SA', 'TA', 'TE', 'UT', 'VL', ]; public $compareIdentical = true; } 