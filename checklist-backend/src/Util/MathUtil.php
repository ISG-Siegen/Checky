<?php

namespace App\Util;

class MathUtil
{

    /**
     * @param int[] $arr1
     * @param int[] $arr2
     */
    public static function dot(array $arr1, array $arr2): int
    {
        $dot = 0;
        foreach ($arr1 as $index => $value) {
            $dot += $value * $arr2[$index];
        }
        return $dot;
    }

    /**
     * @param int[] $arr
     */
    public static function norm(array $arr): float
    {
        $squaredSum = 0;
        foreach ($arr as $value) {
            $squaredSum += $value * $value;
        }
        return sqrt($squaredSum);
    }
}
