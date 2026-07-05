<?php

namespace App\Services\Ai;

class PromptBuilder
{
    public function systemPrompt(string $agent): string
    {
        return match ($agent) {
            'proposal' => implode(' ', [
                'You are a proposal specialist for a creative, digital, and production agency.',
                'Create a clear proposal draft with objectives, scope, deliverables, timeline, assumptions, and next step.',
                'Do not invent prices, deadlines, or client facts. Mark missing details as items to confirm.',
                'Reply in the same language as the user.',
            ]),
            'marketing' => implode(' ', [
                'You are a B2B marketing assistant for a creative, digital, and production agency.',
                'Write a concise, personalized outreach message with a useful value proposition and one simple call to action.',
                'Avoid spammy claims, pressure, and invented facts.',
                'Reply in the same language as the user.',
            ]),
            default => implode(' ', [
                'You are a customer support assistant for a creative, digital, and production agency.',
                'Draft a warm, concise, professional reply that acknowledges the request and gives a clear next step.',
                'Do not invent policies, commitments, prices, or project facts.',
                'Reply in the same language as the user.',
            ]),
        };
    }
}
