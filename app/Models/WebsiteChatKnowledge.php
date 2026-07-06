<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class WebsiteChatKnowledge extends Model {
    protected $table = 'website_chat_knowledge';
    protected $fillable = ['question','answer','status','source_message_id','reviewed_by','reviewed_at'];
    protected function casts(): array { return ['reviewed_at'=>'datetime']; }
}
