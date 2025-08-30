<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Ramsey\Uuid\Type\Decimal;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;
    protected function getStats(): array
    {
        $pemasukan = Transaction::incomes()->get()->sum('amount');
        $pengeluaran = Transaction::expanses()->get()->sum('amount');

        $persentasePemasukan = $pemasukan >0 || $pengeluaran>0 ? $pemasukan / ($pemasukan + $pengeluaran) * 100 :0 ;
        
        $persentasePengeluaran = $pemasukan >0 || $pengeluaran>0 ? $pengeluaran / ($pemasukan + $pengeluaran) * 100:0;


        return [
            Stat::make('Total Pemasukan', 'Rp. ' . number_format($pemasukan, 0, ',', '.'))
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                // persentase pemasukan 2 angka dibelakang koma
                ->description(number_format($persentasePemasukan, 2) . '%')

                ->extraAttributes([
                    'class' => 'text-sm',
                ])
                
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),
            Stat::make('Total Pengeluaran', 'Rp. ' . number_format($pengeluaran, 0, ',', '.'))
                ->description(number_format($persentasePengeluaran, 2) . '%')
                ->extraAttributes([
                    'class' => 'text-sm',
                ])

                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->chart([17, 16, 14, 15, 14, 13, 12])
                ->color('danger'),
            Stat::make('Selisih', 'Rp. ' . number_format($pemasukan - $pengeluaran, 0, ',', '.'))
                // persentase perbandingan pemasukan dan pengeluaran jika pemasukan lebih besar dari pengeluaran maka ubah icon nya menjadi up sebalikanya menjadi down
                ->chart([7, 2, 10, 3, 15, 4, 17])                

                ->description(number_format($persentasePemasukan - $persentasePengeluaran, 2) . '%')

                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
        ];
    }
}
