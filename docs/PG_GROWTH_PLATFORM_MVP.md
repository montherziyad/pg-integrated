# PG Integrated Growth Platform MVP

This package adds the first executable MVP layer on top of the existing Laravel project.

## Added modules

1. Website CMS
   - `cms_pages`
   - Admin CRUD under `/admin/cms`

2. CRM
   - Companies, contacts, activities, follow-up tasks
   - Routes under `/crm`

3. Customer Support
   - Tickets and ticket messages
   - Routes under `/support`

4. AI Workspace
   - AI interaction log
   - Live provider abstraction for OpenAI Responses API, Google Gemini, and Anthropic Claude
   - Provider and model selection from configuration
   - Support, proposal, and marketing prompt profiles
   - Completed and failed requests recorded in the interaction log
   - Routes under `/ai-workspace`

5. Marketing Automation
   - Campaigns and recipients
   - Routes under `/marketing`

## Added Laravel files

- Models:
  - `CmsPage`
  - `CrmCompany`
  - `CrmContact`
  - `CrmActivity`
  - `CrmTask`
  - `SupportTicket`
  - `SupportMessage`
  - `AiInteraction`
  - `MarketingCampaign`
  - `MarketingCampaignRecipient`

- Controllers:
  - `CmsPageController`
  - `CrmController`
  - `SupportTicketController`
  - `AiWorkspaceController`
  - `MarketingCampaignController`

- Migrations:
  - `2026_07_04_000001_create_cms_pages_table.php`
  - `2026_07_04_000002_create_crm_tables.php`
  - `2026_07_04_000003_create_support_tickets_table.php`
  - `2026_07_04_000004_create_ai_and_marketing_tables.php`

## Verification performed

- PHP syntax lint passed for the added PHP files.
- `php artisan route:list` passed.
- All migrations passed against a clean SQLite database.
- The automated suite passed with 29 tests and 75 assertions.
- The production frontend bundle was built successfully with Vite.

## Next local commands

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate

# Configure PostgreSQL in .env, then:
php artisan migrate
npm run build
php artisan serve
```

## AI configuration

Add one or more provider keys to `.env`, then choose the provider in the AI workspace:

```dotenv
AI_DEFAULT_PROVIDER=openai
OPENAI_API_KEY=
GEMINI_API_KEY=
ANTHROPIC_API_KEY=
```

Provider API keys remain server-side and are never rendered in the dashboard.

## Next development step

Connect support tickets, CRM companies, and marketing campaigns to the AI interactions table so drafts can be created with their existing context.
