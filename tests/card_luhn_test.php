<?php
require_once __DIR__ . '/../Login/ap_card.php';

if (!function_exists('assert_condition')) {
    function assert_condition($condition, $message)
    {
        if (!$condition) {
            throw new RuntimeException($message);
        }
    }
}

try {
    $validNumbers = [
        '4539 1488 0343 6467', // Visa
        '6011111111111117',    // Discover
        '371449635398431',     // American Express
    ];

    $invalidNumbers = [
        '4539 1488 0343 6466',
        '1234567890123456',
        '0000 0000 0000 0000',
    ];

    foreach ($validNumbers as $number) {
        assert_condition(is_valid_card_number($number), sprintf('Expected %s to be valid.', $number));
    }

    foreach ($invalidNumbers as $number) {
        assert_condition(!is_valid_card_number($number), sprintf('Expected %s to be invalid.', $number));
    }

    echo "All Luhn checksum tests passed.\n";
} catch (RuntimeException $e) {
    fwrite(STDERR, $e->getMessage() . "\n");
    exit(1);
}
