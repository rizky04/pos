<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

// Models
use App\Models\User;
use App\Models\Tenant;
use App\Models\Outlet;
use App\Models\Setting;

class RegisterController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function process(Request $request)
    {
        // ==========================
        // VALIDASI
        // ==========================
        $validator = Validator::make($request->all(), [
            'tenant_name'    => 'required|string|max:255',
            'owner_name'     => 'required|string|max:255',
            'email'          => 'required|email|unique:tenants,email|unique:users,email',
            'phone'          => 'required|string|max:20',
            'password'       => 'required|min:6',
            'package'        => 'required|string',

            'outlet_name'    => 'required|string|max:255',
            'outlet_address' => 'required|string|max:255',
            'city'           => 'required|string|max:255',
            'timezone'       => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => $validator->errors()->first()
            ]);
        }

        DB::beginTransaction();

        try {
            // ==========================
            // 1. SIMPAN TENANT (UUID)
            // ==========================
            $tenant = Tenant::create([
                'name'       => $request->tenant_name,
                'owner_name' => $request->owner_name,
                'email'      => $request->email,
                'phone'      => $request->phone,
                'package'    => $request->package,
            ]);

            // ==========================
            // 2. SIMPAN USER OWNER (UUID)
            // ==========================
            $user = User::create([
                'tenant_id' => $tenant->id, // UUID
                'name'      => $request->owner_name,
                'email'     => $request->email,
                'password'  => Hash::make($request->password),
            ]);



            // Spatie Role
            $user->assignRole('Owner');

            // ==========================
            // 3. SIMPAN OUTLET PERTAMA (UUID)
            // ==========================
            $outlet = Outlet::create([
                'tenant_id'      => $tenant->id,
                'outlet_name'    => $request->outlet_name,
                'outlet_address' => $request->outlet_address,
                'city'           => $request->city,
                'timezone'       => $request->timezone,
            ]);

            // ==========================
            // 4. SIMPAN SETTING DEFAULT (UUID)
            // ==========================
            Setting::create([
                'tenant_id'       => $tenant->id,
                'default_tax'     => 0,
                'prefix_sale'     => 'SL',
                'prefix_purchase' => 'PR',
            ]);

            DB::commit();

            return response()->json([
                'status'   => 'ok',
                'message'  => 'Registrasi berhasil! Silakan login.',
                'redirect' => route('login')
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'status'  => 'error',
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }
}
