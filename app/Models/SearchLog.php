<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class SearchLog extends Model {
    use HasFactory;
    protected $fillable = ['user_id','query','ip_address','user_agent','results_count','metadata'];
    protected function casts(): array { return ['metadata'=>'array']; }
    public function user(){ return $this->belongsTo(User::class); }
}
