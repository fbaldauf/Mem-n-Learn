<?php
echo '<pre>';

testEqual(1, getArabic('I'));
testEqual(2, getArabic('II'));
testEqual(3, getArabic('III'));
testEqual(4, getArabic('IV'));
testEqual(5, getArabic('V'));
testEqual(6, getArabic('VI'));
testEqual(7, getArabic('VII'));
testEqual(8, getArabic('VIII'));
testEqual(9, getArabic('IX'));
testEqual(10, getArabic('X'));
testEqual(11, getArabic('XI'));
testEqual(50, getArabic('L'));
testEqual(51, getArabic('LI'));
testEqual(56, getArabic('LVI'));
testEqual(54, getArabic('LIV'));
testEqual(433, getArabic('CDXXXIII'));

function getArabic($roman) {
	$raMap = ['M' => 1000, 'D' => 500, 'C' => 100, 'L' => 50, 'X' => 10, 'V' => 5, 'I' => 1];
	$result = 0;
	for ($i = strlen($roman) - 1; $i >= 0; $i--) {
		$value = $raMap[substr($roman, $i, 1)];
		$result += $value * (($result > (3 * $value)) ? -1 : 1);
	}
	return $result;
}

function testEqual($exp, $act) {
	$c = ($exp == $act) ? 'color:green' : 'color:red';
	echo '<span style="' . $c . '">' . $exp . "\t" . $act . "</span>\n";
}