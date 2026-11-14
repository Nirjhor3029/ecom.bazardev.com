<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Bangladesh administrative divisions (8) and districts (64) for the checkout address form.
 * Idempotent. Upazilas are left to free text for now (≈490 rows; not needed by shipping).
 */
class GeoSeeder extends Seeder
{
    public function run(): void
    {
        // division [en, bn] => districts [[en, bn], …]
        $data = [
            ['Dhaka', 'ঢাকা', [['Dhaka', 'ঢাকা'], ['Gazipur', 'গাজীপুর'], ['Narayanganj', 'নারায়ণগঞ্জ'], ['Narsingdi', 'নরসিংদী'], ['Manikganj', 'মানিকগঞ্জ'], ['Munshiganj', 'মুন্সিগঞ্জ'], ['Tangail', 'টাঙ্গাইল'], ['Kishoreganj', 'কিশোরগঞ্জ'], ['Faridpur', 'ফরিদপুর'], ['Gopalganj', 'গোপালগঞ্জ'], ['Madaripur', 'মাদারীপুর'], ['Rajbari', 'রাজবাড়ী'], ['Shariatpur', 'শরীয়তপুর']]],
            ['Chattogram', 'চট্টগ্রাম', [['Chattogram', 'চট্টগ্রাম'], ["Cox's Bazar", 'কক্সবাজার'], ['Cumilla', 'কুমিল্লা'], ['Feni', 'ফেনী'], ['Brahmanbaria', 'ব্রাহ্মণবাড়িয়া'], ['Rangamati', 'রাঙ্গামাটি'], ['Noakhali', 'নোয়াখালী'], ['Chandpur', 'চাঁদপুর'], ['Lakshmipur', 'লক্ষ্মীপুর'], ['Khagrachhari', 'খাগড়াছড়ি'], ['Bandarban', 'বান্দরবান']]],
            ['Rajshahi', 'রাজশাহী', [['Rajshahi', 'রাজশাহী'], ['Bogura', 'বগুড়া'], ['Pabna', 'পাবনা'], ['Sirajganj', 'সিরাজগঞ্জ'], ['Natore', 'নাটোর'], ['Joypurhat', 'জয়পুরহাট'], ['Chapainawabganj', 'চাঁপাইনবাবগঞ্জ'], ['Naogaon', 'নওগাঁ']]],
            ['Khulna', 'খুলনা', [['Khulna', 'খুলনা'], ['Bagerhat', 'বাগেরহাট'], ['Satkhira', 'সাতক্ষীরা'], ['Jashore', 'যশোর'], ['Narail', 'নড়াইল'], ['Magura', 'মাগুরা'], ['Chuadanga', 'চুয়াডাঙ্গা'], ['Kushtia', 'কুষ্টিয়া'], ['Meherpur', 'মেহেরপুর'], ['Jhenaidah', 'ঝিনাইদহ']]],
            ['Barishal', 'বরিশাল', [['Barishal', 'বরিশাল'], ['Bhola', 'ভোলা'], ['Patuakhali', 'পটুয়াখালী'], ['Pirojpur', 'পিরোজপুর'], ['Jhalokathi', 'ঝালকাঠি'], ['Barguna', 'বরগুনা']]],
            ['Sylhet', 'সিলেট', [['Sylhet', 'সিলেট'], ['Moulvibazar', 'মৌলভীবাজার'], ['Habiganj', 'হবিগঞ্জ'], ['Sunamganj', 'সুনামগঞ্জ']]],
            ['Rangpur', 'রংপুর', [['Rangpur', 'রংপুর'], ['Dinajpur', 'দিনাজপুর'], ['Kurigram', 'কুড়িগ্রাম'], ['Gaibandha', 'গাইবান্ধা'], ['Nilphamari', 'নীলফামারী'], ['Lalmonirhat', 'লালমনিরহাট'], ['Thakurgaon', 'ঠাকুরগাঁও'], ['Panchagarh', 'পঞ্চগড়']]],
            ['Mymensingh', 'ময়মনসিংহ', [['Mymensingh', 'ময়মনসিংহ'], ['Jamalpur', 'জামালপুর'], ['Netrokona', 'নেত্রকোনা'], ['Sherpur', 'শেরপুর']]],
        ];

        foreach ($data as [$en, $bn, $districts]) {
            $divisionId = DB::table('geo_divisions')->where('name', $en)->value('id')
                ?? DB::table('geo_divisions')->insertGetId(['name' => $en, 'bn_name' => $bn]);

            foreach ($districts as [$dEn, $dBn]) {
                if (! DB::table('geo_districts')->where('geo_division_id', $divisionId)->where('name', $dEn)->exists()) {
                    DB::table('geo_districts')->insert(['geo_division_id' => $divisionId, 'name' => $dEn, 'bn_name' => $dBn]);
                }
            }
        }
    }
}
