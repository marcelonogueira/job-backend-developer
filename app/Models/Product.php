<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'description',
        'category',
        'image_url',
    ];

    protected $casts = [
        'with_image' => 'boolean',
    ];

    /**
     * Realiza a consulta pelos filters
     * @param Array $filters
     * @return Collection
     */
    public static function search(array $filters = []): Collection
    {
        $query = Product::query()
            ->when(isset($filters['id']), function ($q) use ($filters) {
                $q->where('id', $filters['id']);
            })
            ->when(isset($filters['name_category']), function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['name_category'] . '%')
                    ->orWhere('category', 'like', '%' . $filters['name_category'] . '%');
            })
            ->when(isset($filters['category']), function ($q) use ($filters) {
                $q->where('category', 'like', '%' . $filters['category'] . '%');
            })
            ->when(isset($filters['with_image']), function ($q) use ($filters) {
                if ($filters['with_image'] == 'true') {
                    $q->whereNotNull('image_url');
                } else {
                    $q->whereNull('image_url');
                }
            })
            ->orderBy('name');

        $data = $query->get();

        return $data;
    }
}
