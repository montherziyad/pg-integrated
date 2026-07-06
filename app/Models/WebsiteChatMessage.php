<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class WebsiteChatMessage extends Model {
    protected $fillable = ['session_id','role','message','confidence','needs_human','source_refs','meta'];
    protected function casts(): array { return ['needs_human'=>'boolean','source_refs'=>'array','meta'=>'array']; }
    public function session() { return $this->belongsTo(WebsiteChatSession::class, 'session_id'); }
}
