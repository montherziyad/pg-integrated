<?php

namespace App\Http\Controllers;

use App\Models\MarketingCampaign;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MarketingCampaignController extends Controller
{
    public function index(): View
    {
        return view('marketing.index', [
            'campaigns' => MarketingCampaign::query()->withCount('recipients')->latest()->paginate(20),
        ]);
    }

    public function create(): View
    {
        return view('marketing.form', ['campaign' => new MarketingCampaign]);
    }

    public function store(Request $request): RedirectResponse
    {
        MarketingCampaign::create($request->validate([
            'name' => ['required', 'string', 'max:180'],
            'channel' => ['required', Rule::in(['email', 'whatsapp', 'sms', 'linkedin'])],
            'status' => ['required', Rule::in(['draft', 'scheduled', 'active'])],
            'message_template' => ['nullable', 'string'],
            'scheduled_at' => ['nullable', 'date'],
        ]) + ['created_by' => $request->user()->id]);

        return redirect()->route('marketing.index')->with('status', 'Campaign created.');
    }
}
