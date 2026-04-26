<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class ResearchNote extends Model {
    use HasFactory;
    protected $fillable = ['user_id','architecture_id','title','body','visibility'];
    public function user(){ return $this->belongsTo(User::class); }
    public function architecture(){ return $this->belongsTo(Architecture::class); }
}
