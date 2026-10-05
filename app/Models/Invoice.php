<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_invoice',
        'menu_id',
        'inspection_id',
        'tanggal_invoice',
        'subtotal_po',
        'potongan_ketidaksesuaian',
        'total_tagihan',
        'status',
        'catatan',
    ];

    protected $casts = [
        'tanggal_invoice' => 'date',
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function inspection()
    {
        return $this->belongsTo(Inspection::class);
    }
}
