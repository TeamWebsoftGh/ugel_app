<?php

namespace Database\Seeders;

use App\Models\Payment\Bank;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BanksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $banks = [
            ['code' => '300302', 'name' => 'STANDARD CHARTERED BANK', 'short_name' => 'SCB'],
            ['code' => '300303', 'name' => 'ABSA BANK GHANA LIMITED', 'short_name' => 'ABSA'],
            ['code' => '300304', 'name' => 'GCB BANK LIMITED', 'short_name' => 'GCB'],
            ['code' => '300305', 'name' => 'NATIONAL INVESTMENT BANK', 'short_name' => 'NIB'],
            ['code' => '300307', 'name' => 'AGRICULTURAL DEVELOPMENT BANK', 'short_name' => 'ADB'],
            ['code' => '300309', 'name' => 'UNIVERSAL MERCHANT BANK', 'short_name' => 'UMB'],
            ['code' => '300310', 'name' => 'REPUBLIC BANK LIMITED', 'short_name' => 'RPB'],
            ['code' => '300311', 'name' => 'ZENITH BANK GHANA LTD', 'short_name' => 'ZNB'],
            ['code' => '300312', 'name' => 'ECOBANK GHANA LTD', 'short_name' => 'ECBK'],
            ['code' => '300313', 'name' => 'CAL BANK LIMITED', 'short_name' => 'CLB'],
            ['code' => '300316', 'name' => 'FIRST ATLANTIC BANK', 'short_name' => 'FAB'],
            ['code' => '300317', 'name' => 'PRUDENTIAL BANK LTD', 'short_name' => 'PBL'],
            ['code' => '300318', 'name' => 'STANBIC BANK', 'short_name' => 'STB'],
            ['code' => '300319', 'name' => 'FIRST BANK OF NIGERIA', 'short_name' => 'FBN'],
            ['code' => '300320', 'name' => 'BANK OF AFRICA', 'short_name' => 'BOA'],
            ['code' => '300322', 'name' => 'GUARANTY TRUST BANK', 'short_name' => 'GTB'],
            ['code' => '300323', 'name' => 'FIDELITY BANK LIMITED', 'short_name' => 'FBGL'],
            ['code' => '300324', 'name' => 'SAHEL - SAHARA BANK (BSIC)', 'short_name' => 'BSIC'],
            ['code' => '300325', 'name' => 'UNITED BANK OF AFRICA', 'short_name' => 'UBA'],
            ['code' => '300328', 'name' => 'BANK OF GHANA', 'short_name' => 'BOG'],
            ['code' => '300329', 'name' => 'ACCESS BANK LTD', 'short_name' => 'ACB'],
            ['code' => '300331', 'name' => 'CONSOLIDATED BANK GHANA', 'short_name' => 'CBG'],
            ['code' => '300334', 'name' => 'FIRST NATIONAL BANK', 'short_name' => 'FNB'],
            ['code' => '300361', 'name' => 'SERVICES INTEGRITY SAVINGS LOANS', 'short_name' => 'SISL'],
            ['code' => '300362', 'name' => 'GHL Bank', 'short_name' => 'GHLB'],
            ['code' => '300496', 'name' => 'DALEX FINANCE AND LEASING COMPANY', 'short_name' => 'DFLC'],
            ['code' => '300574', 'name' => 'G-MONEY', 'short_name' => 'GMONEY'],
            ['code' => '300591', 'name' => 'MTN MOBILE MONEY', 'short_name' => 'MTN'],
            ['code' => '300592', 'name' => 'AIRTELTIGO MONEY', 'short_name' => 'AIRTEL'],
            ['code' => '300594', 'name' => 'VODAFONE CASH', 'short_name' => 'VODAFONE'],
            ['code' => '300306', 'name' => 'ARB APEX BANK LIMITED', 'short_name' => 'ABL'],
            ['code' => '300308', 'name' => 'SOCIETE GENERALE GHANA', 'short_name' => 'SG'],
        ];

        foreach ($banks as $bank) {
            Bank::updateOrCreate(
                ['code' => $bank['code']],
                ['name' => $bank['name'], 'short_name' => $bank['short_name']],
            );
        }
    }
}
