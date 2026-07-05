<?php

namespace Database\Seeders;

use App\Models\CmsPage;
use Illuminate\Database\Seeder;

class CmsPageSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->pages() as $page) {
            CmsPage::query()->updateOrCreate(
                ['key' => $page['key']],
                [
                    'title' => $page['title'],
                    'slug' => $page['slug'],
                    'is_published' => true,
                    'sections' => $page['sections'],
                    'seo' => $page['seo'],
                ],
            );
        }
    }

    private function pages(): array
    {
        return [
            [
                'key' => 'home',
                'title' => 'Home',
                'slug' => 'home',
                'sections' => [
                    'type' => 'home',
                    'hero' => [
                        'eyebrow' => 'PG Integrated · Jeddah · Riyadh',
                        'title' => 'Integrated ideas for brands that need to move.',
                        'body' => 'A multi-disciplinary agency connecting strategy, creative, digital, production, and delivery operations in one accountable rhythm.',
                        'primary_label' => 'Start a conversation',
                        'primary_url' => 'mailto:mziyad@pgintegrated.com',
                        'secondary_label' => 'View our work',
                        'secondary_url' => '/work',
                        'image' => '/prd-assets/images/homepage-option18-banner.jpg',
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
                            ['title' => 'Campaign Key Visual', 'image' => '/prd-assets/PORTFOLIO/Jeddah-Riyadh-KV-04-1200x846.jpeg'],
                            ['title' => 'Retail Activation', 'image' => '/prd-assets/PORTFOLIO/11512-Panda-36th-Anniversary-In_Mall-Mupi-70x120cm.jpeg'],
                            ['title' => 'FMCG Campaign', 'image' => '/prd-assets/PORTFOLIO/01-AM-Lemon-Mint-Campaign-A1-Poster-2-724x1024.jpeg'],
                        ],
                    ],
                    'cta' => [
                        'title' => 'Have a brief in mind?',
                        'body' => 'Send the brief to PG Integrated and the team can shape the right next step.',
                        'label' => 'Contact PG Integrated',
                        'url' => 'mailto:mziyad@pgintegrated.com',
                    ],
                ],
                'seo' => [
                    'title' => 'PG Integrated | Creative, Digital & Production',
                    'description' => 'Integrated strategy, creative, digital, production, and client operations for ambitious brands.',
                ],
            ],
            [
                'key' => 'about',
                'title' => 'About',
                'slug' => 'about',
                'sections' => [
                    'type' => 'content',
                    'hero' => [
                        'eyebrow' => 'About',
                        'title' => 'One agency. One integrated operating model.',
                        'body' => 'PG Integrated connects strategy, creative, digital, production, and delivery so brands can move from idea to execution with clarity.',
                        'image' => '/prd-assets/images/business-about-bg.jpg',
                    ],
                    'items' => [
                        ['title' => 'End-to-end thinking', 'body' => 'From business challenge to strategy, creative routes, production, and market delivery.'],
                        ['title' => 'Local insight', 'body' => 'A Saudi-market perspective with a long record of regional brand work.'],
                        ['title' => 'Operational visibility', 'body' => 'The platform connects briefs, jobs, teams, clients, support, CRM, and reports.'],
                    ],
                ],
                'seo' => ['title' => 'About PG Integrated', 'description' => 'Learn about PG Integrated and its end-to-end agency model.'],
            ],
            [
                'key' => 'services',
                'title' => 'Services',
                'slug' => 'services',
                'sections' => [
                    'type' => 'services',
                    'hero' => [
                        'eyebrow' => 'Services',
                        'title' => 'Strategy, creative, digital, and production under one roof.',
                        'body' => 'Services are structured around clear outcomes: sharper brands, stronger campaigns, cleaner delivery, and better visibility for clients.',
                        'image' => '/prd-assets/O2.jpeg',
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
                'seo' => ['title' => 'PG Integrated Services', 'description' => 'Strategy, creative, digital, design, production, and client operations services.'],
            ],
            [
                'key' => 'work',
                'title' => 'Work',
                'slug' => 'work',
                'sections' => [
                    'type' => 'portfolio',
                    'hero' => [
                        'eyebrow' => 'Work',
                        'title' => 'Selected campaigns and brand work.',
                        'body' => 'A view of campaign visuals, activations, and creative systems drawn from the PRD portfolio assets.',
                    ],
                    'items' => [
                        ['title' => 'Jeddah Riyadh Campaign', 'image' => '/prd-assets/PORTFOLIO/Jeddah-Riyadh-KV-04-1200x846.jpeg'],
                        ['title' => 'Juffali Saudization', 'image' => '/prd-assets/PORTFOLIO/Juffali-Saudization-1.jpeg'],
                        ['title' => 'Panda Anniversary', 'image' => '/prd-assets/PORTFOLIO/11512-Panda-36th-Anniversary-In_Mall-Mupi-70x120cm.jpeg'],
                        ['title' => 'Almarai Lemon Mint', 'image' => '/prd-assets/PORTFOLIO/01-AM-Lemon-Mint-Campaign-A1-Poster-2-724x1024.jpeg'],
                        ['title' => 'Saptco Go Bus', 'image' => '/prd-assets/PORTFOLIO/Saptco-go-bus-A5-FINAL-FINAL-01.jpg'],
                        ['title' => 'Velor Campaign', 'image' => '/prd-assets/PORTFOLIO/Velor-KV-A3-Temp-WHITE-01-01.jpeg'],
                    ],
                ],
                'seo' => ['title' => 'PG Integrated Work', 'description' => 'Selected campaign, activation, and creative work by PG Integrated.'],
            ],
            [
                'key' => 'team',
                'title' => 'Team',
                'slug' => 'team',
                'sections' => [
                    'type' => 'team',
                    'hero' => [
                        'eyebrow' => 'Team',
                        'title' => 'Leadership and specialist teams.',
                        'body' => 'The public team interface uses the PRD team assets and can be edited from the CMS page data.',
                    ],
                    'items' => [
                        ['name' => 'Ahmad Kammoun', 'role' => 'Managing Director', 'image' => '/prd-assets/Team/Ahmed Kammoun.png'],
                        ['name' => 'Imad Beyhum', 'role' => 'Client Leadership', 'image' => '/prd-assets/Team/Imad Beyhum.jpg'],
                        ['name' => 'Riad Chehab', 'role' => 'Creative Leadership', 'image' => '/prd-assets/Team/Riad Chehab.jpg'],
                        ['name' => 'Hamoud Al Harbi', 'role' => 'Operations', 'image' => '/prd-assets/Team/Hamoud Al Harbi.jpg'],
                        ['name' => 'Amr Sallam', 'role' => 'Digital & Accounts', 'image' => '/prd-assets/Team/Amr Sallam.jpeg'],
                    ],
                ],
                'seo' => ['title' => 'PG Integrated Team', 'description' => 'Meet the leadership and specialist teams at PG Integrated.'],
            ],
            [
                'key' => 'clients',
                'title' => 'Clients',
                'slug' => 'clients',
                'sections' => [
                    'type' => 'clients',
                    'hero' => [
                        'eyebrow' => 'Clients',
                        'title' => 'Trusted by regional and international brands.',
                        'body' => 'Client logos and references from the PRD client library.',
                    ],
                    'items' => [
                        ['name' => 'Unilever', 'image' => '/prd-assets/Client/Unilever.jpeg'],
                        ['name' => 'Vision 2030', 'image' => '/prd-assets/Client/Vision-2030-logo-250x202.jpeg'],
                        ['name' => 'Mercedes-Benz', 'image' => '/prd-assets/Client/Mercedes-Benz-Logo-300x202.jpeg'],
                        ['name' => 'AMG', 'image' => '/prd-assets/Client/AMG-logo-300x202.jpeg'],
                        ['name' => 'Alrajhi Takaful', 'image' => '/prd-assets/Client/Alrajhi-Takaful-logo-250x202.jpeg'],
                        ['name' => 'BAE Systems', 'image' => '/prd-assets/Client/BAE-systems-Logo-320x202.jpeg'],
                        ['name' => 'Bank AlJazira', 'image' => '/prd-assets/Client/BANK-ALJAZIRA.jpeg'],
                        ['name' => 'Binzagr', 'image' => '/prd-assets/Client/Binzagr-logo-250x202.jpeg'],
                        ['name' => 'Comfort', 'image' => '/prd-assets/Client/Comfort.jpeg'],
                        ['name' => 'Diet Center', 'image' => '/prd-assets/Client/Diet-Center-logo-300x202.jpeg'],
                        ['name' => 'Faten', 'image' => '/prd-assets/Client/Faten-logo-250x202.jpeg'],
                        ['name' => 'HRDF', 'image' => '/prd-assets/Client/HRDF-logo-250x202.jpeg'],
                    ],
                ],
                'seo' => ['title' => 'PG Integrated Clients', 'description' => 'A selection of clients and brands served by PG Integrated.'],
            ],
            [
                'key' => 'contact',
                'title' => 'Contact',
                'slug' => 'contact',
                'sections' => [
                    'type' => 'contact',
                    'hero' => [
                        'eyebrow' => 'Contact',
                        'title' => 'Let’s build the next brief.',
                        'body' => 'Reach PG Integrated for new briefs, partnerships, careers, and platform access.',
                        'image' => '/prd-assets/O5.jpeg',
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
                'seo' => ['title' => 'Contact PG Integrated', 'description' => 'Contact PG Integrated for briefs, partnerships, careers, and access.'],
            ],
        ];
    }
}
