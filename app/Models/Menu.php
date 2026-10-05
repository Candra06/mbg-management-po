<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal',
        'nama_menu',
        'kategori',
        'catatan',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function poItems()
    {
        return $this->hasMany(PoItem::class);
    }

    public function inspection()
    {
        return $this->hasOne(Inspection::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    public function getTotalPoAttribute()
    {
        return $this->poItems->sum('total_harga');
    }
}
