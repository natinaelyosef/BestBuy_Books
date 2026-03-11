<?php

namespace App\Http\Controllers;

use App\Models\StoreRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Inertia\Inertia;

class StoreRegistrationController extends Controller
{
    public function create(Request $request)
    {
        if ($request->header('X-Inertia')) {
            return Inertia::location(url('/store/registration'));
        }

        return view('store.registration');
    }

    public function edit(Request $request)
    {
        if ($request->header('X-Inertia')) {
            return Inertia::location(url('/store/registration/update'));
        }

        $store = null;

        if ($request->filled('store_id') && Schema::hasTable('store_registrations')) {
            $store = StoreRegistration::find($request->input('store_id'));
        }

        return view('store.registration-update', [
            'store' => $store,
            'storeId' => $store?->id,
        ]);
    }

    public function show(Request $request)
    {
        if ($request->header('X-Inertia')) {
            return Inertia::location(url('/store/registration/view'));
        }

        $stores = collect();
        $storeSource = 'registrations';

        if (Schema::hasTable('store_registrations')) {
            $stores = StoreRegistration::query()->latest('id')->get();
        }

        if ($stores->isEmpty() && Schema::hasTable('stores')) {
            $stores = DB::table('stores')->orderByDesc('id')->get();
            $storeSource = 'stores';
        }

        return view('store.registration-view', compact('stores', 'storeSource'));
    }

    public function store(Request $request)
    {
        if (!Schema::hasTable('store_registrations')) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Store registration table is missing. Run migrations first.');
        }

        $validated = $request->validate([
            'store_name' => 'required|string|max:255',
            'owner_full_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'country_code' => 'nullable|string|max:10',
            'phone' => 'nullable|string|max:30',
            'store_type' => 'nullable|string|max:50',
            'city' => 'nullable|string|max:100',
            'subcity' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:255',
            'store_description' => 'nullable|string|max:2000',
            'delivery_radius' => 'nullable|integer|min:0',
            'delivery_fee' => 'nullable|numeric|min:0',
            'delivery_methods' => 'nullable|array',
            'delivery_methods.*' => 'string',
            'open_time_monday' => 'nullable|string|max:5',
            'close_time_monday' => 'nullable|string|max:5',
            'open_time_tuesday' => 'nullable|string|max:5',
            'close_time_tuesday' => 'nullable|string|max:5',
            'open_time_wednesday' => 'nullable|string|max:5',
            'close_time_wednesday' => 'nullable|string|max:5',
            'open_time_thursday' => 'nullable|string|max:5',
            'close_time_thursday' => 'nullable|string|max:5',
            'open_time_friday' => 'nullable|string|max:5',
            'close_time_friday' => 'nullable|string|max:5',
            'open_time_saturday' => 'nullable|string|max:5',
            'close_time_saturday' => 'nullable|string|max:5',
            'open_time_sunday' => 'nullable|string|max:5',
            'close_time_sunday' => 'nullable|string|max:5',
            'rental_period' => 'nullable|integer|min:1|max:365',
            'rental_price' => 'nullable|numeric|min:0',
            'late_fee' => 'nullable|numeric|min:0',
            'security_deposit' => 'nullable|numeric|min:0',
            'max_books' => 'nullable|integer|min:1|max:1000',
            'discount_percent' => 'nullable|integer|min:0|max:100',
            'payment_methods' => 'nullable|array',
            'payment_methods.*' => 'string',
            'agree_terms' => 'accepted',
        ]);

        $deliveryMethods = $request->input('delivery_methods', []);
        if (!is_array($deliveryMethods)) {
            $deliveryMethods = [$deliveryMethods];
        }

        $paymentMethods = $request->input('payment_methods', []);
        if (!is_array($paymentMethods)) {
            $paymentMethods = [$paymentMethods];
        }

        $payload = array_merge($validated, [
            'offers_rental' => $request->boolean('offers_rental'),
            'offers_sale' => $request->boolean('offers_sale'),
            'offers_delivery' => $request->boolean('offers_delivery'),
            'delivery_methods' => $deliveryMethods ?: null,
            'delivery_bike' => in_array('bike', $deliveryMethods, true),
            'delivery_car' => in_array('car', $deliveryMethods, true),
            'delivery_pickup' => in_array('pickup', $deliveryMethods, true),
            'open_monday' => $request->boolean('open_monday'),
            'closed_monday' => $request->boolean('closed_monday'),
            'open_tuesday' => $request->boolean('open_tuesday'),
            'closed_tuesday' => $request->boolean('closed_tuesday'),
            'open_wednesday' => $request->boolean('open_wednesday'),
            'closed_wednesday' => $request->boolean('closed_wednesday'),
            'open_thursday' => $request->boolean('open_thursday'),
            'closed_thursday' => $request->boolean('closed_thursday'),
            'open_friday' => $request->boolean('open_friday'),
            'closed_friday' => $request->boolean('closed_friday'),
            'open_saturday' => $request->boolean('open_saturday'),
            'closed_saturday' => $request->boolean('closed_saturday'),
            'open_sunday' => $request->boolean('open_sunday'),
            'closed_sunday' => $request->boolean('closed_sunday'),
            'payment_methods' => $paymentMethods ?: null,
            'agree_terms' => $request->boolean('agree_terms'),
        ]);

        StoreRegistration::create($payload);

        return redirect()
            ->route('store.registration.view')
            ->with('status', 'Store registration submitted.');
    }

    public function update(Request $request)
    {
        if (!Schema::hasTable('store_registrations')) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Store registration table is missing. Run migrations first.');
        }

        $validated = $request->validate([
            'store_name' => 'required|string|max:255',
            'owner_full_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'country_code' => 'nullable|string|max:10',
            'phone' => 'nullable|string|max:30',
            'store_type' => 'nullable|string|max:50',
            'city' => 'nullable|string|max:100',
            'subcity' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:255',
            'store_description' => 'nullable|string|max:2000',
            'delivery_radius' => 'nullable|integer|min:0',
            'delivery_fee' => 'nullable|numeric|min:0',
            'delivery_methods' => 'nullable|array',
            'delivery_methods.*' => 'string',
            'open_time_monday' => 'nullable|string|max:5',
            'close_time_monday' => 'nullable|string|max:5',
            'open_time_tuesday' => 'nullable|string|max:5',
            'close_time_tuesday' => 'nullable|string|max:5',
            'open_time_wednesday' => 'nullable|string|max:5',
            'close_time_wednesday' => 'nullable|string|max:5',
            'open_time_thursday' => 'nullable|string|max:5',
            'close_time_thursday' => 'nullable|string|max:5',
            'open_time_friday' => 'nullable|string|max:5',
            'close_time_friday' => 'nullable|string|max:5',
            'open_time_saturday' => 'nullable|string|max:5',
            'close_time_saturday' => 'nullable|string|max:5',
            'open_time_sunday' => 'nullable|string|max:5',
            'close_time_sunday' => 'nullable|string|max:5',
            'rental_period' => 'nullable|integer|min:1|max:365',
            'rental_price' => 'nullable|numeric|min:0',
            'late_fee' => 'nullable|numeric|min:0',
            'security_deposit' => 'nullable|numeric|min:0',
            'max_books' => 'nullable|integer|min:1|max:1000',
            'discount_percent' => 'nullable|integer|min:0|max:100',
            'payment_methods' => 'nullable|array',
            'payment_methods.*' => 'string',
            'agree_terms' => 'accepted',
        ]);

        $deliveryMethods = $request->input('delivery_methods', []);
        if (!is_array($deliveryMethods)) {
            $deliveryMethods = [$deliveryMethods];
        }

        $paymentMethods = $request->input('payment_methods', []);
        if (!is_array($paymentMethods)) {
            $paymentMethods = [$paymentMethods];
        }

        $payload = array_merge($validated, [
            'offers_rental' => $request->boolean('offers_rental'),
            'offers_sale' => $request->boolean('offers_sale'),
            'offers_delivery' => $request->boolean('offers_delivery'),
            'delivery_methods' => $deliveryMethods ?: null,
            'delivery_bike' => in_array('bike', $deliveryMethods, true),
            'delivery_car' => in_array('car', $deliveryMethods, true),
            'delivery_pickup' => in_array('pickup', $deliveryMethods, true),
            'open_monday' => $request->boolean('open_monday'),
            'closed_monday' => $request->boolean('closed_monday'),
            'open_tuesday' => $request->boolean('open_tuesday'),
            'closed_tuesday' => $request->boolean('closed_tuesday'),
            'open_wednesday' => $request->boolean('open_wednesday'),
            'closed_wednesday' => $request->boolean('closed_wednesday'),
            'open_thursday' => $request->boolean('open_thursday'),
            'closed_thursday' => $request->boolean('closed_thursday'),
            'open_friday' => $request->boolean('open_friday'),
            'closed_friday' => $request->boolean('closed_friday'),
            'open_saturday' => $request->boolean('open_saturday'),
            'closed_saturday' => $request->boolean('closed_saturday'),
            'open_sunday' => $request->boolean('open_sunday'),
            'closed_sunday' => $request->boolean('closed_sunday'),
            'payment_methods' => $paymentMethods ?: null,
            'agree_terms' => $request->boolean('agree_terms'),
        ]);

        $registration = null;
        if ($request->filled('store_id')) {
            $registration = StoreRegistration::find($request->input('store_id'));
        }

        if (!$registration && $request->filled('email')) {
            $registration = StoreRegistration::where('email', $request->input('email'))
                ->latest('id')
                ->first();
        }

        if (!$registration) {
            $registration = StoreRegistration::latest('id')->first();
        }

        if ($registration) {
            $registration->update($payload);
        } else {
            StoreRegistration::create($payload);
        }

        return redirect()
            ->route('store.registration.view')
            ->with('status', 'Store registration updated.');
    }

    public function destroy(Request $request, $storeId)
    {
        if (!Schema::hasTable('store_registrations')) {
            return redirect()
                ->route('store.registration.view')
                ->with('error', 'Store registration table is missing. Run migrations first.');
        }

        $store = StoreRegistration::find($storeId);

        if (!$store) {
            return redirect()
                ->route('store.registration.view')
                ->with('error', 'Store not found.');
        }

        $store->delete();

        return redirect()
            ->route('store.registration.view')
            ->with('status', 'Store deleted successfully.');
    }
}
