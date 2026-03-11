<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\StoreHour;
use App\Models\StoreService;
use App\Models\StorePolicy;
use App\Models\StorePaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class StoreController extends Controller
{
    /**
     * Show the store registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        return view('store.registration');
    }

    /**
     * Handle the store registration process.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        // Validate the request data
        $validator = $this->validateRegistration($request->all());

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('registration_error', true);
        }

        try {
            DB::beginTransaction();

            // Create the store
            $store = $this->createStore($request);

            // Create store services
            $this->createStoreServices($store, $request);

            // Create store hours
            $this->createStoreHours($store, $request);

            // Create store policies
            $this->createStorePolicies($store, $request);

            // Create payment methods
            $this->createPaymentMethods($store, $request);

            DB::commit();

            // Log the successful registration
            Log::info('New store registered', ['store_id' => $store->id, 'store_name' => $store->name]);

            // Redirect to success page with store details
            return redirect()->route('store.registration.success', ['store' => $store->id])
                ->with('success', 'Your bookstore has been successfully registered!')
                ->with('store', $store);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Store registration failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'An error occurred during registration. Please try again. Error: ' . $e->getMessage());
        }
    }

    /**
     * Show registration success page.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function registrationSuccess($id)
    {
        $store = Store::findOrFail($id);
        return view('store.registration-success', compact('store'));
    }

    /**
     * Validate the registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validateRegistration(array $data)
    {
        return Validator::make($data, [
            // Store Information
            'store_name' => 'required|string|max:255|unique:stores,name',
            'owner_full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:stores,email',
            'country_code' => 'required|string|max:10',
            'phone' => 'required|string|max:20|unique:stores,phone',
            'store_type' => 'required|string|in:bookstore,library,coffee_shop,educational,online_only,home_based,religious,children',
            'city' => 'required|string|max:100',
            'subcity' => 'nullable|string|max:100',
            'address' => 'required|string|max:255',
            'store_description' => 'nullable|string|max:1000',

            // Services
            'offers_rental' => 'sometimes|boolean',
            'offers_sale' => 'sometimes|boolean',
            'offers_delivery' => 'sometimes|boolean',
            'delivery_radius' => 'nullable|numeric|min:1|max:50',
            'delivery_fee' => 'nullable|numeric|min:0',
            'delivery_methods' => 'nullable|array',

            // Business Hours
            'open_monday' => 'sometimes|boolean',
            'open_tuesday' => 'sometimes|boolean',
            'open_wednesday' => 'sometimes|boolean',
            'open_thursday' => 'sometimes|boolean',
            'open_friday' => 'sometimes|boolean',
            'open_saturday' => 'sometimes|boolean',
            'open_sunday' => 'sometimes|boolean',
            'closed_monday' => 'sometimes|boolean',
            'closed_tuesday' => 'sometimes|boolean',
            'closed_wednesday' => 'sometimes|boolean',
            'closed_thursday' => 'sometimes|boolean',
            'closed_friday' => 'sometimes|boolean',
            'closed_saturday' => 'sometimes|boolean',
            'closed_sunday' => 'sometimes|boolean',
            
            // Times
            'open_time_monday' => 'nullable|date_format:H:i',
            'close_time_monday' => 'nullable|date_format:H:i',
            'open_time_tuesday' => 'nullable|date_format:H:i',
            'close_time_tuesday' => 'nullable|date_format:H:i',
            'open_time_wednesday' => 'nullable|date_format:H:i',
            'close_time_wednesday' => 'nullable|date_format:H:i',
            'open_time_thursday' => 'nullable|date_format:H:i',
            'close_time_thursday' => 'nullable|date_format:H:i',
            'open_time_friday' => 'nullable|date_format:H:i',
            'close_time_friday' => 'nullable|date_format:H:i',
            'open_time_saturday' => 'nullable|date_format:H:i',
            'close_time_saturday' => 'nullable|date_format:H:i',
            'open_time_sunday' => 'nullable|date_format:H:i',
            'close_time_sunday' => 'nullable|date_format:H:i',

            // Policies
            'rental_period' => 'required|integer|min:1|max:30',
            'rental_price' => 'required|numeric|min:0',
            'late_fee' => 'required|numeric|min:0',
            'security_deposit' => 'required|numeric|min:0',
            'max_books' => 'nullable|integer|min:1',
            'discount_percent' => 'nullable|integer|min:0|max:100',

            // Payment Methods
            'payment_methods' => 'nullable|array',

            // Terms
            'agree_terms' => 'required|accepted',
        ], [
            'store_name.required' => 'Store name is required',
            'store_name.unique' => 'This store name is already taken',
            'owner_full_name.required' => 'Owner full name is required',
            'email.required' => 'Email address is required',
            'email.email' => 'Please enter a valid email address',
            'email.unique' => 'This email is already registered',
            'phone.required' => 'Phone number is required',
            'phone.unique' => 'This phone number is already registered',
            'city.required' => 'City is required',
            'address.required' => 'Street address is required',
            'store_type.required' => 'Store type is required',
            'agree_terms.accepted' => 'You must agree to the terms and conditions',
            'rental_period.required' => 'Rental period is required',
            'rental_price.required' => 'Rental price is required',
            'late_fee.required' => 'Late fee is required',
            'security_deposit.required' => 'Security deposit is required',
        ]);
    }

    /**
     * Create a new store.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Models\Store
     */
    protected function createStore(Request $request)
    {
        $store = new Store();
        $store->name = $request->store_name;
        $store->slug = Str::slug($request->store_name);
        $store->owner_name = $request->owner_full_name;
        $store->email = $request->email;
        $store->country_code = $request->country_code;
        $store->phone = $request->phone;
        $store->full_phone = $request->country_code . $request->phone;
        $store->store_type = $request->store_type;
        $store->city = $request->city;
        $store->subcity = $request->subcity;
        $store->address = $request->address;
        $store->description = $request->store_description;
        $store->status = 'pending'; // Requires admin approval
        $store->registration_date = now();
        
        // Generate a unique store code
        $store->store_code = $this->generateStoreCode();
        
        $store->save();

        return $store;
    }

    /**
     * Generate a unique store code.
     *
     * @return string
     */
    protected function generateStoreCode()
    {
        $prefix = 'BK';
        $timestamp = now()->format('ymd');
        $random = strtoupper(Str::random(4));
        
        $code = $prefix . $timestamp . $random;
        
        // Ensure uniqueness
        while (Store::where('store_code', $code)->exists()) {
            $random = strtoupper(Str::random(4));
            $code = $prefix . $timestamp . $random;
        }
        
        return $code;
    }

    /**
     * Create store services.
     *
     * @param  \App\Models\Store  $store
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function createStoreServices(Store $store, Request $request)
    {
        $service = new StoreService();
        $service->store_id = $store->id;
        $service->offers_rental = $request->has('offers_rental');
        $service->offers_sale = $request->has('offers_sale');
        $service->offers_delivery = $request->has('offers_delivery');
        $service->delivery_radius = $request->delivery_radius;
        $service->delivery_fee = $request->delivery_fee;
        
        // Store delivery methods as JSON if provided
        if ($request->has('delivery_methods')) {
            $service->delivery_methods = json_encode($request->delivery_methods);
        }
        
        $service->save();
    }

    /**
     * Create store business hours.
     *
     * @param  \App\Models\Store  $store
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function createStoreHours(Store $store, Request $request)
    {
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        
        foreach ($days as $day) {
            $isOpen = $request->has('open_' . $day) && $request->input('open_' . $day) == 'on';
            $isClosed = $request->has('closed_' . $day) && $request->input('closed_' . $day) == 'on';
            
            $openTime = $request->input('open_time_' . $day);
            $closeTime = $request->input('close_time_' . $day);
            
            // If closed, set times to null
            if ($isClosed) {
                $openTime = null;
                $closeTime = null;
                $isOpen = false;
            }
            
            $storeHour = new StoreHour();
            $storeHour->store_id = $store->id;
            $storeHour->day_of_week = $day;
            $storeHour->is_open = $isOpen;
            $storeHour->is_closed = $isClosed;
            $storeHour->open_time = $openTime;
            $storeHour->close_time = $closeTime;
            $storeHour->save();
        }
    }

    /**
     * Create store policies.
     *
     * @param  \App\Models\Store  $store
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function createStorePolicies(Store $store, Request $request)
    {
        $policy = new StorePolicy();
        $policy->store_id = $store->id;
        $policy->rental_period_days = $request->rental_period;
        $policy->rental_price_per_day = $request->rental_price;
        $policy->late_fee_per_day = $request->late_fee;
        $policy->security_deposit = $request->security_deposit;
        $policy->max_books_per_customer = $request->max_books ?? 5;
        $policy->student_discount_percent = $request->discount_percent ?? 0;
        $policy->save();
    }

    /**
     * Create store payment methods.
     *
     * @param  \App\Models\Store  $store
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function createPaymentMethods(Store $store, Request $request)
    {
        if ($request->has('payment_methods')) {
            foreach ($request->payment_methods as $method) {
                $paymentMethod = new StorePaymentMethod();
                $paymentMethod->store_id = $store->id;
                $paymentMethod->payment_method = $method;
                $paymentMethod->is_active = true;
                $paymentMethod->save();
            }
        } else {
            // Default to cash if no methods selected
            $paymentMethod = new StorePaymentMethod();
            $paymentMethod->store_id = $store->id;
            $paymentMethod->payment_method = 'cash';
            $paymentMethod->is_active = true;
            $paymentMethod->save();
        }
    }

    /**
     * Get store types for dropdown.
     *
     * @return array
     */
    public static function getStoreTypes()
    {
        return [
            'bookstore' => '📚 Traditional Book Store',
            'library' => '🏛️ Library & Reading Space',
            'coffee_shop' => '☕ Coffee Shop with Books',
            'educational' => '🎓 Educational & Textbook Store',
            'online_only' => '💻 Online Book Store',
            'home_based' => '🏠 Home-based Book Business',
            'religious' => '🕌 Religious Book Store',
            'children' => '🧸 Children\'s Book Store',
        ];
    }

    /**
     * Get Ethiopian cities for dropdown.
     *
     * @return array
     */
    public static function getEthiopianCities()
    {
        return [
            'addis_ababa' => 'Addis Ababa (አዲስ አበባ)',
            'dire_dawa' => 'Dire Dawa (ድሬ ዳዋ)',
            'bahir_dar' => 'Bahir Dar (ባሕር ዳር)',
            'mekele' => 'Mekele (መቀሌ)',
            'hawassa' => 'Hawassa (ሀዋሳ)',
            'gondar' => 'Gondar (ጎንደር)',
            'jimma' => 'Jimma (ጅማ)',
            'desse' => 'Dessie (ደሴ)',
            'harar' => 'Harar (ሐረር)',
            'adama' => 'Adama (አዳማ)',
            'jijiga' => 'Jijiga (ጅጅጋ)',
            'shashamane' => 'Shashamane (ሻሸመኔ)',
            'arba_minch' => 'Arba Minch (አርባ ምንጭ)',
            'other' => 'Other City',
        ];
    }
}