<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RealEstateImage extends Model
{
    use HasFactory;

        protected $fillable = ['real_estate_id', 'image_path'];

    // صورة → تنتمي لعقار
    public function realEstate()
    {
        return $this->belongsTo(RealEstate::class);
    }
}
