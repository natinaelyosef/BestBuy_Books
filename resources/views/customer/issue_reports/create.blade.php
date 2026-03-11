@extends('customer.base')

@section('title', 'Report an Issue - BookHub')

@section('content')
<section class="issue-form-shell">
    <div class="issue-form-head">
        <div>
            <h1>Report an Issue</h1>
            <p>Provide details so our admin team can help quickly.</p>
        </div>
        <a href="{{ route('issue-reports.index') }}" class="btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to Reports
        </a>
    </div>

    <form method="POST" action="{{ route('issue-reports.store') }}" class="issue-form">
        @csrf
        <label class="field">
            <span>Subject</span>
            <input type="text" name="subject" value="{{ old('subject') }}" required maxlength="255" placeholder="Example: Payment failed on checkout">
        </label>

        <label class="field">
            <span>Description</span>
            <textarea name="description" rows="6" required maxlength="4000" placeholder="Describe the issue in detail.">{{ old('description') }}</textarea>
        </label>

        <label class="field">
            <span>Priority</span>
            <select name="priority" required>
                <option value="low" @selected(old('priority') === 'low')>Low</option>
                <option value="medium" @selected(old('priority', 'medium') === 'medium')>Medium</option>
                <option value="high" @selected(old('priority') === 'high')>High</option>
            </select>
        </label>

        <button type="submit" class="btn-primary">
            <i class="bi bi-flag-fill"></i>
            Submit Report
        </button>
    </form>
</section>
@endsection

@section('extra_css')
<style>
.issue-form-shell {
    max-width: 760px;
    margin: 0 auto;
    display: grid;
    gap: 1.2rem;
}
.issue-form-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 1.2rem 1.4rem;
    box-shadow: var(--shadow-sm);
}
.issue-form-head h1 {
    margin: 0 0 0.35rem 0;
    font-size: 1.5rem;
}
.issue-form-head p {
    margin: 0;
    color: var(--text-secondary);
    font-size: 0.9rem;
}
.issue-form {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 1.4rem;
    box-shadow: var(--shadow-sm);
    display: grid;
    gap: 1rem;
}
.field {
    display: grid;
    gap: 0.45rem;
    font-size: 0.82rem;
    color: var(--text-secondary);
    font-weight: 600;
}
.field input,
.field textarea,
.field select {
    background: var(--bg-raised);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 0.7rem 0.8rem;
    font-family: 'Outfit', sans-serif;
    color: var(--text-primary);
}
.btn-primary {
    justify-self: flex-start;
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
    padding: 0.65rem 1.2rem;
    border-radius: 999px;
    background: var(--primary);
    color: #fff;
    font-weight: 700;
    border: none;
    cursor: pointer;
}
.btn-secondary {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.6rem 1.1rem;
    border-radius: 999px;
    background: var(--bg-raised);
    border: 1px solid var(--border);
    color: var(--text-secondary);
    text-decoration: none;
    font-weight: 700;
}
</style>
@endsection
