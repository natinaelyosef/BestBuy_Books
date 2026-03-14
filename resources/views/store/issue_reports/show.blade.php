@extends('store.sales_dashboard')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('store.issue-reports.index') }}" class="text-gray-500 hover:text-indigo-600 transition-colors">
                <i class="bi bi-arrow-left text-xl"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Report Details</h1>
                <p class="text-gray-600 mt-1">Report #{{ $issueReport->id }}</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
            <div class="p-6 sm:p-8 border-b border-gray-100 flex flex-wrap justify-between items-start gap-4">
                <div>
                    <h2 class="text-xl font-bold text-gray-900">{{ $issueReport->subject }}</h2>
                    <p class="text-gray-500 text-sm mt-1">Submitted on {{ $issueReport->created_at->format('F j, Y \a\t g:i A') }}</p>
                </div>
                <div class="flex gap-2">
                    @php
                        $statusColors = [
                            'open' => 'bg-blue-100 text-blue-800 border-blue-200',
                            'in_review' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                            'resolved' => 'bg-green-100 text-green-800 border-green-200',
                            'closed' => 'bg-gray-100 text-gray-800 border-gray-200',
                        ];
                        $color = $statusColors[$issueReport->status] ?? $statusColors['open'];
                    @endphp
                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full border {{ $color }}">
                        {{ ucfirst(str_replace('_', ' ', $issueReport->status)) }}
                    </span>
                    
                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full border 
                        @if($issueReport->priority == 'high') bg-red-100 text-red-800 border-red-200
                        @elseif($issueReport->priority == 'medium') bg-orange-100 text-orange-800 border-orange-200
                        @else bg-blue-100 text-blue-800 border-blue-200 @endif">
                        Priority: {{ ucfirst($issueReport->priority) }}
                    </span>
                </div>
            </div>

            <div class="p-6 sm:p-8 bg-gray-50 border-b border-gray-100 flex items-center gap-4">
                <div class="h-12 w-12 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-xl border border-indigo-200 shadow-sm">
                    {{ substr($issueReport->reportedUser?->name ?? '?', 0, 1) }}
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-1">Reported Customer</h3>
                    <div class="text-gray-900 font-medium text-lg">{{ $issueReport->reportedUser?->name ?? 'Unknown Customer' }}</div>
                    @if($issueReport->reportedUser)
                        <div class="text-gray-500 text-sm">{{ $issueReport->reportedUser->email }}</div>
                    @endif
                </div>
            </div>

            <div class="p-6 sm:p-8">
                <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-3">Description</h3>
                <div class="prose max-w-none text-gray-800 whitespace-pre-wrap bg-white border border-gray-200 rounded-lg p-5 shadow-inner">{{ $issueReport->description }}</div>

                @if($issueReport->hasEvidence())
                    <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mt-8 mb-3">Provided Evidence</h3>
                    <div class="border border-gray-200 rounded-lg p-4 bg-gray-50 flex flex-col gap-4">
                        <div class="flex items-center gap-3">
                            <i class="bi {{ $issueReport->isEvidenceImage() ? 'bi-file-image text-blue-500' : 'bi-file-earmark-text text-gray-500' }} text-3xl"></i>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $issueReport->evidence_name }}</p>
                                <a href="{{ $issueReport->evidence_url }}" target="_blank" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium flex items-center gap-1 mt-1">
                                    <i class="bi bi-box-arrow-up-right"></i> View File
                                </a>
                            </div>
                        </div>
                        
                        @if($issueReport->isEvidenceImage())
                            <div class="mt-2 border border-gray-200 rounded overflow-hidden shadow-sm bg-white">
                                <img src="{{ $issueReport->evidence_url }}" alt="Evidence" class="max-w-full h-auto" style="max-height: 400px; object-fit: contain; width: 100%;">
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            @if($issueReport->assignedAdmin)
            <div class="bg-indigo-50 p-6 sm:p-8 border-t border-indigo-100 flex items-center gap-3">
                <i class="bi bi-shield-check text-indigo-600 text-2xl"></i>
                <div>
                    <p class="text-indigo-900 font-medium">Assigned to Admin: {{ $issueReport->assignedAdmin->name }}</p>
                    <p class="text-indigo-700 text-sm">Our team is reviewing your report.</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
