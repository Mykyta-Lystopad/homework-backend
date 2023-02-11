<?php

namespace App\Traits;

use Hashids\Hashids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

/**
 * QrCode generator for models.
 *
 * Trait ModelWithQrCode
 * @package App\Traits
 * @mixin Model
 */
trait ModelWithQrCode
{
    /**
     * @return string
     */
    public function getModelCodeAttribute()
    {
        $data = str_pad($this->getKey(), 6, '0', STR_PAD_LEFT);
        return (new Hashids(config('app.name')))->encodeHex($data);
    }

    /**
     * Generate Qr code and return url to it.
     *
     * @return string
     */
    public function getQrCodeLinkAttribute(): string
    {
        $format = 'png';
        $path = "qrCodes/{$this->getTable()}-{$this->getKey()}.{$format}";
        if (!Storage::exists($path)) {
            $text = route('bind.' . class_basename(self::class), ['code' => $this->model_code]);
            $file = QrCode::format($format)
                ->margin(0)
                ->size(300)
//                ->errorCorrection('Q')
                ->generate($text);

            Storage::put($path, $file);
        }
        return Storage::url($path);
    }
}
