<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Suggestion extends Model {
    use HasFactory;
    protected $fillable = ['user_id','problem_text','detected_domain','input_language','metadata'];
    protected function casts(): array { return ['metadata'=>'array']; }
    public function user(){ return $this->belongsTo(User::class); }
    public function architectures(){ return $this->belongsToMany(Architecture::class)->withPivot(['score','rank','reason'])->withTimestamps()->orderBy('pivot_rank'); }
}
