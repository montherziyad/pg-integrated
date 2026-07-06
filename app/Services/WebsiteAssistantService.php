<?php
namespace App\Services;
use App\Models\CmsPage;
use App\Models\JobCategory;
use App\Models\WebsiteChatKnowledge;
use App\Models\WebsiteChatSession;
use App\Services\Ai\Providers\OpenAiProvider;
use Illuminate\Support\Facades\Log;
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
        } catch (Throwable $exception) {
            Log::warning('Website assistant OpenAI request failed.', [
                'session_id' => $session->id,
                'exception' => $exception::class,
                'message' => $exception->getMessage(),
            ]);

            return $this->localAnswer($session, $question);
        }
    }

    private function localAnswer(WebsiteChatSession $session, string $question): array
    {
        $text = str($question)->lower()->squish()->toString();
        $arabic = preg_match('/[\x{0600}-\x{06FF}]/u', $question) === 1
            || str_starts_with(strtolower($session->locale), 'ar');

        $answers = [
            [
                ['بريف', 'brief', 'طلب مشروع', 'new request'],
                $arabic
                    ? 'لتقديم بريف: إذا كانت شركتك جديدة، سجّل من بوابة العميل وفعّل بريد الشركة ثم انتظر اعتماد PG Integrated. بعد تفعيل الحساب، ادخل إلى Client Portal ثم New Request، واختر Brief، وحدد الخدمة والمشروع إن وجد، وأدخل الهدف والجمهور والرسالة والمتطلبات والمواعيد وأرفق الملفات ثم أرسل الطلب. التسجيل: '.route('client.register').' — دخول العميل: '.route('client.login')
                    : 'To submit a brief, a new company should register in the Client Portal, verify its company email, and wait for PG Integrated approval. Once activated, open New Request, select Brief, choose the service and related project if applicable, add objectives, audience, message, requirements, timing, links and attachments, then submit. Register: '.route('client.register').' — Client login: '.route('client.login'),
                ['client_portal_process'],
            ],
            [
                ['خدمات', 'services', 'ماذا تقدم', 'what do you do'],
                $arabic
                    ? 'تقدم PG Integrated خدمات الاستراتيجية والتموضع، الهوية والإبداع والتصميم، المحتوى الرقمي والسوشيال ميديا، الميديا، الإنتاج والفيديو والموشن، الطباعة، الفعاليات وتنشيط العلامات. يمكنك مراجعة صفحة الخدمات: '.route('website.services')
                    : 'PG Integrated provides strategy and positioning, branding and creative design, digital and social content, media, video and motion production, printing, events and brand activation. View services: '.route('website.services'),
                ['services'],
            ],
            [
                ['اعمال', 'أعمال', 'case studies', 'portfolio', 'work'],
                $arabic
                    ? 'نعم، يمكنك الاطلاع على مجموعة من أعمال وحملات PG Integrated في صفحة Work: '.route('website.work').' ولطلب دراسة حالة مرتبطة بقطاعك يمكنك ترك بياناتك ليتواصل معك الفريق.'
                    : 'Yes. You can view selected PG Integrated campaigns and work here: '.route('website.work').'. For a case study relevant to your sector, leave your details and the team can follow up.',
                ['work'],
            ],
            [
                ['من هي', 'من هو', 'about pg', 'who is pg', 'how is pg'],
                $arabic
                    ? 'PG Integrated وكالة سعودية متكاملة تربط الاستراتيجية والإبداع والتصميم والرقمي والميديا والإنتاج والفعاليات ضمن نموذج عمل واحد. للمزيد: '.route('website.about')
                    : 'PG Integrated is a Saudi-rooted integrated agency connecting strategy, creative, design, digital, media, production and activation in one operating model. Learn more: '.route('website.about'),
                ['about'],
            ],
            [
                ['تواصل', 'موظف', 'موعد', 'اتصال', 'contact', 'call', 'meeting', 'quotation', 'عرض سعر'],
                $arabic
                    ? 'يسعدنا ربطك بأحد موظفي PG Integrated. استخدم زر Talk to a team member داخل المحادثة وأدخل اسمك وبريدك أو هاتفك والشركة والموعد المفضل، وسيظهر طلبك للفريق في لوحة التحكم.'
                    : 'We can connect you with a PG Integrated team member. Use Talk to a team member in this chat and enter your name, email or phone, company and preferred time. The request will appear in the team dashboard.',
                ['human_contact'],
            ],
        ];

        foreach ($answers as [$keywords, $answer, $sources]) {
            if (collect($keywords)->contains(fn ($keyword) => str_contains($text, $keyword))) {
                return ['answer'=>$answer,'confidence'=>0.95,'needs_human'=>false,'sources'=>$sources];
            }
        }

        return [
            'answer'=>$arabic
                ? 'لا أملك إجابة مؤكدة لهذا السؤال بعد. تم تسجيله للمراجعة، ويمكنك أيضًا طلب التواصل مع أحد موظفي PG Integrated.'
                : 'I do not have a confirmed answer to this question yet. It has been recorded for review, and you can also request contact with a PG Integrated team member.',
            'confidence'=>0,
            'needs_human'=>true,
            'sources'=>[],
        ];
    }
    private function knowledge(): string {
        $pages = CmsPage::where('is_published',true)->orderBy('key')->get(['key','title','sections'])->map(fn($p)=>['source'=>$p->key,'title'=>$p->title,'content'=>$p->sections])->all();
        $answers = WebsiteChatKnowledge::where('status','approved')->whereNotNull('answer')->latest('reviewed_at')->take(100)->get(['question','answer'])->toArray();
        $services = JobCategory::query()
            ->where('is_active', true)
            ->where('code', 'not like', 'INTERNAL%')
            ->orderBy('parent_id')
            ->orderBy('name')
            ->get(['name', 'code', 'parent_id', 'description'])
            ->toArray();
        $processes = [
            'start_brief' => [
                'summary' => 'A company can submit a new brief through the secure Client Portal.',
                'new_company' => [
                    'Open the client registration page.',
                    'Enter company and contact information using a valid company email.',
                    'Verify the email address.',
                    'Wait for PG Integrated admin approval and portal activation.',
                    'Sign in to the Client Portal.',
                ],
                'existing_client' => [
                    'Sign in to the Client Portal.',
                    'Open New Request.',
                    'Choose Brief as the request type.',
                    'Choose the relevant PG Integrated service and related project, if any.',
                    'Enter the title, objective, audience, message, mandatories, references, timeline, country, launch date, deliverables, and external links.',
                    'Upload the available attachments and submit.',
                    'The request appears in the PG Integrated admin dashboard for Client Service review and follow-up.',
                ],
                'links' => [
                    'register' => route('client.register'),
                    'login' => route('client.login'),
                    'public_contact' => route('website.contact'),
                ],
                'important' => 'The assistant must not promise acceptance, price, timeline, or project start. PG Integrated reviews the request first.',
            ],
            'human_contact' => [
                'summary' => 'A visitor may request a callback or preferred meeting time using the Talk to a team member form in the website chat.',
                'result' => 'The request appears in Admin > Website Chat for employee follow-up.',
            ],
            'client_portal' => [
                'available_after_approval' => ['Profile', 'Projects', 'New Request', 'Calendar', 'delivery links and progress when published by PG Integrated'],
            ],
        ];
        return str(json_encode([
            'published_pages'=>$pages,
            'public_service_catalog'=>$services,
            'approved_system_processes'=>$processes,
            'approved_answers'=>$answers,
        ],JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES))->limit(45000)->toString();
    }
}
