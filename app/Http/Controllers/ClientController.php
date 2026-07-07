<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Client;
use App\Models\User;
use App\Modules\Clients\Requests\StoreClientRequest;
use App\Modules\Clients\Requests\UpdateClientRequest;
use App\Modules\Clients\Services\ClientService;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function __construct(
        protected ClientService $clientService
    ) {}

    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));

        $clients = Client::query()
            ->with(['branch', 'accountManager', 'clientServiceUsers'])
            ->withCount('projects')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'ilike', "%{$search}%")
                        ->orWhere('client_code', 'ilike', "%{$search}%")
                        ->orWhere('email', 'ilike', "%{$search}%")
                        ->orWhere('contact_person', 'ilike', "%{$search}%")
                        ->orWhere('company_name', 'ilike', "%{$search}%")
                        ->orWhereHas('branch', fn ($branch) => $branch->where('name', 'ilike', "%{$search}%"))
                        ->orWhereHas('clientServiceUsers', fn ($user) => $user->where('name', 'ilike', "%{$search}%"));
                });
            })
            ->orderBy('name')
            ->get();

        return view('admin.clients.index', compact('clients'));
    }

    public function create()
    {
        return view('admin.clients.create', [
            'client' => new Client,
            'branches' => Branch::orderBy('name')->get(),
            'accountManagers' => User::orderBy('name')->get(),
        ]);
    }

    public function store(StoreClientRequest $request)
    {
        $client = $this->clientService->create(
            $request->validated()
        );

        return redirect()
            ->route('admin.clients.show', $client)
            ->with('success', 'Client created successfully.');
    }

    public function show(Client $client)
    {
        return view('admin.clients.show', [
            'client' => $this->clientService->find($client->id),
        ]);
    }

    public function edit(Client $client)
    {
        return view('admin.clients.edit', [
            'client' => $client->loadMissing('clientServiceUsers'),
            'branches' => Branch::orderBy('name')->get(),
            'accountManagers' => User::orderBy('name')->get(),
        ]);
    }

    public function update(
        UpdateClientRequest $request,
        Client $client
    ) {
        $this->clientService->update(
            $client,
            $request->validated()
        );

        return redirect()
            ->route('admin.clients.show', $client)
            ->with('success', 'Client updated successfully.');
    }

    public function destroy(Client $client)
    {
        $this->clientService->delete($client);

        return redirect()
            ->route('admin.clients.index')
            ->with('success', 'Client deleted successfully.');
    }
}
