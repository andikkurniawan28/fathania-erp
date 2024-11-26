<?php

namespace App\Http\Controllers;

use App\Models\Setup;
use App\Models\Account;
use App\Models\ActivityLog;
use App\Models\Currency;
use Illuminate\Http\Request;

class SetupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $setup = Setup::init();
        $setups = Setup::get()->last();
        $accounts = Account::all();
        $currencys = Currency::all();
        return view("setup.index", compact("setup", "setups", "accounts", "currencys"));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'currency_id' => 'required',
            'app_name' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'company_logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // max file size 2MB
            'retained_earning_id' => 'required',
            // 'daily_wage' => 'nullable',
            // 'hourly_wage' => 'nullable',
            // 'hourly_overtime' => 'nullable',
        ]);

        $setup = Setup::findOrFail($id);

        // Jika ada file gambar yang diunggah
        if ($request->hasFile('company_logo')) {
            // Simpan file gambar ke folder public/setups
            $image_name = time() . '.' . $request->company_logo->extension();
            $request->company_logo->move(public_path('setups'), $image_name);

            // Tambahkan nama file gambar ke dalam validated data
            $validated["company_logo"] = 'setups/' . $image_name;

            // Hapus file gambar lama jika ada
            if ($setup->company_logo && file_exists(public_path($setup->company_logo))) {
                @unlink(public_path($setup->company_logo));
            }
        }

        // Update data setup
        $setup->update($validated);

        // Catat aktivitas log
        ActivityLog::insert(["user_id" => Auth()->user()->id, "description" => "Setup was updated."]);

        // Redirect dengan pesan sukses
        return redirect()->back()->with("success", "Setup has been updated");
    }
}
