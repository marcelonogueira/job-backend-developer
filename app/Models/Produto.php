<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Produto extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'description',
        'category',
        'image_url',
    ];

    /**
     * Realiza a consulta pelos filtros
     * @param Array $filtros
     * @param int $limit Limite para a consulta
     * @return Collection
     */
    public static function search(array $filtros = [], $limite = null): Collection
    {
        $query = Produto::query()
            ->when(isset($filtros['id']), function ($q) use ($filtros) {
                $q->where('id', $filtros['id']);
            })
            ->when(isset($filtros['name_category']), function ($q) use ($filtros) {
                $q->where('name', 'like', '%' . $filtros['name_category'] . '%')
                    ->orWhere('category', 'like', '%' . $filtros['name_category'] . '%');
            })
            ->when(isset($filtros['category']), function ($q) use ($filtros) {
                $q->where('category', 'like', '%' . $filtros['category'] . '%');
            })
            ->when(isset($filtros['with_image']), function ($q) use ($filtros) {
                if ($filtros['with_image'] == 'true') {
                    $q->whereNotNull('image_url');
                } else {
                    $q->whereNull('image_url');
                }
            })
            ->when(isset($limite), function ($q) use ($limite) {
                $q->limit($limite);
            })
            ->orderByRaw('name');

        $resultado = $query->get();

        return $resultado;
    }
}
