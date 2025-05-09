<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'competences','titre' ,'experience', 'portfolio', 'tarif','entreprise','description_entreprise','besoins','note'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
