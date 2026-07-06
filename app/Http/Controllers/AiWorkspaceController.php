<?php

namespace App\Http\Controllers;

use App\Models\AiInteraction;
use App\Services\Ai\AiManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Throwable;

class AiWorkspaceController extends Controller
{
    public function __construct(
        private readonly AiManager $ai,
    ) {}

    public function index(): View
    {
        return view('ai.workspace', [
            'interactions' => AiInteraction::query()->latest()->limit(30)->get(),
            'providers' => $this->ai->options(),
            'assistants' => $this->ai->assistantCatalog(),
            'defaultProvider' => config('services.ai.default_provider'),
        ]);
    }

    public function draft(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'agent' => ['required', Rule::in($this->ai->assistantNames())],
            'provider' => ['required', Rule::in($this->ai->names())],
            'prompt' => ['required', 'string', 'max:20000'],
        ]);

        $interaction = AiInteraction::create([
            'provider' => $data['provider'],
            'agent' => $data['agent'],
            'subject_type' => 'system',
            'subject_id' => 0,
            'user_id' => $request->user()->id,
            'prompt' => $data['prompt'],
            'meta' => ['status' => 'processing'],
        ]);

        try {
            $response = $this->ai->generate($data['provider'], $data['agent'], $data['prompt']);

            $interaction->update([
                'response' => $response,
                'meta' => [
                    'status' => 'completed',
                    'model' => $this->ai->model($data['provider']),
                ],
            ]);

            return back()->with('status', 'AI draft generated.');
        } catch (Throwable $exception) {
            Log::warning('AI draft generation failed.', [
                'interaction_id' => $interaction->id,
                'provider' => $data['provider'],
                'exception' => $exception::class,
                'message' => $exception->getMessage(),
            ]);

            $interaction->update([
                'meta' => [
                    'status' => 'failed',
                    'error' => $exception->getMessage(),
                ],
            ]);

            return back()
                ->withInput()
                ->withErrors(['provider' => $exception->getMessage()]);
        }
    }
}
