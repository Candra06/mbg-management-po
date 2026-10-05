<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InspectionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'inspection_id',
        'po_item_id',
        'qty_terima',
        'status_jumlah',
        'warna',
        'aroma',
        'catatan',
    ];

    public function inspection()
    {
        return $this->belongsTo(Inspection::class);
    }

    public function poItem()
    {
        return $this->belongsTo(PoItem::class);
    }

    public function getTotalHargaTerimaAttribute()
    {
        return $this->qty_terima * ($this->poItem->harga_satuan ?? 0);
    }
}
