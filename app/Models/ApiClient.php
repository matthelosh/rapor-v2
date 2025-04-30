<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string $client_id
 * @property string $client_secret
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ApiClient newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ApiClient newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ApiClient query()
 * @method static \Illuminate\Database\Eloquent\Builder|ApiClient whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApiClient whereClientSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApiClient whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApiClient whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApiClient whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ApiClient extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'client_secret'
    ];
}
