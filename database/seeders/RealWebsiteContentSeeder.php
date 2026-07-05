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
                        'items' => array_slice($this->workItems(), 0, 6),
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
                        'body' => 'An expanded public-facing selection from the May 2026 credentials deck, organized to show PG Integrated’s range across campaigns, banking, services, automotive, activations, and branding.',
                    ],
                    'items' => $this->workItems(),
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

    private function workItems(): array
    {
        return [
            ['title' => 'NBCC Memories Launch', 'body' => 'Premium biscuit launch built around “Memories Biscuits… For your sweetest Memories,” achieving 27% tea-biscuit market share in less than a year.', 'image' => '/case-study-assets/nbcc-memories-launch-1.jpeg'],
            ['title' => 'NBCC Memories Outdoor Launch', 'body' => 'Outdoor-led launch activity during Ramadan, designed to build fast awareness for the new Memories premium biscuit brand.', 'image' => '/case-study-assets/nbcc-memories-outdoor-1.jpeg'],
            ['title' => 'NBCC Memories TV Campaign', 'body' => 'Post-launch communication supported by celebrity chef Manal Al-Alem to strengthen the brand’s credibility with families.', 'image' => '/case-study-assets/nbcc-memories-tv-1.png'],
            ['title' => 'NBCC Memories 10-Year Anniversary', 'body' => 'A brand anniversary activation built around consumer participation and a Guinness World Records biscuit-cake collaboration.', 'image' => '/case-study-assets/nbcc-memories-anniversary-1.jpeg'],
            ['title' => 'Al Batal · Walad AlBalad', 'body' => 'A refreshed platform for a Saudi snack brand founded in 1988, reconnecting with new generations through “البطل، ولد البلد”.', 'image' => '/case-study-assets/albatal-walad-albalad-1.png'],
            ['title' => 'Al Batal Football', 'body' => 'A football campaign built around national pride and the role of Al Batal in Saudi football moments.', 'image' => '/case-study-assets/albatal-football-1.png'],
            ['title' => 'Al Batal World Cup', 'body' => 'World Cup communication celebrating Saudi fan culture, cheering rituals, and football pride under “الكورة يبيلها بطل”.', 'image' => '/case-study-assets/albatal-world-cup-1.png'],
            ['title' => 'Al Batal Stay at Home', 'body' => 'A Covid-era family communication encouraging people to reconnect and support each other while staying home.', 'image' => '/case-study-assets/albatal-stay-at-home-1.png'],
            ['title' => 'Ringo Sandwich Biscuits', 'body' => 'A kid-focused campaign inspired by curiosity, play, and the line “غذي لعبهم”.', 'image' => '/case-study-assets/ringo-sandwich-1.png'],
            ['title' => 'Ringo Popcorn', 'body' => 'A product launch combining the savory crunch of popcorn with the creamy sweetness of Ringo sandwich biscuits.', 'image' => '/case-study-assets/ringo-popcorn-1.png'],
            ['title' => 'Ringo Promo 2024', 'body' => 'Promotional campaign assets for Ringo, extending the brand’s playful product world into offer-led communication.', 'image' => '/case-study-assets/ringo-promo-1.png'],
            ['title' => 'Ringo Promo OOH & Digital', 'body' => 'Scan-and-win promotional material adapted for outdoor and digital touchpoints.', 'image' => '/case-study-assets/ringo-promo-ooh-1.jpg'],
            ['title' => 'Nada Protein Range', 'body' => 'Audience-tailored content for Nada Protein, using different creative messages for fitness, work, and lifestyle audiences.', 'image' => '/case-study-assets/nada-protein-content-1.png'],
            ['title' => 'KUDU LTO · Travel Around the World', 'body' => 'A travel-inspired sandwich campaign that increased sales by 37% within a month and strengthened innovation perception.', 'image' => '/case-study-assets/kudu-lto-2.png'],
            ['title' => 'KUDU LTO Campaign Results', 'body' => 'A visual campaign system supporting international LTO sandwiches and helping bring KUDU back onto the innovation map.', 'image' => '/case-study-assets/kudu-lto-results-1.png'],
            ['title' => 'KUDU · And More!', 'body' => 'Offer communication simplifying multiple generous daily deals through a memorable “and more” campaign idea.', 'image' => '/case-study-assets/kudu-and-more-2.png'],
            ['title' => 'Alshifa Honey Expert', 'body' => 'A strategic platform positioning the GCC-leading honey brand as “The Honey Expert”.', 'image' => '/case-study-assets/alshifa-honey-2.png'],
            ['title' => 'Alshifa Thematic Campaign', 'body' => 'A thematic campaign highlighting the reasons that make Alshifa the honey expert.', 'image' => '/case-study-assets/alshifa-thematic-2.png'],
            ['title' => 'Sary Honey', 'body' => 'A honey campaign challenging category conventions through a smarter-choice positioning: “SARY المدركين”.', 'image' => '/case-study-assets/sary-honey-1.png'],
            ['title' => 'Almarai Cheese · Where it all starts', 'body' => 'A spreadable cheese campaign designed around everyday family usage moments and “Where it all starts”.', 'image' => '/case-study-assets/almarai-cheese-1.png'],
            ['title' => 'OMO Mother’s Day Display', 'body' => 'Retail display and key visual work for OMO Mother’s Day communication.', 'image' => '/case-study-assets/omo-mothers-day-1.jpeg'],
            ['title' => 'OMO Mother’s Day Video', 'body' => 'Mother’s Day content and display campaign assets extending the OMO message across touchpoints.', 'image' => '/case-study-assets/omo-mothers-day-video-1.jpeg'],
            ['title' => 'OMO & Comfort · Share a Touch of Love', 'body' => 'A joint OMO and Comfort campaign built around care, giving, and Ramadan-linked generosity.', 'image' => '/case-study-assets/omo-comfort-touch-love-1.jpeg'],
            ['title' => 'Alinma Bank · Tafaal', 'body' => 'A bank launch platform created during a trust-challenged financial climate, turning aspiration into a memorable brand idea.', 'image' => '/case-study-assets/alinma-tafaal-1.jpg'],
            ['title' => 'Alinma Corporate 360 Application', 'body' => 'Corporate campaign application system across banking communication channels and brand touchpoints.', 'image' => '/case-study-assets/alinma-positioning-1-1.png'],
            ['title' => 'Alinma Positioning Film', 'body' => 'Film-led positioning communication for Alinma’s corporate brand platform.', 'image' => '/case-study-assets/alinma-positioning-2-1.png'],
            ['title' => 'Alinma Family Communication', 'body' => 'A Tafaal platform execution using human stories and optimistic banking language.', 'image' => '/case-study-assets/alinma-positioning-3-1.png'],
            ['title' => 'Alinma Positioning Application', 'body' => 'Brand platform applications supporting Alinma’s positioning across print and branch communication.', 'image' => '/case-study-assets/alinma-positioning-4-1.png'],
            ['title' => 'Alinma Confidence Campaign', 'body' => 'Corporate banking communication focused on confidence, trust, and reassurance.', 'image' => '/case-study-assets/alinma-positioning-5-1.png'],
            ['title' => 'Alinma Corporate Campaign', 'body' => 'Corporate campaign assets extending Alinma’s banking promise into visual communication.', 'image' => '/case-study-assets/alinma-corporate-1.jpg'],
            ['title' => 'Alinma Branch Opening', 'body' => 'Branch opening communication supporting new physical customer touchpoints.', 'image' => '/case-study-assets/alinma-branch-opening-1.jpeg'],
            ['title' => 'Alinma Cash Back', 'body' => 'Card and offer communication for Alinma cashback propositions.', 'image' => '/case-study-assets/alinma-cashback-1-1.jpeg'],
            ['title' => 'Alinma Cash Back Digital', 'body' => 'Digital and offer-led communication for Alinma cashback activity.', 'image' => '/case-study-assets/alinma-cashback-2-1.jpeg'],
            ['title' => 'Alinma DSF Campaign', 'body' => 'Dubai Shopping Festival-linked promotion communication for Alinma cardholders.', 'image' => '/case-study-assets/alinma-dsf-1.jpg'],
            ['title' => 'Alinma Joint Promotion', 'body' => 'Partner promotion assets communicating travel and cardholder benefits.', 'image' => '/case-study-assets/alinma-joint-promotion-1.jpg'],
            ['title' => 'Alinma Eid Campaign', 'body' => 'Seasonal Eid communication for Alinma customers.', 'image' => '/case-study-assets/alinma-eid-1.jpeg'],
            ['title' => 'Alinma Apple Pay', 'body' => 'Digital payment communication introducing Apple Pay benefits for Alinma customers.', 'image' => '/case-study-assets/alinma-apple-pay-1.jpg'],
            ['title' => 'Alinma Mazaya', 'body' => 'Rewards and benefits communication for Alinma Mazaya cardholders.', 'image' => '/case-study-assets/alinma-mazaya-1.jpg'],
            ['title' => 'Alinma Saudi National Day', 'body' => 'Saudi National Day campaign communication for Alinma.', 'image' => '/case-study-assets/alinma-national-day-1.jpg'],
            ['title' => 'Alinma Internal Communication', 'body' => 'Internal communication material supporting engagement and staff awareness.', 'image' => '/case-study-assets/alinma-internal-1.jpg'],
            ['title' => 'Alinma Loyalty Cards', 'body' => 'Loyalty and card communication assets for Alinma’s customer benefit programs.', 'image' => '/case-study-assets/alinma-loyalty-cards-1.jpg'],
            ['title' => 'Alinma Online Branch', 'body' => 'Branch and digital service communication built around the value of human service alongside online banking.', 'image' => '/case-study-assets/alinma-online-branch-1.png'],
            ['title' => 'Mazadak App Relaunch', 'body' => 'A three-phase teaser, revealer, and install campaign for an auction platform expanding beyond cars.', 'image' => '/case-study-assets/mazadak-overview-2.jpeg'],
            ['title' => 'Mazadak Teaser & Revealer', 'body' => 'Teaser and revealer campaign samples introducing Mazadak’s broader auction offering.', 'image' => '/case-study-assets/mazadak-teaser-revealer-1.png'],
            ['title' => 'Mazadak App Install', 'body' => 'App-install communication supporting conversion from awareness into download and platform use.', 'image' => '/case-study-assets/mazadak-app-install-1.jpeg'],
            ['title' => 'Mazadak Digital Campaign', 'body' => 'Social and digital creative showing Mazadak’s auction categories across land, sea, and air.', 'image' => '/case-study-assets/mazadak-digital-3.jpeg'],
            ['title' => 'Careem Captains Recruitment', 'body' => 'A targeted recruitment campaign for Saudi captains, helping shift perception and drive more than 600% increase in sign-ups.', 'image' => '/case-study-assets/careem-captains-2.png'],
            ['title' => 'ITC Zoom Fiber Key Visuals', 'body' => 'Neighborhood-level fiber campaign visuals built around speed, gaming, streaming, and conversion to sign-ups.', 'image' => '/case-study-assets/itc-fiber-key-visuals-1.jpeg'],
            ['title' => 'Mercedes-Benz · She’s Mercedes', 'body' => 'Regional empowerment content for an international Mercedes-Benz initiative celebrating Arab women under “What can stop us?!”', 'image' => '/case-study-assets/mercedes-shes-mercedes-1.png'],
            ['title' => 'Mercedes-Benz Trucks', 'body' => 'A dynamic campaign communicating durability, performance, and capability for Mercedes-Benz Trucks.', 'image' => '/case-study-assets/mercedes-trucks-1.png'],
            ['title' => 'MG MECOTY Launch', 'body' => 'Automotive launch video work for MG MECOTY communication.', 'image' => '/case-study-assets/mg-mecoty-1-1.png'],
            ['title' => 'MG MECOTY Film', 'body' => 'A cinematic automotive execution supporting the MG MECOTY launch.', 'image' => '/case-study-assets/mg-mecoty-2-1.png'],
            ['title' => 'Mercedes-Benz Formula 1 Event', 'body' => 'Event and activation communication connected to the Jeddah Corniche Circuit and the Formula 1 experience.', 'image' => '/case-study-assets/mercedes-f1-event-1.png'],
            ['title' => 'Mercedes-Benz Top End Vehicles Event', 'body' => 'Automotive event communication for Mercedes-Benz top-end vehicle experiences.', 'image' => '/case-study-assets/mercedes-top-end-1.png'],
            ['title' => 'Mercedes-Benz Brand Experience', 'body' => 'Brand experience work connecting Mercedes-Benz with premium automotive moments and event environments.', 'image' => '/case-study-assets/mercedes-brand-experience-1.png'],
            ['title' => 'Hankook We Care Experience', 'body' => 'Activation and experience work for Hankook’s We Care platform.', 'image' => '/case-study-assets/hankook-we-care-1.png'],
            ['title' => 'Siemens Energy Testimonials', 'body' => 'Corporate testimonial content for Siemens Energy, focused on people, operations, and credibility.', 'image' => '/case-study-assets/siemens-energy-testimonials-1.jpg'],
            ['title' => 'The Visitor Founding Day', 'body' => 'Commerce activation and branded experience work for The Visitor around Saudi Founding Day.', 'image' => '/case-study-assets/visitor-founding-day-1.jpeg'],
            ['title' => 'The Visitor Valentine’s Day', 'body' => 'Seasonal commerce activation for The Visitor Valentine’s Day.', 'image' => '/case-study-assets/visitor-valentines-day-1.png'],
            ['title' => 'The Visitor Raffle Draw', 'body' => 'Raffle draw activation communication for The Visitor, including prize-led campaign material.', 'image' => '/case-study-assets/visitor-raffle-draw-1.png'],
            ['title' => 'CEER Office Branding', 'body' => 'Environmental branding and office experience work supporting a future-facing Saudi automotive brand.', 'image' => '/case-study-assets/ceer-office-branding-1.png'],
            ['title' => 'CEER Executive Office Branding', 'body' => 'Office branding applications extending CEER’s identity into executive and meeting spaces.', 'image' => '/case-study-assets/ceer-branding-2-1.png'],
            ['title' => 'CEER Reception Branding', 'body' => 'Reception and branded wall applications for CEER office environments.', 'image' => '/case-study-assets/ceer-branding-3-1.png'],
            ['title' => 'CEER Corridor Branding', 'body' => 'Large-scale environmental graphics and corridor branding for CEER office spaces.', 'image' => '/case-study-assets/ceer-branding-5-1.png'],
        ];
    }
}
