<?php

use Illuminate\Support\Facades\DB;

if (!function_exists('generateKode')) {

    function generateKode($prefix, $table, $column)
    {
        $tanggal = now()->format('dmy');

        $last = DB::table($table)
            ->whereDate('created_at', now())
            ->where($column, 'like', $prefix . $tanggal . '%')
            ->orderBy($column, 'desc')
            ->first();

        if ($last) {
            $lastNumber = (int) substr($last->$column, -3);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        return $prefix . $tanggal . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }
}
