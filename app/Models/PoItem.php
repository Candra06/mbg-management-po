<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PoItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'menu_id',
        'nama_bahan',
        'merk',
        'qty_pesan',
        'satuan',
        'harga_satuan',
        'total_harga',
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function inspectionItem()
    {
        return $this->hasOne(InspectionItem::class);
    }
}
