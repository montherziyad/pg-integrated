<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Modules\Clients\Requests\StoreClientRequest;
use App\Modules\Clients\Requests\UpdateClientRequest;
use App\Modules\Clients\Services\ClientService;

class ClientController extends Controller
{
    public function __construct(
        protected ClientService $clientService
    ) {}

    /**
     * Clients List
     */
    public function index()
    {
        $clients = $this->clientService->all();

        return view('clients.index', compact('clients'));
    }

    /**
     * Create Client Form
     */
    public function create()
    {
        return view('clients.create');
    }

    /**
     * Store Client
     */
    public function store(StoreClientRequest $request)
    {
        $client = $this->clientService->create(
            $request->validated()
        );

        return redirect()
            ->route('clients.show', $client)
            ->with('success', 'Client created successfully.');
    }

    /**
     * Client Details
     */
    public function show(Client $client)
    {
        return view('clients.show', [
            'client' => $this->clientService->find($client->id),
        ]);
    }

    /**
     * Edit Client
     */
    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    /**
     * Update Client
     */
    public function update(
        UpdateClientRequest $request,
        Client $client
    ) {
        $this->clientService->update(
            $client,
            $request->validated()
        );

        return redirect()
            ->route('clients.show', $client)
            ->with('success', 'Client updated successfully.');
    }

    /**
     * Delete Client
     */
    public function destroy(Client $client)
    {
        $this->clientService->delete($client);

        return redirect()
            ->route('clients.index')
            ->with('success', 'Client deleted successfully.');
    }
}