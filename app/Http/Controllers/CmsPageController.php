<?php

namespace App\Http\Controllers;

use App\Models\CmsPage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CmsPageController extends Controller
{
    public function index(): View
    {
        return view('admin.cms.index', [
            'pages' => CmsPage::query()->latest()->paginate(20),
        ]);
    }

    public function create(): View
    {
        return view('admin.cms.form', ['page' => new CmsPage]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['updated_by'] = $request->user()->id;
        CmsPage::create($data);

        return redirect()->route('admin.cms.index')->with('status', 'Page created.');
    }

    public function edit(CmsPage $cms): View
    {
        return view('admin.cms.form', ['page' => $cms]);
    }

    public function update(Request $request, CmsPage $cms): RedirectResponse
    {
        $data = $this->validated($request, $cms);
        $data['updated_by'] = $request->user()->id;
        $cms->update($data);

        return redirect()->route('admin.cms.index')->with('status', 'Page updated.');
    }

    public function destroy(CmsPage $cms): RedirectResponse
    {
        $cms->delete();

        return redirect()->route('admin.cms.index')->with('status', 'Page deleted.');
    }

    private function validated(Request $request, ?CmsPage $page = null): array
    {
        $data = $request->validate([
            'key' => [
                'required',
                'string',
                'max:120',
                Rule::unique('cms_pages', 'key')->ignore($page),
            ],
            'title' => ['required', 'string', 'max:180'],
            'slug' => [
                'required',
                'string',
                'max:180',
                Rule::unique('cms_pages', 'slug')->ignore($page),
            ],
            'page_type' => ['nullable', 'string', Rule::in(['home', 'content', 'services', 'portfolio', 'team', 'clients', 'contact'])],
            'sections' => ['nullable', 'json'],
            'seo' => ['nullable', 'json'],
            'seo_title' => ['nullable', 'string', 'max:180'],
            'seo_description' => ['nullable', 'string', 'max:300'],
            'is_published' => ['nullable', 'boolean'],
            'hero_eyebrow' => ['nullable', 'string', 'max:180'],
            'hero_title' => ['nullable', 'string', 'max:240'],
            'hero_body' => ['nullable', 'string', 'max:1000'],
            'hero_image' => ['nullable', 'string', 'max:1000'],
            'hero_image_upload' => ['nullable', 'file', 'mimetypes:image/jpeg,image/png,image/webp,image/gif', 'max:20480'],
            'hero_video_url' => ['nullable', 'string', 'max:1000'],
            'hero_video_upload' => ['nullable', 'file', 'mimetypes:video/mp4,video/webm,video/quicktime', 'max:102400'],
            'primary_label' => ['nullable', 'string', 'max:120'],
            'primary_url' => ['nullable', 'string', 'max:1000'],
            'secondary_label' => ['nullable', 'string', 'max:120'],
            'secondary_url' => ['nullable', 'string', 'max:1000'],
            'intro_eyebrow' => ['nullable', 'string', 'max:180'],
            'intro_title' => ['nullable', 'string', 'max:240'],
            'intro_body' => ['nullable', 'string', 'max:1200'],
            'cta_title' => ['nullable', 'string', 'max:240'],
            'cta_body' => ['nullable', 'string', 'max:1200'],
            'cta_label' => ['nullable', 'string', 'max:120'],
            'cta_url' => ['nullable', 'string', 'max:1000'],
            'stats' => ['nullable', 'array'],
            'stats.*.value' => ['nullable', 'string', 'max:60'],
            'stats.*.label' => ['nullable', 'string', 'max:160'],
            'services_title' => ['nullable', 'string', 'max:240'],
            'services_body' => ['nullable', 'string', 'max:1200'],
            'services' => ['nullable', 'array'],
            'services.*.title' => ['nullable', 'string', 'max:240'],
            'services.*.body' => ['nullable', 'string', 'max:1200'],
            'services.*.image' => ['nullable', 'string', 'max:1000'],
            'services.*.image_upload' => ['nullable', 'file', 'mimetypes:image/jpeg,image/png,image/webp,image/gif', 'max:20480'],
            'services.*.video_url' => ['nullable', 'string', 'max:1000'],
            'services.*.video_upload' => ['nullable', 'file', 'mimetypes:video/mp4,video/webm,video/quicktime', 'max:102400'],
            'content_items' => ['nullable', 'array'],
            'content_items.*.title' => ['nullable', 'string', 'max:240'],
            'content_items.*.body' => ['nullable', 'string', 'max:1200'],
            'portfolio_title' => ['nullable', 'string', 'max:240'],
            'portfolio' => ['nullable', 'array'],
            'portfolio.*.title' => ['nullable', 'string', 'max:240'],
            'portfolio.*.body' => ['nullable', 'string', 'max:1200'],
            'portfolio.*.image' => ['nullable', 'string', 'max:1000'],
            'portfolio.*.image_upload' => ['nullable', 'file', 'mimetypes:image/jpeg,image/png,image/webp,image/gif', 'max:20480'],
            'portfolio.*.video_url' => ['nullable', 'string', 'max:1000'],
            'portfolio.*.video_upload' => ['nullable', 'file', 'mimetypes:video/mp4,video/webm,video/quicktime', 'max:102400'],
            'team' => ['nullable', 'array'],
            'team.*.name' => ['nullable', 'string', 'max:180'],
            'team.*.role' => ['nullable', 'string', 'max:180'],
            'team.*.email' => ['nullable', 'string', 'max:180'],
            'team.*.image' => ['nullable', 'string', 'max:1000'],
            'team.*.image_upload' => ['nullable', 'file', 'mimetypes:image/jpeg,image/png,image/webp,image/gif', 'max:20480'],
            'team.*.video_url' => ['nullable', 'string', 'max:1000'],
            'team.*.video_upload' => ['nullable', 'file', 'mimetypes:video/mp4,video/webm,video/quicktime', 'max:102400'],
            'clients' => ['nullable', 'array'],
            'clients.*.name' => ['nullable', 'string', 'max:180'],
            'clients.*.image' => ['nullable', 'string', 'max:1000'],
            'clients.*.image_upload' => ['nullable', 'file', 'mimetypes:image/jpeg,image/png,image/webp,image/gif', 'max:20480'],
            'clients.*.video_url' => ['nullable', 'string', 'max:1000'],
            'clients.*.video_upload' => ['nullable', 'file', 'mimetypes:video/mp4,video/webm,video/quicktime', 'max:102400'],
            'contacts' => ['nullable', 'array'],
            'contacts.*.label' => ['nullable', 'string', 'max:120'],
            'contacts.*.value' => ['nullable', 'string', 'max:240'],
            'contacts.*.url' => ['nullable', 'string', 'max:1000'],
            'locations' => ['nullable', 'array'],
            'locations.*.city' => ['nullable', 'string', 'max:180'],
            'locations.*.address' => ['nullable', 'string', 'max:500'],
        ]);

        $advancedSections = filled($data['sections'] ?? null);
        $advancedSeo = filled($data['seo'] ?? null);

        $data['sections'] = $advancedSections
            ? json_decode($data['sections'], true, flags: JSON_THROW_ON_ERROR)
            : $this->structuredSections($request, $page);

        $data['seo'] = $advancedSeo
            ? json_decode($data['seo'], true, flags: JSON_THROW_ON_ERROR)
            : array_filter([
                'title' => $data['seo_title'] ?? null,
                'description' => $data['seo_description'] ?? null,
            ], fn ($value) => filled($value));

        $data['is_published'] = $request->boolean('is_published');

        return collect($data)
            ->only(['key', 'title', 'slug', 'sections', 'seo', 'is_published'])
            ->all();
    }

    private function structuredSections(Request $request, ?CmsPage $page = null): array
    {
        $existing = $page?->sections ?? [];
        $type = $request->string('page_type')->toString() ?: data_get($existing, 'type', $request->string('key')->toString());
        $type = in_array($type, ['home', 'content', 'services', 'portfolio', 'team', 'clients', 'contact'], true) ? $type : 'content';

        $sections = ['type' => $type];
        $hero = $this->clean([
            'eyebrow' => $request->input('hero_eyebrow'),
            'title' => $request->input('hero_title'),
            'body' => $request->input('hero_body'),
            'image' => $this->uploadedUrl($request, 'hero_image_upload') ?: $request->input('hero_image'),
            'video_url' => $this->uploadedUrl($request, 'hero_video_upload') ?: $request->input('hero_video_url'),
            'primary_label' => $request->input('primary_label'),
            'primary_url' => $request->input('primary_url'),
            'secondary_label' => $request->input('secondary_label'),
            'secondary_url' => $request->input('secondary_url'),
        ]);

        if ($hero !== []) {
            $sections['hero'] = $hero;
        }

        $stats = $this->cleanRows($request->input('stats', []), ['value', 'label']);
        if ($stats !== []) {
            $sections['stats'] = $stats;
        }

        $intro = $this->clean([
            'eyebrow' => $request->input('intro_eyebrow'),
            'title' => $request->input('intro_title'),
            'body' => $request->input('intro_body'),
        ]);
        if ($intro !== []) {
            $sections['intro'] = $intro;
        }

        $contentItems = $this->cleanRows($request->input('content_items', []), ['title', 'body']);
        if ($type === 'content' && $contentItems !== []) {
            $sections['items'] = $contentItems;
        }

        $services = $this->cleanRowsWithUploads($request, 'services', ['title', 'body', 'image', 'video_url']);
        if ($services !== []) {
            if ($type === 'services') {
                $sections['items'] = $services;
            } else {
                $sections['services'] = $this->clean([
                    'title' => $request->input('services_title'),
                    'body' => $request->input('services_body'),
                    'items' => $services,
                ]);
            }
        }

        $portfolio = $this->cleanRowsWithUploads($request, 'portfolio', ['title', 'body', 'image', 'video_url']);
        if ($portfolio !== []) {
            if ($type === 'portfolio') {
                $sections['items'] = $portfolio;
            } else {
                $sections['portfolio'] = $this->clean([
                    'title' => $request->input('portfolio_title'),
                    'items' => $portfolio,
                ]);
            }
        }

        $team = $this->cleanRowsWithUploads($request, 'team', ['name', 'role', 'email', 'image', 'video_url']);
        if ($team !== []) {
            $sections['items'] = $team;
        }

        $clients = $this->cleanRowsWithUploads($request, 'clients', ['name', 'image', 'video_url']);
        if ($clients !== []) {
            $sections['items'] = $clients;
        }

        $contacts = $this->cleanRows($request->input('contacts', []), ['label', 'value', 'url']);
        if ($contacts !== []) {
            $sections['contacts'] = $contacts;
        }

        $locations = $this->cleanRows($request->input('locations', []), ['city', 'address', 'lat', 'lng', 'map_url']);
        if ($locations !== []) {
            $sections['locations'] = $locations;
        }

        $cta = $this->clean([
            'title' => $request->input('cta_title'),
            'body' => $request->input('cta_body'),
            'label' => $request->input('cta_label'),
            'url' => $request->input('cta_url'),
        ]);
        if ($cta !== []) {
            $sections['cta'] = $cta;
        }

        return $sections;
    }

    private function cleanRowsWithUploads(Request $request, string $key, array $fields): array
    {
        $rows = [];

        foreach ($request->input($key, []) as $index => $row) {
            if ($url = $this->uploadedUrl($request, "{$key}.{$index}.image_upload")) {
                $row['image'] = $url;
            }

            if ($url = $this->uploadedUrl($request, "{$key}.{$index}.video_upload")) {
                $row['video_url'] = $url;
            }

            $rows[] = $this->clean(collect($fields)
                ->mapWithKeys(fn ($field) => [$field => $row[$field] ?? null])
                ->all());
        }

        return array_values(array_filter($rows, fn ($row) => $row !== []));
    }

    private function cleanRows(array $rows, array $fields): array
    {
        return array_values(array_filter(array_map(
            fn ($row) => $this->clean(collect($fields)
                ->mapWithKeys(fn ($field) => [$field => $row[$field] ?? null])
                ->all()),
            $rows,
        ), fn ($row) => $row !== []));
    }

    private function clean(array $values): array
    {
        return array_filter($values, fn ($value) => filled($value) || is_array($value));
    }

    private function uploadedUrl(Request $request, string $key): ?string
    {
        if (! $request->hasFile($key)) {
            return null;
        }

        $path = $request->file($key)->store('website', 'public');

        return Storage::url($path);
    }
}
