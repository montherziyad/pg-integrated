<?php
namespace App\Http\Controllers;
use App\Models\WebsiteChatKnowledge;
use App\Models\WebsiteChatSession;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
class AdminWebsiteChatController extends Controller {
    public function index(): View {
        return view('admin.website-chat.index',[
            'sessions'=>WebsiteChatSession::with(['messages'=>fn($q)=>$q->latest()->take(10)])->latest('last_message_at')->paginate(20),
            'knowledge'=>WebsiteChatKnowledge::where('status','pending')->latest()->take(50)->get(),
        ]);
    }
    public function approveKnowledge(Request $request,WebsiteChatKnowledge $knowledge): RedirectResponse {
        $data=$request->validate(['answer'=>['required','string','min:5','max:5000']]);
        $knowledge->update(['answer'=>$data['answer'],'status'=>'approved','reviewed_by'=>$request->user()->id,'reviewed_at'=>now()]);
        return back()->with('status','Answer approved and added to website assistant knowledge.');
    }

    public function reset(Request $request): RedirectResponse
    {
        abort_unless($request->user()?->role?->code === 'SUPER_ADMIN', 403);

        DB::transaction(function () {
            WebsiteChatKnowledge::where('status', 'pending')->delete();
            WebsiteChatSession::query()->delete();
        });

        return back()->with('status', 'Previous website chat tests were cleared. Approved knowledge was preserved.');
    }
}
