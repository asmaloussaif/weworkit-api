<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ['client_id', 'freelancer_id', 'montant', 'statut','description','project_id','date_limite'];

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function freelancer()
    {
        return $this->belongsTo(User::class, 'freelancer_id');
    }
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}
