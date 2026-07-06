<?php
namespace App\Http\Controllers;
use App\Models\WebsiteChatKnowledge;
use App\Models\WebsiteChatSession;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
class AdminWebsiteChatController extends Controller {
    public function index(): View {
        return view('admin.website-chat.index',[
            'sessions'=>WebsiteChatSession::with(['messages'=>fn($q)=>$q->latest()->take(3)])->whereIn('status',['needs_attention','escalated'])->latest('last_message_at')->paginate(20),
            'knowledge'=>WebsiteChatKnowledge::where('status','pending')->latest()->take(50)->get(),
        ]);
    }
    public function approveKnowledge(Request $request,WebsiteChatKnowledge $knowledge): RedirectResponse {
        $data=$request->validate(['answer'=>['required','string','min:5','max:5000']]);
        $knowledge->update(['answer'=>$data['answer'],'status'=>'approved','reviewed_by'=>$request->user()->id,'reviewed_at'=>now()]);
        return back()->with('status','Answer approved and added to website assistant knowledge.');
    }
}
