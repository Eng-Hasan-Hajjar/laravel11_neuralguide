<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Comment extends Model {
    use HasFactory;
    protected $fillable = ['user_id','architecture_id','rating','body','is_approved'];
    protected function casts(): array { return ['is_approved'=>'boolean']; }
    public function user(){ return $this->belongsTo(User::class); }
    public function architecture(){ return $this->belongsTo(Architecture::class); }
}
