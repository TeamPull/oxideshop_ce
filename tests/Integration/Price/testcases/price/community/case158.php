<?php
/**
 * Price enter mode: netto
 * Price view mode: brutto
 * Product count: 1
 * VAT info: 20%
 * Currency rate: 1.0
 * Discounts: count
 *  1. shop; %; 10; general
 *  2. shop; %; 10; general
 * Short description: Tests if the additive discount mode calculates as expected.
 */
$aData = array (
    'articles' => array (
        0 => array (
            'oxid'                     => '1001_a',
            'oxprice'                  => 100,
            'oxvat'                    => 20,
        )
    ),
    'discounts' => array (
        0 => array (
            'oxid'         => 'percent_1',
            'oxaddsum'     => 10,
            'oxaddsumtype' => '%',
            'oxprice'    => 0,
            'oxpriceto' => 99999,
            'oxamount' => 0,
            'oxamountto' => 99999,
            'oxactive' => 1,
            'oxarticles' => array ( '1001_a' ),
        ),
        1 => array (
            'oxid'         => 'percent_2',
            'oxaddsum'     => 10,
            'oxaddsumtype' => '%',
            'oxprice'    => 0,
            'oxpriceto' => 99999,
            'oxamount' => 0,
            'oxamountto' => 99999,
            'oxactive' => 1,
            'oxarticles' => array ( '1001_a' ),
        ),
    ),
    'expected' => array (
        '1001_a' => array (
            'base_price' => '100,00',
            'price' => '96,00',
        )
    ),
    'options' => array (
        'config' => array(
            'blEnterNetPrice' => true,
            'blShowNetPrice' => false,
            'calculateDiscountsMultiplicative' => false,
        ),
        'activeCurrencyRate' => 1,
    ),
);