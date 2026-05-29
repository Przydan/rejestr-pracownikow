<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyInfo extends Model
{
    use HasFactory;

    protected $table = 'company_infos';

    protected $fillable = [
        'name',
        'address',
        'nip',
        'regon',
        'email',
        'phone',
        'description',
        'logo_path',
    ];
}
