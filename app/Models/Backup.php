<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $sekolah_id
 * @property string $url
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Backup newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Backup newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Backup query()
 * @method static \Illuminate\Database\Eloquent\Builder|Backup whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Backup whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Backup whereSekolahId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Backup whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Backup whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Backup whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Backup whereUserId($value)
 * @mixin \Eloquent
 */
class Backup extends Model
{
    use HasFactory;
}
