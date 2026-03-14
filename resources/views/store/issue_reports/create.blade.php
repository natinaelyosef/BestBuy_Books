@extends('store.registration-layout')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('store.issue-reports.index') }}" class="text-gray-500 hover:text-indigo-600 transition-colors">
                <i class="bi bi-arrow-left text-xl"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Report a Customer</h1>
                <p class="text-gray-600 mt-1">If a customer violates our rules, report them here.</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 sm:p-8">
                <form action="{{ route('store.issue-reports.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    
                    <div>
                        <label for="reported_user_id" class="block text-sm font-medium text-gray-700 mb-1">Customer to Report</label>
                        <select name="reported_user_id" id="reported_user_id" required class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md shadow-sm border bg-white">
                            <option value="">Select a customer...</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" @selected(old('reported_user_id') == $customer->id)>
                                    {{ $customer->name }} ({{ $customer->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('reported_user_id')
                            <p class="mt-1 flex items-center text-xs text-red-500 bg-red-50 px-2 py-1 rounded"><i class="bi bi-exclamation-circle mr-1"></i> {{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Issue Subject</label>
                        <input type="text" name="subject" id="subject" value="{{ old('subject') }}" required placeholder="Brief description of the issue" class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md py-2 px-3 border">
                        @error('subject')
                            <p class="mt-1 flex items-center text-xs text-red-500 bg-red-50 px-2 py-1 rounded">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Full Details</label>
                        <textarea name="description" id="description" rows="5" required placeholder="Provide all necessary context so our admins can take action..." class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md p-3 border">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 flex items-center text-xs text-red-500 bg-red-50 px-2 py-1 rounded">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="priority" class="block text-sm font-medium text-gray-700 mb-1">Severity / Priority</label>
                            <select name="priority" id="priority" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md shadow-sm border bg-white">
                                <option value="low" @selected(old('priority') == 'low')>Low</option>
                                <option value="medium" @selected(old('priority', 'medium') == 'medium')>Medium</option>
                                <option value="high" @selected(old('priority') == 'high')>High (Urgent action needed)</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Evidence (Optional)</label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:bg-gray-50 transition-colors">
                                <div class="space-y-1 text-center">
                                    <i class="bi bi-cloud-arrow-up text-3xl text-gray-400"></i>
                                    <div class="flex text-sm text-gray-600 justify-center">
                                        <label for="evidence" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                            <span>Upload a file</span>
                                            <input id="evidence" name="evidence" type="file" class="sr-only" accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.txt">
                                        </label>
                                    </div>
                                    <p class="text-xs text-gray-500 border border-gray-200 mt-2 bg-white px-2 py-1 rounded inline-block" id="file-name">
                                        PNG, JPG, PDF up to 10MB
                                    </p>
                                </div>
                            </div>
                            @error('evidence')
                                <p class="mt-1 flex items-center text-xs text-red-500 bg-red-50 px-2 py-1 rounded">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-100 flex justify-end">
                        <button type="submit" class="inline-flex justify-center py-2.5 px-6 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <i class="bi bi-send-fill mr-2"></i> Submit Report
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('evidence').addEventListener('change', function(e) {
        if(e.target.files.length > 0) {
            document.getElementById('file-name').innerHTML = '<span class="text-indigo-600 font-bold"><i class="bi bi-check-circle-fill"></i> ' + e.target.files[0].name + '</span>';
        }
    });
</script>
@endsection
