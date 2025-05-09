<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = ['client_id', 'titre', 'description', 'statut', 'categorie', 'budget', 'date_limite'];


    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }
    public function selectedApplication()
{
    return $this->hasOne(Application::class)->with('freelancer');
}

}
