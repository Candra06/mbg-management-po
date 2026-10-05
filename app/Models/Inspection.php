<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inspection extends Model
{
    use HasFactory;

    protected $fillable = [
        'menu_id',
        'tanggal_cek',
        'nama_pemeriksa',
        'total_item',
        'total_sesuai',
        'total_tidak_sesuai',
        'status_keseluruhan',
        'catatan_umum',
    ];

    protected $casts = [
        'tanggal_cek' => 'datetime',
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function inspectionItems()
    {
        return $this->hasMany(InspectionItem::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    public function recalculateSummary()
    {
        $items = $this->inspectionItems;
        $this->total_item = $items->count();
        $this->total_sesuai = $items->where('status_jumlah', 'sesuai')->where('warna', 'Segar/Normal')->where('aroma', 'Segar/Normal')->count();
        $this->total_tidak_sesuai = $this->total_item - $this->total_sesuai;

        if ($this->total_tidak_sesuai === 0) {
            $this->status_keseluruhan = 'Lolos QC Perfect';
        } elseif ($this->total_sesuai > 0) {
            $this->status_keseluruhan = 'Lolos QC Dgn Catatan';
        } else {
            $this->status_keseluruhan = 'Ditolak QC';
        }

        $this->save();
    }
}
