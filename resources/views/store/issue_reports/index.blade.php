@extends('store.registration-layout')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">My Reports</h1>
            <p class="text-gray-600 mt-1">Reports you've filed against customers.</p>
        </div>
        <a href="{{ route('store.issue-reports.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded shadow">
            <i class="bi bi-flag-fill mr-2"></i> Report a Customer
        </a>
    </div>

    @if(session('status'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded shadow-sm">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="bi bi-check-circle-fill text-green-500"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700 font-medium">{{ session('status') }}</p>
                </div>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Report Info</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reported Customer</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th scope="col" class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($reports as $report)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $report->subject }}</div>
                            <div class="text-sm text-gray-500">
                                Priority: 
                                <span class="@if($report->priority == 'high') text-red-600 @elseif($report->priority == 'medium') text-yellow-600 @else text-blue-600 @endif font-medium">
                                    {{ ucfirst($report->priority) }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                @if($report->reportedUser?->avatar)
                                    <img class="h-8 w-8 rounded-full mr-3 object-cover border border-gray-200" src="{{ asset('storage/' . $report->reportedUser->avatar) }}" alt="">
                                @else
                                    <div class="h-8 w-8 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold mr-3 border border-indigo-200">
                                        {{ substr($report->reportedUser?->name ?? '?', 0, 1) }}
                                    </div>
                                @endif
                                <div class="text-sm font-medium text-gray-900">{{ $report->reportedUser?->name ?? 'Unknown' }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusColors = [
                                    'open' => 'bg-blue-100 text-blue-800 border-blue-200',
                                    'in_review' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                    'resolved' => 'bg-green-100 text-green-800 border-green-200',
                                    'closed' => 'bg-gray-100 text-gray-800 border-gray-200',
                                ];
                                $color = $statusColors[$report->status] ?? $statusColors['open'];
                            @endphp
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full border {{ $color }}">
                                {{ ucfirst(str_replace('_', ' ', $report->status)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $report->created_at->format('M d, Y') }}<br>
                            <span class="text-xs text-gray-400">{{ $report->created_at->format('H:i') }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('store.issue-reports.show', $report) }}" class="text-indigo-600 hover:text-indigo-900 flex items-center justify-end gap-1">
                                View Details <i class="bi bi-chevron-right"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 text-gray-400 mb-4">
                                <i class="bi bi-flag fs-3"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-1">No reports filed</h3>
                            <p class="text-gray-500">You haven't reported any customers yet.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
