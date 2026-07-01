<x-app-layout>

    <x-slot name="header">
        Dashboard
    </x-slot>

    <div class="space-y-8">

        <!-- Welcome -->
        <div>
            <h2 class="pg-title">
                Welcome back, {{ Auth::user()->name }}
            </h2>

            <p class="pg-subtitle mt-1">
                PG Integrated Creative Operations Dashboard
            </p>
        </div>

       <!-- Statistics -->
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">

    <div class="pg-card">
        <div class="pg-card-body">
            <div class="pg-stat-label">Total Jobs</div>
            <div class="pg-stat-value">
                {{ $totalJobs }}
            </div>
        </div>
    </div>

    <div class="pg-card">
        <div class="pg-card-body">
            <div class="pg-stat-label">Urgent Jobs</div>
            <div class="pg-stat-value text-red-600">
                {{ $urgentJobs }}
            </div>
        </div>
    </div>

    <div class="pg-card">
        <div class="pg-card-body">
            <div class="pg-stat-label">Clients</div>
            <div class="pg-stat-value">
                {{ $totalClients }}
            </div>
        </div>
    </div>

    <div class="pg-card">
        <div class="pg-card-body">
            <div class="pg-stat-label">Projects</div>
            <div class="pg-stat-value">
                {{ $totalProjects }}
            </div>
        </div>
    </div>

</div>
        <!-- Two Columns -->
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

            <!-- Traffic Board -->
            <div class="pg-card">
                <div class="pg-card-body">

                    <h3 class="text-lg font-bold mb-6">
                        Traffic Board
                    </h3>

                    <div class="space-y-4">

                        <div class="flex justify-between">
                            <span>New Jobs</span>
                            <span class="pg-badge pg-badge-new">14</span>
                        </div>

                        <div class="flex justify-between">
                            <span>In Progress</span>
                            <span class="pg-badge pg-badge-progress">19</span>
                        </div>

                        <div class="flex justify-between">
                            <span>Waiting Review</span>
                            <span class="pg-badge pg-badge-review">5</span>
                        </div>

                        <div class="flex justify-between">
                            <span>Completed</span>
                            <span class="pg-badge pg-badge-completed">12</span>
                        </div>

                    </div>

                </div>
            </div>

            <!-- Team Workload -->
            <div class="pg-card">
                <div class="pg-card-body">

                    <h3 class="text-lg font-bold mb-6">
                        Team Workload
                    </h3>

                    <table class="w-full">

                        <thead>
                            <tr class="text-left border-b">
                                <th class="pb-3">Employee</th>
                                <th class="pb-3">Status</th>
                                <th class="pb-3">Jobs</th>
                            </tr>
                        </thead>

                        <tbody>

                            <tr class="border-b">
                                <td class="py-3">Ahmed</td>
                                <td>
                                    <span class="pg-badge pg-badge-progress">
                                        Busy
                                    </span>
                                </td>
                                <td>8</td>
                            </tr>

                            <tr class="border-b">
                                <td class="py-3">Sarah</td>
                                <td>
                                    <span class="pg-badge pg-badge-completed">
                                        Available
                                    </span>
                                </td>
                                <td>2</td>
                            </tr>

                            <tr>
                                <td class="py-3">Mohammed</td>
                                <td>
                                    <span class="pg-badge pg-badge-review">
                                        Review
                                    </span>
                                </td>
                                <td>4</td>
                            </tr>

                        </tbody>

                    </table>

                </div>
            </div>

        </div>

    </div>

</x-app-layout>
