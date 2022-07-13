<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;
use Spatie\Activitylog\Traits\LogsActivity;

class BarangMasuk extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = ['supplier_id', 'berat', 'barang_id', 'harga', 'jumlah','tgl_brg_masuk' ];

    // log configuration
    protected static $logAttributes = ['berat', 'harga', 'jumlah', 'tgl_brg_masuk'];
    protected static $igonoreChangedAttributes = ['updated_at'];
    protected static $recordEvents = ['created', 'updated', 'deleted'];
    protected static $logOnlyDirty = true;
    protected static $logName = 'goods';

    public function getDescriptionForEvent(string $eventName): string
    {
        return "You have {$eventName} stock-in";
    }

    public function supplier()
    {
    	return $this->belongsTo(Supplier::class);
    }

    public function barang()
    {
    	return $this->belongsTo(Barang::class);
    }
}
