<?php
if (!isset($_SERVER['REQUEST_METHOD'])) {
    $_SERVER['REQUEST_METHOD'] = 'GET';
}

if (!isset($_SERVER['HTTP_USER_AGENT'])) {
    $_SERVER['HTTP_USER_AGENT'] = 'cli-test-agent';
}

require_once __DIR__ . '/../ap_card.php';

$tests = [
    ['input' => '4111111111111111', 'expected' => true, 'description' => 'Valid Visa'],
    ['input' => '4242 4242 4242 4242', 'expected' => true, 'description' => 'Valid Visa with spaces'],
    ['input' => '5500000000000004', 'expected' => true, 'description' => 'Valid MasterCard'],
    ['input' => '4111111111111110', 'expected' => false, 'description' => 'Invalid check digit'],
    ['input' => 'not-a-number', 'expected' => false, 'description' => 'Non-numeric characters'],
];

$allPassed = true;

foreach ($tests as $test) {
    $actual = is_valid_card_number($test['input']);
    $passed = $actual === $test['expected'];
    $allPassed = $allPassed && $passed;

    $status = $passed ? 'PASS' : 'FAIL';
    $expectedText = $test['expected'] ? 'true' : 'false';
    $actualText = $actual ? 'true' : 'false';

    echo sprintf(
        "%s: %s (expected %s, got %s)\n",
        $status,
        $test['description'],
        $expectedText,
        $actualText
    );
}

if (!$allPassed) {
    exit(1);
}

exit(0);
