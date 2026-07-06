<?php
namespace App\Services;
use App\Models\CmsPage;
use App\Models\WebsiteChatKnowledge;
use App\Models\WebsiteChatSession;
use App\Services\Ai\Providers\OpenAiProvider;
use Throwable;
class WebsiteAssistantService {
    public function reply(WebsiteChatSession $session, string $question): array {
        $history = $session->messages()->latest()->take(8)->get()->reverse()->map(fn($m)=>strtoupper($m->role).': '.$m->message)->implode("\n");
        $prompt = "APPROVED PUBLIC KNOWLEDGE:\n".$this->knowledge()."\n\nRECENT CONVERSATION:\n".($history ?: 'None')."\n\nVISITOR QUESTION:\n".$question.
            "\n\nReturn JSON only: {\"answer\":\"...\",\"confidence\":0.0,\"needs_human\":false,\"sources\":[\"page key\"]}.";
        $instructions = 'You are the public website assistant for PG Integrated. Answer in the visitor language using only approved public knowledge. Never expose internal operations, private clients, projects, employee data, credentials, prices, or unpublished information. Never invent facts, commitments, availability, timelines, prices, results, or contact details. If unsupported, say you do not have confirmed information, set needs_human true and confidence below 0.55. If the visitor requests a person, meeting, quotation, callback, or project discussion, set needs_human true. Keep answers concise.';
        try {
            $text = app(OpenAiProvider::class)->generate($instructions,$prompt);
            $text = trim(preg_replace('/^```(?:json)?|```$/m','',trim($text)));
            $data = json_decode($text,true,512,JSON_THROW_ON_ERROR);
            return ['answer'=>(string)($data['answer']??''),'confidence'=>max(0,min(1,(float)($data['confidence']??0))),'needs_human'=>(bool)($data['needs_human']??true),'sources'=>array_values(array_filter((array)($data['sources']??[])))];
        } catch (Throwable) {
            $answer = str_starts_with(strtolower($session->locale), 'ar')
                ? 'لا أملك الآن إجابة مؤكدة. يمكنني تسجيل طلبك ليتواصل معك أحد موظفي PG Integrated.'
                : 'I do not have a confirmed answer right now. I can ask a PG Integrated team member to contact you.';
            return ['answer'=>$answer,'confidence'=>0,'needs_human'=>true,'sources'=>[]];
        }
    }
    private function knowledge(): string {
        $pages = CmsPage::where('is_published',true)->orderBy('key')->get(['key','title','sections'])->map(fn($p)=>['source'=>$p->key,'title'=>$p->title,'content'=>$p->sections])->all();
        $answers = WebsiteChatKnowledge::where('status','approved')->whereNotNull('answer')->latest('reviewed_at')->take(100)->get(['question','answer'])->toArray();
        return str(json_encode(['published_pages'=>$pages,'approved_answers'=>$answers],JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES))->limit(30000)->toString();
    }
}
