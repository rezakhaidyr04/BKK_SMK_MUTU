<?php

namespace App\Http\Controllers;

use App\Support\IndonesiaRegions;
use Illuminate\Http\Request;

/**
 * Dependent dropdown province → city untuk form/filter lowongan.
 * Publik, read-only, data dari config (tanpa query DB).
 */
class WilayahController extends Controller
{
    public function districts(Request $request)
    {
        $province = (string) $request->query('province', '');
        $city = (string) $request->query('city', '');

        if ($province === '' || $city === '' || ! IndonesiaRegions::isValidProvince($province)
            || ! IndonesiaRegions::isValidCity($province, $city)) {
            return response()->json([]);
        }

        return response()->json(array_values(IndonesiaRegions::districtsFor($province, $city)));
    }

    public function cities(Request $request)
    {
        $province = (string) $request->query('province', '');

        if ($province === '' || ! IndonesiaRegions::isValidProvince($province)) {
            return response()->json([]);
        }

        return response()->json(array_values(IndonesiaRegions::citiesFor($province)));
    }
}
