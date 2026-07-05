<?php

namespace App\Http\Controllers;

use App\Models\CmsPage;
use Illuminate\View\View;

class PublicWebsiteController extends Controller
{
    public function home(): View
    {
        return $this->renderPage('home');
    }

    public function about(): View
    {
        return $this->renderPage('about');
    }

    public function services(): View
    {
        return $this->renderPage('services');
    }

    public function work(): View
    {
        return $this->renderPage('work');
    }

    public function team(): View
    {
        return $this->renderPage('team');
    }

    public function clients(): View
    {
        return $this->renderPage('clients');
    }

    public function contact(): View
    {
        return $this->renderPage('contact');
    }

    public function show(CmsPage $page): View
    {
        abort_unless($page->is_published, 404);

        return $this->render($page);
    }

    private function renderPage(string $key): View
    {
        $page = CmsPage::query()
            ->where('is_published', true)
            ->where(fn ($query) => $query->where('key', $key)->orWhere('slug', $key))
            ->first();

        return $this->render($page, $key);
    }

    private function render(?CmsPage $page, string $fallbackKey = 'home'): View
    {
        $content = $page?->sections ?? data_get($this->defaultPages(), $fallbackKey, $this->defaultPages()['home']);

        return view('website.page', [
            'page' => $page,
            'content' => $content,
            'seo' => $page?->seo ?? data_get($content, 'seo', []),
            'navigationPages' => $this->navigationPages(),
        ]);
    }

    private function navigationPages(): array
    {
        return [
            ['label' => 'Home', 'route' => 'website.home'],
            ['label' => 'About', 'route' => 'website.about'],
            ['label' => 'Services', 'route' => 'website.services'],
            ['label' => 'Work', 'route' => 'website.work'],
            ['label' => 'Team', 'route' => 'website.team'],
            ['label' => 'Clients', 'route' => 'website.clients'],
            ['label' => 'Contact', 'route' => 'website.contact'],
        ];
    }

    private function defaultPages(): array
    {
        return [
            'home' => [
                'type' => 'home',
                'hero' => [
                    'eyebrow' => 'PG Integrated · Jeddah · Riyadh',
                    'title' => 'Integrated ideas for brands that need to move.',
                    'body' => 'A multi-disciplinary agency connecting strategy, creative, digital, production, and delivery operations in one accountable rhythm.',
                    'primary_label' => 'Start a conversation',
                    'primary_url' => 'mailto:mziyad@pgintegrated.com',
                    'secondary_label' => 'View our work',
                    'secondary_url' => route('website.work'),
                    'image' => asset('prd-assets/images/homepage-option18-banner.jpg'),
                ],
                'stats' => [
                    ['value' => '41+', 'label' => 'Years of experience'],
                    ['value' => '150+', 'label' => 'Marketing specialists'],
                    ['value' => '360°', 'label' => 'Integrated delivery'],
                ],
                'intro' => [
                    'eyebrow' => 'About PG',
                    'title' => 'A Saudi-rooted agency built for end-to-end marketing.',
                    'body' => 'PG Integrated is a multi-disciplinary agency offering end-to-end marketing solutions, from strategy and brand narrative to design, digital, production, and activation.',
                ],
                'services' => [
                    'title' => 'Services',
                    'body' => 'Strategy, positioning, creative concepts, web and digital experiences, graphic design, production, and brand activation.',
                    'items' => [
                        ['title' => 'Strategy & Positioning', 'body' => 'Market studies, brand architecture, narratives, and campaign direction.'],
                        ['title' => 'Creative & Design', 'body' => 'Concepts, identities, graphic design, motion, and content systems.'],
                        ['title' => 'Digital & Web', 'body' => 'Interactive websites, digital campaigns, social assets, and platform thinking.'],
                    ],
                ],
                'portfolio' => [
                    'title' => 'Selected Work',
                    'items' => [
                        ['title' => 'Campaign Key Visual', 'image' => asset('prd-assets/PORTFOLIO/Jeddah-Riyadh-KV-04-1200x846.jpeg')],
                        ['title' => 'Retail Activation', 'image' => asset('prd-assets/PORTFOLIO/11512-Panda-36th-Anniversary-In_Mall-Mupi-70x120cm.jpeg')],
                        ['title' => 'FMCG Campaign', 'image' => asset('prd-assets/PORTFOLIO/01-AM-Lemon-Mint-Campaign-A1-Poster-2-724x1024.jpeg')],
                    ],
                ],
                'cta' => [
                    'title' => 'Have a brief in mind?',
                    'body' => 'Send the brief to PG Integrated and the team can shape the right next step.',
                    'label' => 'Contact PG Integrated',
                    'url' => 'mailto:mziyad@pgintegrated.com',
                ],
            ],
            'about' => [
                'type' => 'content',
                'hero' => [
                    'eyebrow' => 'About',
                    'title' => 'One agency. One integrated operating model.',
                    'body' => 'PG Integrated connects strategy, creative, digital, production, and delivery so brands can move from idea to execution with clarity.',
                    'image' => asset('prd-assets/images/business-about-bg.jpg'),
                ],
                'items' => [
                    ['title' => 'End-to-end thinking', 'body' => 'From business challenge to strategy, creative routes, production, and market delivery.'],
                    ['title' => 'Local insight', 'body' => 'A Saudi-market perspective with a long record of regional brand work.'],
                    ['title' => 'Operational visibility', 'body' => 'The platform connects briefs, jobs, teams, clients, support, CRM, and reports.'],
                ],
            ],
            'services' => [
                'type' => 'services',
                'hero' => [
                    'eyebrow' => 'Services',
                    'title' => 'Strategy, creative, digital, and production under one roof.',
                    'body' => 'Services are structured around clear outcomes: sharper brands, stronger campaigns, cleaner delivery, and better visibility for clients.',
                    'image' => asset('prd-assets/O2.jpeg'),
                ],
                'items' => [
                    ['title' => 'Strategy & Positioning', 'body' => 'Global studies, market mapping, brand positioning, campaign planning, and storytelling.'],
                    ['title' => 'Concept & Creative', 'body' => 'Creative concepts, key visuals, art direction, copywriting, content systems, and campaign toolkits.'],
                    ['title' => 'Digital Branding', 'body' => 'Web interfaces, digital content, interactive experiences, social campaigns, and performance assets.'],
                    ['title' => 'Graphic Design', 'body' => 'Identity applications, packaging, presentation systems, print, OOH, and production-ready artwork.'],
                    ['title' => 'Production', 'body' => 'Photography, video, post-production, motion, and scalable adaptations.'],
                    ['title' => 'Client Operations', 'body' => 'Brief intake, project tracking, support tickets, approvals, reporting, and archive management.'],
                ],
            ],
            'work' => [
                'type' => 'portfolio',
                'hero' => [
                    'eyebrow' => 'Work',
                    'title' => 'Selected campaigns and brand work.',
                    'body' => 'A view of campaign visuals, activations, and creative systems drawn from the PRD portfolio assets.',
                ],
                'items' => [
                    ['title' => 'Jeddah Riyadh Campaign', 'image' => asset('prd-assets/PORTFOLIO/Jeddah-Riyadh-KV-04-1200x846.jpeg')],
                    ['title' => 'Juffali Saudization', 'image' => asset('prd-assets/PORTFOLIO/Juffali-Saudization-1.jpeg')],
                    ['title' => 'Panda Anniversary', 'image' => asset('prd-assets/PORTFOLIO/11512-Panda-36th-Anniversary-In_Mall-Mupi-70x120cm.jpeg')],
                    ['title' => 'Almarai Lemon Mint', 'image' => asset('prd-assets/PORTFOLIO/01-AM-Lemon-Mint-Campaign-A1-Poster-2-724x1024.jpeg')],
                    ['title' => 'Saptco Go Bus', 'image' => asset('prd-assets/PORTFOLIO/Saptco-go-bus-A5-FINAL-FINAL-01.jpg')],
                    ['title' => 'Velor Campaign', 'image' => asset('prd-assets/PORTFOLIO/Velor-KV-A3-Temp-WHITE-01-01.jpeg')],
                ],
            ],
            'team' => [
                'type' => 'team',
                'hero' => [
                    'eyebrow' => 'Team',
                    'title' => 'Leadership and specialist teams.',
                    'body' => 'The public team interface uses the PRD team assets and can be edited from the CMS page data.',
                ],
                'items' => [
                    ['name' => 'Ahmad Kammoun', 'role' => 'Managing Director', 'image' => asset('prd-assets/Team/Ahmed Kammoun.png')],
                    ['name' => 'Imad Beyhum', 'role' => 'Client Leadership', 'image' => asset('prd-assets/Team/Imad Beyhum.jpg')],
                    ['name' => 'Riad Chehab', 'role' => 'Creative Leadership', 'image' => asset('prd-assets/Team/Riad Chehab.jpg')],
                    ['name' => 'Hamoud Al Harbi', 'role' => 'Operations', 'image' => asset('prd-assets/Team/Hamoud Al Harbi.jpg')],
                    ['name' => 'Amr Sallam', 'role' => 'Digital & Accounts', 'image' => asset('prd-assets/Team/Amr Sallam.jpeg')],
                ],
            ],
            'clients' => [
                'type' => 'clients',
                'hero' => [
                    'eyebrow' => 'Clients',
                    'title' => 'Trusted by regional and international brands.',
                    'body' => 'Client logos and references from the PRD client library.',
                ],
                'items' => [
                    ['name' => 'Unilever', 'image' => asset('prd-assets/Client/Unilever.jpeg')],
                    ['name' => 'Vision 2030', 'image' => asset('prd-assets/Client/Vision-2030-logo-250x202.jpeg')],
                    ['name' => 'Mercedes-Benz', 'image' => asset('prd-assets/Client/Mercedes-Benz-Logo-300x202.jpeg')],
                    ['name' => 'AMG', 'image' => asset('prd-assets/Client/AMG-logo-300x202.jpeg')],
                    ['name' => 'Alrajhi Takaful', 'image' => asset('prd-assets/Client/Alrajhi-Takaful-logo-250x202.jpeg')],
                    ['name' => 'BAE Systems', 'image' => asset('prd-assets/Client/BAE-systems-Logo-320x202.jpeg')],
                    ['name' => 'Bank AlJazira', 'image' => asset('prd-assets/Client/BANK-ALJAZIRA.jpeg')],
                    ['name' => 'Binzagr', 'image' => asset('prd-assets/Client/Binzagr-logo-250x202.jpeg')],
                    ['name' => 'Comfort', 'image' => asset('prd-assets/Client/Comfort.jpeg')],
                    ['name' => 'Diet Center', 'image' => asset('prd-assets/Client/Diet-Center-logo-300x202.jpeg')],
                    ['name' => 'Faten', 'image' => asset('prd-assets/Client/Faten-logo-250x202.jpeg')],
                    ['name' => 'HRDF', 'image' => asset('prd-assets/Client/HRDF-logo-250x202.jpeg')],
                ],
            ],
            'contact' => [
                'type' => 'contact',
                'hero' => [
                    'eyebrow' => 'Contact',
                    'title' => 'Let’s build the next brief.',
                    'body' => 'Reach PG Integrated for new briefs, partnerships, careers, and platform access.',
                    'image' => asset('prd-assets/O5.jpeg'),
                ],
                'contacts' => [
                    ['label' => 'Email', 'value' => 'mziyad@pgintegrated.com', 'url' => 'mailto:mziyad@pgintegrated.com'],
                    ['label' => 'Info', 'value' => 'info@pgintegrated.com', 'url' => 'mailto:info@pgintegrated.com'],
                    ['label' => 'Phone', 'value' => '+966 12 663 5959', 'url' => 'tel:+966126635959'],
                    ['label' => 'Fax', 'value' => '+966 12 665 6423', 'url' => null],
                ],
                'locations' => [
                    ['city' => 'Jeddah', 'address' => 'PG Integrated main office'],
                    ['city' => 'Riyadh', 'address' => 'PG Integrated regional operations'],
                ],
            ],
        ];
    }
}
