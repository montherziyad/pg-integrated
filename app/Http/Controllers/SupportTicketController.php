<?php

namespace App\Http\Controllers;

use App\Models\SupportMessage;
use App\Models\SupportTicket;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SupportTicketController extends Controller
{
    public function index(): View
    {
        return view('support.index', [
            'tickets' => SupportTicket::query()->with(['assignee', 'company'])->latest()->paginate(20),
        ]);
    }

    public function show(SupportTicket $ticket): View
    {
        $ticket->load(['messages.user', 'assignee', 'company']);

        return view('support.show', compact('ticket'));
    }

    public function create(): View
    {
        return view('support.form', [
            'ticket' => new SupportTicket,
            'users' => User::query()->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'subject' => ['required', 'string', 'max:180'],
            'priority' => ['required', Rule::in(['normal', 'high', 'urgent'])],
            'channel' => ['required', Rule::in(['website', 'email', 'whatsapp', 'phone'])],
            'assigned_to' => ['nullable', 'exists:users,id'],
            'message' => ['nullable', 'string', 'max:20000'],
        ]);

        $ticket = DB::transaction(function () use ($data) {
            $ticket = SupportTicket::create(collect($data)->except('message')->all());

            if (filled($data['message'] ?? null)) {
                $ticket->messages()->create([
                    'sender_type' => 'customer',
                    'message' => $data['message'],
                ]);
            }

            return $ticket;
        });

        return redirect()->route('support.show', $ticket)->with('status', 'Ticket created.');
    }

    public function reply(Request $request, SupportTicket $ticket): RedirectResponse
    {
        SupportMessage::create($request->validate([
            'message' => ['required', 'string', 'max:20000'],
            'sender_type' => ['required', Rule::in(['agent', 'customer', 'ai'])],
        ]) + [
            'ticket_id' => $ticket->id,
            'user_id' => $request->user()->id,
        ]);

        return back()->with('status', 'Reply added.');
    }
}
