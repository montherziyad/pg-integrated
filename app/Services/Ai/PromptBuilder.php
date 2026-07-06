<?php

namespace App\Services\Ai;

class PromptBuilder
{
    public function catalog(): array
    {
        return [
            'Client Service' => [
                ['value' => 'client_service_reply', 'label' => 'Client Service Reply', 'task' => 'Draft a professional client-service reply with a clear next step.'],
                ['value' => 'email_reply', 'label' => 'Email Reply', 'task' => 'Draft a concise, polished email reply.'],
                ['value' => 'brief_analyzer', 'label' => 'Brief Analyzer', 'task' => 'Analyze the brief into objectives, audience, deliverables, mandatories, risks, and next steps.'],
                ['value' => 'missing_information', 'label' => 'Missing Information Checker', 'task' => 'Identify missing, unclear, or contradictory information and prepare focused questions.'],
                ['value' => 'meeting_summary', 'label' => 'Meeting Summary', 'task' => 'Summarize decisions, action items, owners, deadlines, and open questions.'],
                ['value' => 'delivery_note', 'label' => 'Delivery Note', 'task' => 'Draft a clear client delivery note describing files, versions, links, and required confirmation.'],
            ],
            'Proposals & Strategy' => [
                ['value' => 'proposal', 'label' => 'Proposal', 'task' => 'Create a proposal with objectives, scope, deliverables, timeline, assumptions, exclusions, and next step.'],
                ['value' => 'quotation_draft', 'label' => 'Quotation Draft', 'task' => 'Prepare a quotation structure without inventing prices, taxes, or commercial terms.'],
                ['value' => 'campaign_strategy', 'label' => 'Campaign Strategy', 'task' => 'Create a campaign strategy covering challenge, insight, audience, proposition, channels, phases, and KPIs.'],
                ['value' => 'brand_narrative', 'label' => 'Brand Narrative', 'task' => 'Develop a credible brand narrative, key message, pillars, and tone of voice.'],
                ['value' => 'creative_concept', 'label' => 'Creative Concept', 'task' => 'Propose distinct creative territories with rationale, message, and activation examples.'],
                ['value' => 'client_presentation', 'label' => 'Client Presentation', 'task' => 'Structure a client presentation slide by slide with clear headlines and supporting points.'],
            ],
            'Content & Creative' => [
                ['value' => 'arabic_copywriter', 'label' => 'Arabic Copywriter', 'task' => 'Write polished Arabic copy appropriate to the requested market, audience, and channel.'],
                ['value' => 'english_copywriter', 'label' => 'English Copywriter', 'task' => 'Write polished English copy appropriate to the requested market, audience, and channel.'],
                ['value' => 'social_media_content', 'label' => 'Social Media Content', 'task' => 'Create platform-appropriate social captions, hooks, calls to action, and content notes.'],
                ['value' => 'content_calendar', 'label' => 'Content Calendar', 'task' => 'Build a structured content calendar with themes, formats, channels, dates, and objectives.'],
                ['value' => 'translation', 'label' => 'Translation', 'task' => 'Translate accurately while preserving meaning, terminology, tone, and brand context.'],
                ['value' => 'proofreading', 'label' => 'Proofreading', 'task' => 'Correct grammar, spelling, clarity, consistency, and tone without changing intended meaning.'],
            ],
            'Media & Production' => [
                ['value' => 'media_plan', 'label' => 'Media Plan', 'task' => 'Draft a media plan framework with audience, channels, formats, phases, KPIs, and assumptions.'],
                ['value' => 'outdoor_campaign', 'label' => 'Outdoor Campaign Plan', 'task' => 'Plan an outdoor campaign covering formats, locations, message hierarchy, adaptations, and production needs.'],
                ['value' => 'event_activation', 'label' => 'Event & Activation Plan', 'task' => 'Develop an event or activation plan covering journey, zones, engagement, staffing, content, and logistics.'],
                ['value' => 'video_script', 'label' => 'Video Script', 'task' => 'Draft a video script with scenes, visuals, voice-over, supers, timing, and call to action.'],
                ['value' => 'production_brief', 'label' => 'Production Brief', 'task' => 'Create a production brief with outputs, specifications, locations, talent, schedule, and dependencies.'],
            ],
            'Projects & Traffic' => [
                ['value' => 'job_summary', 'label' => 'Job Summary', 'task' => 'Summarize the job, scope, status, owners, deadlines, risks, and required decisions.'],
                ['value' => 'project_status', 'label' => 'Project Status Report', 'task' => 'Prepare an executive project-status report with progress, completed work, next steps, blockers, and risks.'],
                ['value' => 'deadline_risk', 'label' => 'Deadline & Risk Review', 'task' => 'Review deadlines and identify schedule, dependency, approval, and delivery risks with mitigations.'],
                ['value' => 'workload_recommendation', 'label' => 'Workload Recommendation', 'task' => 'Recommend a fair work assignment based only on provided capacity, skills, deadlines, and priority.'],
                ['value' => 'delivery_checklist', 'label' => 'Final Delivery Checklist', 'task' => 'Create a final QA and delivery checklist covering formats, versions, links, approvals, and archive requirements.'],
            ],
            'CRM & Support' => [
                ['value' => 'crm_lead_research', 'label' => 'CRM Lead Research', 'task' => 'Structure provided company research into verified facts, opportunities, unknowns, and recommended next step.'],
                ['value' => 'lead_qualification', 'label' => 'Lead Qualification', 'task' => 'Assess a lead using only supplied evidence and clearly separate facts, assumptions, and missing information.'],
                ['value' => 'support', 'label' => 'Support Reply', 'task' => 'Draft a warm support reply acknowledging the issue and explaining the safe next step.'],
                ['value' => 'client_follow_up', 'label' => 'Client Follow-up', 'task' => 'Draft a courteous follow-up that states context, pending item, requested action, and next date.'],
                ['value' => 'company_profile', 'label' => 'Company Profile Summary', 'task' => 'Summarize the company profile, services, footprint, audience, and relevant opportunities from provided facts.'],
            ],
            'Internal Operations' => [
                ['value' => 'management_report', 'label' => 'Management Report', 'task' => 'Prepare a concise management report with findings, metrics, risks, decisions, and recommendations.'],
                ['value' => 'internal_memo', 'label' => 'Internal Memo', 'task' => 'Draft a clear internal memo with purpose, context, instructions, owners, and effective date.'],
                ['value' => 'hr_announcement', 'label' => 'HR Announcement', 'task' => 'Draft a respectful HR announcement without inventing policy, legal, salary, or employee details.'],
                ['value' => 'performance_summary', 'label' => 'Monthly Performance Summary', 'task' => 'Summarize supplied monthly performance data, achievements, gaps, risks, and next priorities.'],
                ['value' => 'marketing', 'label' => 'Marketing Message', 'task' => 'Write a concise B2B marketing message with a credible value proposition and one call to action.'],
            ],
        ];
    }

    public function names(): array
    {
        return collect($this->catalog())->flatten(1)->pluck('value')->all();
    }

    public function systemPrompt(string $agent): string
    {
        $legacyAliases = [
            'proposal' => 'proposal',
            'support' => 'support',
            'marketing' => 'marketing',
        ];
        $agent = $legacyAliases[$agent] ?? $agent;
        $assistant = collect($this->catalog())->flatten(1)->firstWhere('value', $agent);
        $task = $assistant['task'] ?? 'Prepare a professional agency draft for the requested task.';

        return implode(' ', [
            'You are an assistant inside PG Integrated, a creative, digital, media, events, and production agency.',
            $task,
            'Use only the information supplied by the user.',
            'Do not invent client facts, prices, deadlines, approvals, links, policies, research, or commitments.',
            'Clearly label missing information and assumptions.',
            'Produce a draft for human review; never claim it was sent, approved, or applied.',
            'Reply in the language requested by the user, or the same language as the input.',
        ]);
    }
}
