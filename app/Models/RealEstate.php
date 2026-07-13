<?php
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
 
class RealEstate extends Model
{
    use HasFactory;
 
    protected $fillable = [
        'type',
        'city',
        'area',
        'address',
        'price',
        'status',
        'owner_id',
        'size',
        'rooms',
        'description',
        'gender',
    ];
 
    // ========== Relationships ==========
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
 
    public function images()
    {
        return $this->hasMany(RealEstateImage::class);
    }
 
    // ========== Scopes ==========
    public function scopeForRent(Builder $query): Builder
    {
        return $query->where('status', 'for_rent');
    }
 
    public function scopeForSale(Builder $query): Builder
    {
        return $query->where('status', 'for_sale');
    }
 
    public function scopeInCity(Builder $query, string $city): Builder
    {
        return $query->where('city', $city);
    }
 
    public function scopePriceRange(Builder $query, int $min, int $max): Builder
    {
        return $query->whereBetween('price', [$min, $max]);
    }
 
    public function scopeOfType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }
}
 
