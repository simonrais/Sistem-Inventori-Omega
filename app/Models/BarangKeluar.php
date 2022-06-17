<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;
use Spatie\Activitylog\Traits\LogsActivity;

class BarangKeluar extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = ['penerima', 'berat', 'barang_id', 'harga', 'jumlah', 'image'];

    // log configuration
    protected static $logAttributes = ['berat', 'harga', 'jumlah', 'penerima'];
    protected static $igonoreChangedAttributes = ['updated_at'];
    protected static $recordEvents = ['created', 'updated', 'deleted'];
    protected static $logOnlyDirty = true;
    protected static $logName = 'goods';

    public function getDescriptionForEvent(string $eventName): string
    {
        return "You have {$eventName} stock-out";
    }

    public function getImageAttribute()
    {
        return $this->attributes['image'] ?  URL::to('/') . '/uploads/barang/keluar/' . $this->attributes['image'] : null;
    }


    public function barang()
    {
    	return $this->belongsTo(Barang::class);
    }
}
