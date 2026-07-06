<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class WebsiteChatSession extends Model {
    protected $fillable = ['visitor_token','locale','status','name','email','phone','company','preferred_at','contact_notes','assigned_to','last_message_at','meta'];
    protected function casts(): array { return ['preferred_at'=>'datetime','last_message_at'=>'datetime','meta'=>'array']; }
    public function messages() { return $this->hasMany(WebsiteChatMessage::class, 'session_id'); }
}
