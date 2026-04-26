<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Architecture extends Model {
    use HasFactory;
    protected $fillable = [
        'name','slug','short_description','description','year','paper_title','paper_url','arxiv_url',
        'difficulty','data_requirement','compute_requirement','best_for','limitations','frameworks',
        'recommended_settings','pytorch_example','tensorflow_example','tags','is_published'
    ];
    protected function casts(): array {
        return ['frameworks'=>'array','tags'=>'array','is_published'=>'boolean'];
    }
    public function categories(){ return $this->belongsToMany(Category::class)->withTimestamps(); }
    public function suggestions(){ return $this->belongsToMany(Suggestion::class)->withPivot(['score','rank','reason'])->withTimestamps(); }
    public function comments(){ return $this->hasMany(Comment::class); }
    public function favoritedBy(){ return $this->belongsToMany(User::class, 'favorites')->withTimestamps(); }
}
