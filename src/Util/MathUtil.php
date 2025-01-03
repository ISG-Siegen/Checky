<?php

namespace App\Util;

class MathUtil
{
    /**
     * Calculates the dot product of two integer arrays.
     *
     * @param int[] $arr1 The first array of integers.
     * @param int[] $arr2 The second array of integers.
     * @return int The dot product of the two arrays.
     */
    public static function dot(array $arr1, array $arr2): int
    {
        $dot = 0;
        foreach ($arr1 as $index => $value) {
            $dot += $value * $arr2[$index]; // Multiply corresponding elements and sum the results.
        }
        return $dot;
    }

    /**
     * Calculates the Euclidean norm (magnitude) of an integer array.
     *
     * @param int[] $arr The array of integers.
     * @return float The Euclidean norm of the array.
     */
    public static function norm(array $arr): float
    {
        $squaredSum = 0;
        foreach ($arr as $value) {
            $squaredSum += $value * $value; // Sum of squares of array elements.
        }
        return sqrt($squaredSum); // Square root of the sum gives the norm.
    }
}
