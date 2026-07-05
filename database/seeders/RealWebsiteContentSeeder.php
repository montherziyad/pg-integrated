<?php

namespace Database\Seeders;

use App\Models\CmsPage;
use Illuminate\Database\Seeder;

class RealWebsiteContentSeeder extends Seeder
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
                        'eyebrow' => 'PG Integrated · 45 Years of Care',
                        'title' => 'Care, progress and innovation for brands that need to move.',
                        'body' => 'Since 1979, PG Integrated has helped ambitious brands turn strategy into campaigns, activations, content, digital experiences, and market-ready production across Saudi Arabia and the region.',
                        'primary_label' => 'Start a brief',
                        'primary_url' => 'mailto:mziyad@pgintegrated.com',
                        'secondary_label' => 'Explore case studies',
                        'secondary_url' => '/work',
                        'image' => '/prd-assets/images/homepage-option18-banner.jpg',
                    ],
                    'stats' => [
                        ['value' => '45', 'label' => 'Years of care, progress and innovation'],
                        ['value' => '6', 'label' => 'Regional talent hubs'],
                        ['value' => '135+', 'label' => 'Family members across markets'],
                    ],
                    'intro' => [
                        'eyebrow' => 'Legacy of Care',
                        'title' => 'We nurture relationships, not just accounts.',
                        'body' => 'PG Integrated treats clients as partners and talent as family. This operating belief has shaped long-term relationships with regional and international brands across FMCG, banking, automotive, commerce, technology, energy, and government-linked sectors.',
                    ],
                    'services' => [
                        'title' => 'What we do',
                        'body' => 'A selected public-facing view from the credentials deck: brand strategy, creative campaigns, digital targeting, social content, activations, environmental branding, and production.',
                        'items' => [
                            ['title' => 'Strategy & Identity', 'body' => 'Brand strategy, positioning, corporate identity systems, brand documents, guidelines, and application toolkits.'],
                            ['title' => '360 Campaigns', 'body' => 'Campaign platforms, key visuals, film, outdoor, digital, social, retail, and launch communications.'],
                            ['title' => 'Digital & Targeting', 'body' => 'Programmatic, geo-targeted media, app campaigns, social-first content, and channel-specific creative.'],
                        ],
                    ],
                    'portfolio' => [
                        'title' => 'Featured case studies',
                        'items' => [
                            ['title' => 'NBCC Memories', 'body' => 'A premium biscuit launch that helped Memories gain 27% tea-biscuit market share in less than a year.', 'image' => '/prd-assets/PORTFOLIO/12847-Unilever-Touch-of-Love-Ramadan-CampKV-A4-Ar.jpg'],
                            ['title' => 'KUDU LTO', 'body' => 'A travel-inspired LTO platform that lifted sales by 37% within a month and strengthened innovation perception.', 'image' => '/prd-assets/Client/KUDU.jpeg'],
                            ['title' => 'Nada Protein Range', 'body' => 'Targeted multi-channel content achieving 99M+ impressions and 62M+ views.', 'image' => '/prd-assets/PORTFOLIO/Refreshing-Range-Kiwi-Lime-Master-Visual-ENG-725x1024.jpeg'],
                        ],
                    ],
                    'cta' => [
                        'title' => 'Ready to turn a real brief into a live project?',
                        'body' => 'Send your brief or request client portal access and the PG Integrated team can start the workflow from intake to delivery.',
                        'label' => 'Contact PG Integrated',
                        'url' => 'mailto:mziyad@pgintegrated.com',
                    ],
                ],
                'seo' => [
                    'title' => 'PG Integrated | Strategy, Creative, Digital & Production',
                    'description' => 'PG Integrated builds strategy, creative campaigns, digital content, activations, and production for ambitious brands.',
                ],
            ],
            [
                'key' => 'about',
                'title' => 'About',
                'slug' => 'about',
                'sections' => [
                    'type' => 'content',
                    'hero' => [
                        'eyebrow' => 'About PG Integrated',
                        'title' => 'A regional agency built on care, progress and innovation.',
                        'body' => 'For 45 years, PG Integrated has blended long-term client partnerships with specialist talent across Jeddah, Riyadh, Dubai, Cairo, Beirut, and Manila.',
                        'image' => '/prd-assets/images/business-about-bg.jpg',
                    ],
                    'items' => [
                        ['title' => 'Legacy of care', 'body' => 'The agency was built on nurturing relationships: clients are treated as partners, and teams are treated as family.'],
                        ['title' => 'Regional presence', 'body' => 'A distributed talent base across Saudi Arabia and the region enables culturally relevant work with operational depth.'],
                        ['title' => 'Integrated capability', 'body' => 'Strategy, identity, campaign development, digital, production, activations, and branding are connected in one delivery model.'],
                        ['title' => 'Long-term partnerships', 'body' => 'The credentials deck highlights decades of work across FMCG, banking, automotive, commerce, technology, and government-linked sectors.'],
                        ['title' => 'Talent culture', 'body' => 'PGi describes its people as family: teams challenge, collaborate, and stay together in service of client outcomes.'],
                        ['title' => 'Future-facing delivery', 'body' => 'The platform now connects public website content, client requests, jobs, traffic, CRM, support, and reporting.'],
                    ],
                ],
                'seo' => ['title' => 'About PG Integrated', 'description' => 'A 45-year regional agency built on care, progress, innovation, and integrated marketing delivery.'],
            ],
            [
                'key' => 'services',
                'title' => 'Services',
                'slug' => 'services',
                'sections' => [
                    'type' => 'services',
                    'hero' => [
                        'eyebrow' => 'Services',
                        'title' => 'From brand strategy to market activation.',
                        'body' => 'A focused selection from the credentials deck: the services most relevant for the public website and new client briefs.',
                        'image' => '/prd-assets/O2.jpeg',
                    ],
                    'items' => [
                        ['title' => 'Brand Strategy & Positioning', 'body' => 'Strategy platforms, market narratives, brand positioning, naming logic, and campaign foundations.'],
                        ['title' => 'Corporate Identity & Guidelines', 'body' => 'Identity uplift, logo systems, brand marks, signatures, guidelines, and application systems for corporate brands.'],
                        ['title' => '360 Creative Campaigns', 'body' => 'Campaign ideas, key visuals, TVC, outdoor, retail, social, content systems, and launch toolkits.'],
                        ['title' => 'FMCG Campaign Development', 'body' => 'Food, beverage, snack, dairy, honey, and household care campaigns built around consumer insight and sales objectives.'],
                        ['title' => 'Digital & Social Content', 'body' => 'Platform-native videos, cutdowns, social content, digital banners, programmatic assets, and performance-led creative.'],
                        ['title' => 'Geo-targeted Media Campaigns', 'body' => 'Neighborhood-level targeting, app-install campaigns, awareness phases, teaser/revealer structures, and channel planning.'],
                        ['title' => 'Activations & Events', 'body' => 'Automotive, retail, commerce, raffle, brand experience, and event-based communication.'],
                        ['title' => 'Environmental Branding', 'body' => 'Office branding, retail branding, signage systems, activation spaces, and branded experience environments.'],
                        ['title' => 'Production & Motion', 'body' => 'Film, animation, photography, post-production, campaign adaptations, and content production for multiple channels.'],
                    ],
                ],
                'seo' => ['title' => 'PG Integrated Services', 'description' => 'Brand strategy, identity, creative campaigns, digital content, activations, branding, and production.'],
            ],
            [
                'key' => 'work',
                'title' => 'Work',
                'slug' => 'work',
                'sections' => [
                    'type' => 'portfolio',
                    'hero' => [
                        'eyebrow' => 'Selected Work',
                        'title' => 'Case studies across FMCG, banking, services and automotive.',
                        'body' => 'A curated 30% public-facing selection from the May 2026 credentials deck, focused on projects that explain PG Integrated’s range without exposing the entire internal deck.',
                    ],
                    'items' => [
                        ['title' => 'NBCC Memories Launch', 'body' => 'Premium biscuit launch built around “For your sweetest memories,” achieving 27% tea-biscuit market share in less than a year.', 'image' => '/prd-assets/PORTFOLIO/12847-Unilever-Touch-of-Love-Ramadan-CampKV-A4-Ar.jpg'],
                        ['title' => 'Al Batal · Walad AlBalad', 'body' => 'A relevance platform reconnecting a long-standing Saudi snack brand with younger audiences while celebrating its local roots.', 'image' => '/prd-assets/PORTFOLIO/Omo-Ramadan-Care-Activation-Box-100X60cm.jpg'],
                        ['title' => 'Ringo Sandwich Biscuits', 'body' => 'A kid-focused campaign built on curiosity, play, and “غذي لعبهم”.', 'image' => '/prd-assets/PORTFOLIO/5-1024x768.jpeg'],
                        ['title' => 'Nada Protein Range', 'body' => 'Audience-tailored video and content campaign with 99M+ impressions and 62M+ views.', 'image' => '/prd-assets/PORTFOLIO/Refreshing-Range-Kiwi-Lime-Master-Visual-ENG-725x1024.jpeg'],
                        ['title' => 'KUDU LTO · Travel Around the World', 'body' => 'Animated sandwich campaign that increased sales by 37% within a month and strengthened brand innovation.', 'image' => '/prd-assets/Client/KUDU.jpeg'],
                        ['title' => 'KUDU Khafayif', 'body' => 'A comedic “هلوس الجوع” platform using food craving as a social sharing idea.', 'image' => '/prd-assets/PORTFOLIO/Jeddah-Riyadh-KV-04-1200x846.jpeg'],
                        ['title' => 'Alshifa Honey Expert', 'body' => 'A long-term strategy positioning the GCC-leading honey brand as “The Honey Expert”.', 'image' => '/prd-assets/PORTFOLIO/01-AM-Lemon-Mint-Campaign-A1-Poster-2-724x1024.jpeg'],
                        ['title' => 'Almarai Cheese · Where it all starts', 'body' => 'A versatile spreadable cheese campaign built around everyday stories and usage moments.', 'image' => '/prd-assets/PORTFOLIO/01-AM-Watermelon-With-Pulp-A1-Poster-EN-723x1024.jpeg'],
                        ['title' => 'Alinma Bank · Tafaal', 'body' => 'A bank launch platform created during a trust-challenged financial climate, turning aspiration into a memorable brand idea.', 'image' => '/prd-assets/Client/BANK-ALJAZIRA.jpeg'],
                        ['title' => 'Mazadak App Relaunch', 'body' => 'A three-phase teaser, revealer, and install campaign for an auction platform expanding beyond cars.', 'image' => '/prd-assets/PORTFOLIO/Saptco-go-bus-A5-FINAL-FINAL-01.jpg'],
                        ['title' => 'Careem Captains Recruitment', 'body' => 'A targeted recruitment campaign that helped shift perception and drive 600%+ increase in Saudi sign-ups.', 'image' => '/prd-assets/PORTFOLIO/Juffali-Saudization-1.jpeg'],
                        ['title' => 'ITC Zoom Fiber', 'body' => 'Neighborhood-level geo-targeted campaign for fiber conversion using the insight “وليه حرقة الدم”.', 'image' => '/prd-assets/Client/ITC-logo-300x200.jpeg'],
                        ['title' => 'Mercedes-Benz · She’s Mercedes', 'body' => 'Regional empowerment content for an international Mercedes-Benz initiative celebrating Arab women.', 'image' => '/prd-assets/Client/Mercedes-Benz-Logo-300x202.jpeg'],
                        ['title' => 'Nissan Women Showroom', 'body' => 'Launch communication for an all-women showroom experience built around comfort, empowerment and service excellence.', 'image' => '/prd-assets/Client/PG_Integrated_2023_FMCG20.jpg'],
                        ['title' => 'CEER Office Branding', 'body' => 'Environmental branding and office experience work supporting a future-facing Saudi automotive brand.', 'image' => '/prd-assets/images/business-digital-bg.jpg'],
                    ],
                ],
                'seo' => ['title' => 'PG Integrated Work & Case Studies', 'description' => 'Selected case studies from FMCG, banking, services, automotive, activations, and branding.'],
            ],
            [
                'key' => 'team',
                'title' => 'Team',
                'slug' => 'team',
                'sections' => [
                    'type' => 'team',
                    'hero' => [
                        'eyebrow' => 'Team',
                        'title' => 'A family of specialists across markets.',
                        'body' => 'The credentials deck describes PGi as a family of talent across Jeddah, Riyadh, Cairo, Dubai, Beirut, and Manila.',
                    ],
                    'items' => [
                        ['name' => 'Ahmad Kammoun', 'role' => 'Managing Director', 'image' => '/prd-assets/Team/Ahmed Kammoun.png'],
                        ['name' => 'Imad Beyhum', 'role' => 'Client Leadership', 'image' => '/prd-assets/Team/Imad Beyhum.jpg'],
                        ['name' => 'Riad Chehab', 'role' => 'Creative Leadership', 'image' => '/prd-assets/Team/Riad Chehab.jpg'],
                        ['name' => 'Hamoud Al Harbi', 'role' => 'Operations', 'image' => '/prd-assets/Team/Hamoud Al Harbi.jpg'],
                        ['name' => 'Tarek Chehab', 'role' => 'Strategy & Creative', 'image' => '/prd-assets/Team/Tarek Chehab.jpg'],
                        ['name' => 'Amr Sallam', 'role' => 'Digital & Accounts', 'image' => '/prd-assets/Team/Amr Sallam.jpeg'],
                    ],
                ],
                'seo' => ['title' => 'PG Integrated Team', 'description' => 'A regional family of strategy, creative, digital, operations, and client leadership specialists.'],
            ],
            [
                'key' => 'clients',
                'title' => 'Clients',
                'slug' => 'clients',
                'sections' => [
                    'type' => 'clients',
                    'hero' => [
                        'eyebrow' => 'Clients & Sectors',
                        'title' => 'Trusted across FMCG, banking, automotive, commerce and services.',
                        'body' => 'A curated public-facing client view based on the credentials deck and available brand assets.',
                    ],
                    'items' => [
                        ['name' => 'Unilever', 'image' => '/prd-assets/Client/Unilever.jpeg'],
                        ['name' => 'KUDU', 'image' => '/prd-assets/Client/KUDU.jpeg'],
                        ['name' => 'Almarai / FMCG Work', 'image' => '/prd-assets/Client/PG_Integrated_2023_FMCG.jpg'],
                        ['name' => 'Alshifa / Honey Category', 'image' => '/prd-assets/Client/PG_Integrated_2023_FMCG11.jpg'],
                        ['name' => 'Mercedes-Benz', 'image' => '/prd-assets/Client/Mercedes-Benz-Logo-300x202.jpeg'],
                        ['name' => 'AMG', 'image' => '/prd-assets/Client/AMG-logo-300x202.jpeg'],
                        ['name' => 'BAE Systems', 'image' => '/prd-assets/Client/BAE-systems-Logo-320x202.jpeg'],
                        ['name' => 'Bank AlJazira', 'image' => '/prd-assets/Client/BANK-ALJAZIRA.jpeg'],
                        ['name' => 'Binzagr', 'image' => '/prd-assets/Client/Binzagr-logo-250x202.jpeg'],
                        ['name' => 'ITC', 'image' => '/prd-assets/Client/ITC-logo-300x200.jpeg'],
                        ['name' => 'Vision 2030', 'image' => '/prd-assets/Client/Vision-2030-logo-250x202.jpeg'],
                        ['name' => 'Panda', 'image' => '/prd-assets/Client/Panda-logo-250x202.jpeg'],
                        ['name' => 'Comfort', 'image' => '/prd-assets/Client/Comfort.jpeg'],
                        ['name' => 'Lipton', 'image' => '/prd-assets/Client/Lipton.jpeg'],
                        ['name' => 'OMO', 'image' => '/prd-assets/Client/OMO.jpeg'],
                        ['name' => 'Jif', 'image' => '/prd-assets/Client/Jif.jpeg'],
                    ],
                ],
                'seo' => ['title' => 'PG Integrated Clients', 'description' => 'Selected clients and sectors served by PG Integrated across FMCG, banking, services, automotive, and commerce.'],
            ],
            [
                'key' => 'contact',
                'title' => 'Contact',
                'slug' => 'contact',
                'sections' => [
                    'type' => 'contact',
                    'hero' => [
                        'eyebrow' => 'Let’s Chit Chat',
                        'title' => 'Bring your next real brief into the system.',
                        'body' => 'For new clients, live briefs, credentials requests, portal access, and campaign discussions, contact PG Integrated.',
                        'image' => '/prd-assets/O5.jpeg',
                    ],
                    'contacts' => [
                        ['label' => 'Traffic / Main Email', 'value' => 'mziyad@pgintegrated.com', 'url' => 'mailto:mziyad@pgintegrated.com'],
                        ['label' => 'Info', 'value' => 'info@pgintegrated.com', 'url' => 'mailto:info@pgintegrated.com'],
                        ['label' => 'Phone', 'value' => '+966 12 663 5959', 'url' => 'tel:+966126635959'],
                        ['label' => 'Client Portal', 'value' => 'Request access through your account manager', 'url' => '/client/login'],
                    ],
                    'locations' => [
                        ['city' => 'Jeddah', 'address' => 'Main Saudi operations and account leadership.'],
                        ['city' => 'Riyadh', 'address' => 'Saudi market and client operations.'],
                        ['city' => 'Regional Network', 'address' => 'Dubai, Cairo, Beirut, and Manila talent support.'],
                    ],
                ],
                'seo' => ['title' => 'Contact PG Integrated', 'description' => 'Contact PG Integrated for new briefs, credentials requests, client portal access, and campaign discussions.'],
            ],
        ];
    }
}
