<?php

namespace App\Services;

use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use Illuminate\Support\Facades\DB;

class FinanceService
{
    public function getSummary()
    {
        $totalPemasukan = (float) Pemasukan::sum('jumlah');
        $totalPengeluaran = (float) Pengeluaran::sum('jumlah');
        return [
            'totalPemasukan' => $totalPemasukan,
            'totalPengeluaran' => $totalPengeluaran,
            'saldoBersih' => $totalPemasukan - $totalPengeluaran,
        ];
    }

    public function getMonthlyBreakdown(int $year): array
    {
        $isSqlite = DB::getDriverName() === 'sqlite';
        $monthExpr = $isSqlite ? "strftime('%m', tanggal)" : "MONTH(tanggal)";

        $income = Pemasukan::whereYear('tanggal', $year)
            ->selectRaw("$monthExpr as month, SUM(jumlah) as total")
            ->groupBy('month')
            ->get()
            ->pluck('total', 'month')
            ->mapWithKeys(fn($val, $key) => [(int)$key => (float)$val]);

        $expense = Pengeluaran::whereYear('tanggal', $year)
            ->selectRaw("$monthExpr as month, SUM(jumlah) as total")
            ->groupBy('month')
            ->get()
            ->pluck('total', 'month')
            ->mapWithKeys(fn($val, $key) => [(int)$key => (float)$val]);

        return compact('income', 'expense');
    }
}
