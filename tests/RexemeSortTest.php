<?php

namespace izucken\regexTools;

use PHPUnit\Framework\TestCase;

class RexemeSortTest extends TestCase
{
    private $rexemeSort;

    function setUp () : void {
        $this->rexemeSort = new RexemeSort();
        parent::setUp();
    }

    static $rexemeCases = [
        "ru/album.*/(1242x2688|1125x2436|full-hd-iphone|750x1334|2048x1536)" => "18127",
        "ru/album/.*/(1242x2688|1125x2436|full-hd-iphone|750x1334|2048x1536)" => "18127",
        "ru/album/goroda" => "17",
        "ru/album/goroda$" => "1",
        "ru/tag" => "17",
        "ru/?.*" => "19",
    ];

    function testRexemeCases () {
        foreach ( self::$rexemeCases as $case => $result ) {
            $this->assertEquals( $result, $this->rexemeSort->regexToLexeme( $case ) );
        }
    }

    static $cases = [
        [
            "source" => [
                "ru/album.*/(1242x2688|1125x2436|full-hd-iphone|750x1334|2048x1536)",
                "ru/album/.*/(1242x2688|1125x2436|full-hd-iphone|750x1334|2048x1536)",
                "ru/album/goroda",
                "ru/album/goroda$",
                "ru/?.*",
            ],
            "pad" => [
                "ru/album.*/(1242x2688|1125x2436|full-hd-iphone|750x1334|2048x1536)" => "18127",
                "ru/album/.*/(1242x2688|1125x2436|full-hd-iphone|750x1334|2048x1536)" => "18127",
                "ru/album/goroda" => "17777",
                "ru/album/goroda$" => "11111",
                "ru/?.*" => "19999",
            ],
            "target" => [
                "ru/?.*",
                "ru/album.*/(1242x2688|1125x2436|full-hd-iphone|750x1334|2048x1536)",
                "ru/album/.*/(1242x2688|1125x2436|full-hd-iphone|750x1334|2048x1536)",
                "ru/album/goroda",
                "ru/album/goroda$",
            ],
        ],
        [
            "source" => [
                "ru/album/best_wallpapers$",
                "ru/album/best_wallpapers",
            ],
            "pad" => [
                "ru/album/best_wallpapers" => "17",
                "ru/album/best_wallpapers$" => "11",
            ],
            "target" => [
                "ru/album/best_wallpapers",
                "ru/album/best_wallpapers$",
            ],
        ],
    ];

    function testRexemePadCases () {
        foreach ( self::$cases as $case ) {
            $this->assertEquals(
                $case['pad'],
                $this->rexemeSort->equalizeRexemeMap(
                    $this->rexemeSort->regexArrayToRexemeMap(
                        $case['source']
                    )
                )
            );
        }
    }

    function testSortByRexemeCases () {
        foreach ( self::$cases as $case ) {
            $this->assertEquals(
                $case['target'],
                $this->rexemeSort->sortByRexeme( $case['source'] )
            );
        }
    }
}
