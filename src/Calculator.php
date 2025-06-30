<?php

/**
 * Kelas Calculator untuk operasi matematika sederhana
 */
class Calculator
{
    /**
     * Penjumlahan dua angka
     */
    public function add($a, $b)
    {
        return $a + $b;
    }

    /**
     * Pengurangan dua angka
     */
    public function subtract($a, $b)
    {
        return $a - $b;
    }

    /**
     * Perkalian dua angka
     */
    public function multiply($a, $b)
    {
        return $a * $b;
    }

    /**
     * Pembagian dua angka
     */
    public function divide($a, $b)
    {
        if ($b == 0) {
            throw new InvalidArgumentException("Tidak bisa membagi dengan nol");
        }
        return $a / $b;
    }
}
