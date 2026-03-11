@extends('store.registration-layout')
@section('title', 'Store Management')
@section('content')

<div class="container-fluid py-4">

    <!-- Header + Add Button -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0 fw-semibold text-dark">Store Management</h4>
        <a href="{{ route('store.registration.create') }}" class="btn btn-primary btn-lg shadow-sm">
            <i class="fas fa-plus me-2"></i>Add New Store
        </a>
    </div>
    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle mb-0 small">
                    <thead class="bg-dark text-white text-center">
                        <tr>
                            <th class="py-3">ID</th>
                            <th class="py-3">Store Name</th>
                            <th class="py-3">Owner</th>
                            <th class="py-3">Email</th>
                            <th class="py-3">Phone</th>
                            <th class="py-3">Type</th>
                            <th class="py-3">City</th>
                            <th class="py-3">Sub City</th>
                            <th class="py-3">Address</th>
                            <th class="py-3">Description</th>

                            <!-- Services -->
                            <th class="py-3">Rental</th>
                            <th class="py-3">Sale</th>
                            <th class="py-3">Delivery</th>
                            <th class="py-3">Del. Fee</th>
                            <th class="py-3">Bike</th>
                            <th class="py-3">Car</th>
                            <th class="py-3">Pickup</th>

                            <!-- Working Days - grouped visually -->
                            <th colspan="4" class="bg-secondary text-white">Monday</th>
                            <th colspan="4" class="bg-secondary text-white">Tuesday</th>
                            <th colspan="4" class="bg-secondary text-white">Wednesday</th>
                            <th colspan="4" class="bg-secondary text-white">Thursday</th>
                            <th colspan="4" class="bg-secondary text-white">Friday</th>
                            <th colspan="4" class="bg-secondary text-white">Saturday</th>
                            <th colspan="4" class="bg-secondary text-white">Sunday</th>

                            <!-- Rental Terms -->
                            <th class="py-3">Period</th>
                            <th class="py-3">Price</th>
                            <th class="py-3">Late Fee</th>
                            <th class="py-3">Deposit</th>
                            <th class="py-3">Max Books</th>
                            <th class="py-3">Discount</th>
                            <th class="py-3">Payments</th>
                            <th class="py-3">Terms</th>

                            <th class="py-3">Actions</th>
                        </tr>
                        <!-- Second header row for day details -->
                        <tr class="bg-dark text-white text-center small fw-normal">
                            <th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th>
                            <!-- Services -->
                            <th></th><th></th><th></th><th></th><th></th><th></th><th></th>
                            <!-- Monday -->
                            <th>Open</th>
                            <th>Open Time</th>
                            <th>Close Time</th>
                            <th>Closed</th>
                            <!-- Tuesday -->
                            <th>Open</th>
                            <th>Open Time</th>
                            <th>Close Time</th>
                            <th>Closed</th>
                            <!-- Wednesday -->
                            <th>Open</th>
                            <th>Open Time</th>
                            <th>Close Time</th>
                            <th>Closed</th>
                            <!-- Thursday -->
                            <th>Open</th>
                            <th>Open Time</th>
                            <th>Close Time</th>
                            <th>Closed</th>
                            <!-- Friday -->
                            <th>Open</th>
                            <th>Open Time</th>
                            <th>Close Time</th>
                            <th>Closed</th>
                            <!-- Saturday -->
                            <th>Open</th>
                            <th>Open Time</th>
                            <th>Close Time</th>
                            <th>Closed</th>
                            <!-- Sunday -->
                            <th>Open</th>
                            <th>Open Time</th>
                            <th>Close Time</th>
                            <th>Closed</th>
                            <!-- Rental -->
                            <th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody class="text-center">
                        @php
                            $get = function ($store, array $keys, $default = null) {
                                foreach ($keys as $key) {
                                    $value = data_get($store, $key);
                                    if ($value !== null && $value !== '') {
                                        return $value;
                                    }
                                }
                                return $default;
                            };
                            $yesNo = function ($value, $dash = '-') {
                                return is_null($value) ? $dash : ($value ? 'Yes' : 'No');
                            };
                            $yesNoClosed = function ($value, $dash = '-') {
                                return is_null($value) ? $dash : ($value ? 'Closed' : 'Open');
                            };
                            $money = function ($value) {
                                return is_null($value) || $value == '' ? '0.00' : number_format((float) $value, 2, '.', '');
                            };
                            $text = function ($value, $dash = '-') {
                                return is_null($value) || $value === '' ? $dash : $value;
                            };
                        @endphp
                        @forelse ($stores as $store)
                            @php
                                $paymentMethods = $store->payment_methods ?? null;
                                if (is_array($paymentMethods)) {
                                    $paymentMethods = implode(', ', $paymentMethods);
                                }
                                $description = $get($store, ['store_description', 'description'], null);
                            @endphp
                            <tr>
                                <td class="fw-bold text-muted">{{ $store->id }}</td>
                                <td class="fw-semibold">{{ $text($get($store, ['store_name', 'name'])) }}</td>
                                <td>{{ $text($get($store, ['owner_full_name', 'owner_name'])) }}</td>
                                <td class="text-nowrap">{{ $text($get($store, ['email'])) }}</td>
                                <td>{{ $text($get($store, ['phone'])) }}</td>
                                <td>{{ $text($get($store, ['store_type', 'type'])) }}</td>
                                <td>{{ $text($get($store, ['city'])) }}</td>
                                <td>{{ $text($get($store, ['sub_city', 'subcity'])) }}</td>
                                <td class="text-start">{{ $text($get($store, ['address'])) }}</td>
                                <td class="text-start text-truncate" style="max-width: 180px;" title="{{ $description ?? '' }}">
                                    {{ $description ? \Illuminate\Support\Str::words($description, 8, '...') : '-' }}
                                </td>

                                <td><span class="badge bg-success-subtle text-success">{{ $yesNo($get($store, ['offers_rental'])) }}</span></td>
                                <td><span class="badge bg-success-subtle text-success">{{ $yesNo($get($store, ['offers_sale'])) }}</span></td>
                                <td><span class="badge bg-info-subtle text-info">{{ $yesNo($get($store, ['offers_delivery'])) }}</span></td>
                                <td class="fw-medium">${{ $money($get($store, ['delivery_fee'])) }}</td>
                                <td>{{ $yesNo($get($store, ['delivery_bike'])) }}</td>
                                <td>{{ $yesNo($get($store, ['delivery_car'])) }}</td>
                                <td>{{ $yesNo($get($store, ['delivery_pickup'])) }}</td>

                                <!-- Monday -->
                                <td>{{ $yesNo($get($store, ['open_monday'])) }}</td>
                                <td>{{ $text($get($store, ['open_time_monday'])) }}</td>
                                <td>{{ $text($get($store, ['close_time_monday'])) }}</td>
                                <td>{{ $yesNoClosed($get($store, ['closed_monday', 'close_monday'])) }}</td>

                                <!-- Tuesday -->
                                <td>{{ $yesNo($get($store, ['open_tuesday'])) }}</td>
                                <td>{{ $text($get($store, ['open_time_tuesday'])) }}</td>
                                <td>{{ $text($get($store, ['close_time_tuesday'])) }}</td>
                                <td>{{ $yesNoClosed($get($store, ['closed_tuesday', 'close_tuesday'])) }}</td>

                                <!-- Wednesday -->
                                <td>{{ $yesNo($get($store, ['open_wednesday'])) }}</td>
                                <td>{{ $text($get($store, ['open_time_wednesday'])) }}</td>
                                <td>{{ $text($get($store, ['close_time_wednesday'])) }}</td>
                                <td>{{ $yesNoClosed($get($store, ['closed_wednesday', 'close_wednesday'])) }}</td>

                                <!-- Thursday -->
                                <td>{{ $yesNo($get($store, ['open_thursday'])) }}</td>
                                <td>{{ $text($get($store, ['open_time_thursday'])) }}</td>
                                <td>{{ $text($get($store, ['close_time_thursday'])) }}</td>
                                <td>{{ $yesNoClosed($get($store, ['closed_thursday', 'close_thursday'])) }}</td>

                                <!-- Friday -->
                                <td>{{ $yesNo($get($store, ['open_friday'])) }}</td>
                                <td>{{ $text($get($store, ['open_time_friday'])) }}</td>
                                <td>{{ $text($get($store, ['close_time_friday'])) }}</td>
                                <td>{{ $yesNoClosed($get($store, ['closed_friday', 'close_friday'])) }}</td>

                                <!-- Saturday -->
                                <td>{{ $yesNo($get($store, ['open_saturday'])) }}</td>
                                <td>{{ $text($get($store, ['open_time_saturday'])) }}</td>
                                <td>{{ $text($get($store, ['close_time_saturday'])) }}</td>
                                <td>{{ $yesNoClosed($get($store, ['closed_saturday', 'close_saturday'])) }}</td>

                                <!-- Sunday -->
                                <td>{{ $yesNo($get($store, ['open_sunday'])) }}</td>
                                <td>{{ $text($get($store, ['open_time_sunday'])) }}</td>
                                <td>{{ $text($get($store, ['close_time_sunday'])) }}</td>
                                <td>{{ $yesNoClosed($get($store, ['closed_sunday', 'close_sunday'])) }}</td>

                                <!-- Rental Terms -->
                                <td>{{ $text($get($store, ['rental_period', 'rental_period_days'])) }}</td>
                                <td class="fw-medium">${{ $money($get($store, ['rental_price', 'rental_price_per_day'])) }}</td>
                                <td>${{ $money($get($store, ['late_fee', 'late_fee_per_day'])) }}</td>
                                <td>${{ $money($get($store, ['security_deposit'])) }}</td>
                                <td>{{ $text($get($store, ['max_books', 'max_books_per_customer'])) }}</td>
                                <td>{{ $text($get($store, ['discount_percent', 'student_discount_percent']), '0') }}%</td>
                                <td class="text-start">{{ $text($paymentMethods ?? $get($store, ['payment_methods', 'payment_method'])) }}</td>
                                <td>{{ $yesNo($get($store, ['agree_terms', 'agreed_terms'])) }}</td>

                                <td class="text-nowrap">
                                    @if(($storeSource ?? 'registrations') === 'registrations')
                                        <a href="{{ route('store.registration.edit', ['store_id' => $store->id]) }}" class="btn btn-outline-primary btn-sm me-2">
                                            <i class="far fa-edit"></i>edit
                                        </a>
                                        <form action="{{ route('store.registration.destroy', $store->id) }}" method="post" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm"
                                                    onclick="return confirm('Delete this store permanently?');">
                                                <i class="far fa-trash-alt"></i>delete
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted">Not editable</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="70" class="text-center py-5 text-muted fs-5">
                                    <i class="fas fa-store-slash me-2"></i>No stores found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

@endsection
