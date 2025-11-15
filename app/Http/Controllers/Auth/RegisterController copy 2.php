<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Tenant;
use App\Models\Outlet;
use Illuminate\Support\Facades\DB;


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
        // VALIDASI INPUT
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
            // 1. SIMPAN TENANT
            // ==========================
            $tenant = Tenant::create([
                'name'       => $request->tenant_name,
                'owner_name' => $request->owner_name,
                'email'      => $request->email,
                'phone'      => $request->phone,
                'package'    => $request->package,
            ]);

            // ==========================
            // 2. SIMPAN USER UTAMA
            // ==========================
            $user = User::create([
                'name'      => $request->owner_name,
                'email'     => $request->email,
                'password'  => Hash::make($request->password),
                'tenant_id' => $tenant->id,
            ]);

            // Assign Role via Spatie (role HARUS sudah dibuat)
            $user->assignRole('Owner');

            // ==========================
            // 3. SIMPAN OUTLET PERTAMA
            // ==========================
            Outlet::create([
                'tenant_id'      => $tenant->id,
                'outlet_name'    => $request->outlet_name,
                'outlet_address' => $request->outlet_address,
                'city'           => $request->city,
                'timezone'       => $request->timezone,
            ]);

            DB::commit();

            return response()->json([
                'status'   => 'ok',
                'message'  => 'Pendaftaran tenant berhasil! Silakan login.',
                'redirect' => route('login')
            ]);

        } catch (\Exception $e) {

            DB::rollBack();
            return response()->json([
                'status'  => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }
}
