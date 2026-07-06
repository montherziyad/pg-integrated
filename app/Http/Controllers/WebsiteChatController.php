<?php
namespace App\Http\Controllers;
use App\Models\WebsiteChatKnowledge;
use App\Models\WebsiteChatSession;
use App\Services\WebsiteAssistantService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
class WebsiteChatController extends Controller {
    public function message(Request $request, WebsiteAssistantService $assistant): JsonResponse {
        $data=$request->validate(['visitor_token'=>['nullable','uuid'],'message'=>['required','string','min:2','max:2000'],'locale'=>['nullable','string','max:10']]);
        $session=$this->session($data['visitor_token']??null,$data['locale']??'en');
        $userMessage=$session->messages()->create(['role'=>'user','message'=>$data['message']]);
        $result=$assistant->reply($session,$data['message']); $needsHuman=$result['needs_human']||$result['confidence']<0.55;
        $session->messages()->create(['role'=>'assistant','message'=>$result['answer'],'confidence'=>$result['confidence'],'needs_human'=>$needsHuman,'source_refs'=>$result['sources']]);
        if($needsHuman) WebsiteChatKnowledge::updateOrCreate(
            ['question'=>$data['message'],'status'=>'pending'],
            ['source_message_id'=>$userMessage->id,'answer'=>$result['answer']]
        );
        $session->update(['status'=>$needsHuman?'needs_attention':$session->status,'last_message_at'=>now()]);
        return response()->json(['visitor_token'=>$session->visitor_token,'answer'=>$result['answer'],'needs_human'=>$needsHuman]);
    }
    public function contact(Request $request): JsonResponse {
        $data=$request->validate(['visitor_token'=>['nullable','uuid'],'name'=>['required','string','max:180'],'email'=>['nullable','required_without:phone','email','max:255'],'phone'=>['nullable','required_without:email','string','max:80'],'company'=>['nullable','string','max:180'],'preferred_at'=>['nullable','date','after:now'],'contact_notes'=>['nullable','string','max:2000'],'locale'=>['nullable','string','max:10']]);
        $session=$this->session($data['visitor_token']??null,$data['locale']??'en');
        $session->update([...collect($data)->only(['name','email','phone','company','preferred_at','contact_notes'])->all(),'status'=>'escalated','last_message_at'=>now()]);
        return response()->json(['visitor_token'=>$session->visitor_token,'message'=>'Your request has been recorded. A PG Integrated team member will review it.']);
    }
    private function session(?string $token,string $locale): WebsiteChatSession {
        if($token&&$session=WebsiteChatSession::where('visitor_token',$token)->first()) return $session;
        return WebsiteChatSession::create(['visitor_token'=>(string)Str::uuid(),'locale'=>$locale,'status'=>'open','last_message_at'=>now()]);
    }
}
