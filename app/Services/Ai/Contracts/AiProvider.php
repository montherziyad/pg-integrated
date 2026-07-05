<?php

namespace App\Services\Ai\Contracts;

interface AiProvider
{
    public function generate(string $systemPrompt, string $prompt): string;

    public function model(): string;
}
