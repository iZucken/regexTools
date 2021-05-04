<?php

namespace izucken\regexTools;

class RexemeSort
{
    const MAP = [
        "#[\w\d\s\-\/]+#" => "1",
        "#\(1(\|1)*\)#" => "2",
        "#\[[^\]]+\]#" => "2",
        "#1\?#" => "1",
        "#\.#" => "3",
        "#2(\*|\+)#" => "4",
        "#3(\*|\+)#" => "9",
        "#(?<!9|\\$|9\\$)$#" => "7",
        "#\\$$#" => "",
        "#91#" => "81",
    ];

    function regexToLexeme ( string $regex ) : string {
        return preg_replace( array_keys(self::MAP), array_values(self::MAP), $regex );
    }

    function regexArrayToRexemeMap ( array $regexArray ) : array {
        $rexemeMap = [];
        foreach ( $regexArray as $sample ) {
            $rexemeMap[$sample] = $this->regexToLexeme( $sample );
        }
        return $rexemeMap;
    }

    function maxElementLength ( array $array ) : int {
        $maxLength = 0;
        foreach ( $array as $index => $value ) {
            $maxLength = max( $maxLength, strlen( $value ) );
        }
        return $maxLength;
    }

    function equalizeRexemeMap ( array $rexemeMap ) : array {
        $maxLength = $this->maxElementLength( $rexemeMap );
        $equalizedMap = [];
        foreach ( $rexemeMap as $regex => $lexeme ) {
            $equalizedMap[$regex] = str_pad(
                $lexeme,
                $maxLength,
                substr( $lexeme, strlen( $lexeme ) - 1, 1 )
            );
        }
        return $equalizedMap;
    }

    function rexemeSortFunction ( array $rexemeMap ) : array {
        uksort( $rexemeMap, function ( $a, $b ) use ( &$rexemeMap ) {
            return ( $rexemeMap[$b] <=> $rexemeMap[$a] ) ? : ( strlen( $a ) <=> strlen( $b ) );
        } );
        return $rexemeMap;
    }

    function sortByRexeme ( array $regexArray ) : array {
        return array_keys(
            $this->rexemeSortFunction(
                $this->equalizeRexemeMap(
                    $this->regexArrayToRexemeMap( $regexArray )
                )
            )
        );
    }
}