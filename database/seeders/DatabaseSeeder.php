<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\AdminEmail;
use App\Models\Category;
use App\Models\Product;
use App\Models\VoucherItem;
use App\Models\ShippingOption;
use App\Models\PaymentMethod;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Review;
use App\Models\Message;
use App\Models\Notification;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Admin Account
        $admin = User::create([
            'name' => 'Admin AstridMart',
            'email' => 'admin@astridmart.com',
            'password' => Hash::make('password'),
            'phone_number' => '081234567890',
        ]);

        AdminEmail::create([
            'email' => 'admin@astridmart.com',
            'note' => 'Main Admin Account',
        ]);

        // 2. Create User Accounts (Omitted to start with a fresh store with only 1 admin)

        // 3. Create Categories
        $catFashion = Category::create([
            'name' => 'Fashion',
            'slug' => 'fashion',
            'icon' => 'checkroom',
        ]);

        $catAksesoris = Category::create([
            'name' => 'Aksesoris',
            'slug' => 'aksesoris',
            'icon' => 'watch',
        ]);

        $catMakanan = Category::create([
            'name' => 'Makanan',
            'slug' => 'makanan',
            'icon' => 'restaurant',
        ]);

        $catVoucher = Category::create([
            'name' => 'Voucher',
            'slug' => 'voucher',
            'icon' => 'confirmation_number',
        ]);

        // 4. Create Shipping Options
        $shipAmbil = ShippingOption::create(['name' => 'Ambil Sendiri', 'fee_type' => 'fixed', 'fee_value' => 0]);
        $shipAntar = ShippingOption::create(['name' => 'Antar Barang (Lokal)', 'fee_type' => 'fixed', 'fee_value' => 10000]);
        $shipReguler = ShippingOption::create(['name' => 'Kurir Reguler', 'fee_type' => 'fixed', 'fee_value' => 15000]);
        $shipInstan = ShippingOption::create(['name' => 'Kurir Instan', 'fee_type' => 'fixed', 'fee_value' => 30000]);

        // 5. Create Payment Methods
        $payBank = PaymentMethod::create(['name' => 'Transfer Bank']);
        $payQris = PaymentMethod::create(['name' => 'QRIS']);
        $payDana = PaymentMethod::create(['name' => 'DANA']);
        $payOvo = PaymentMethod::create(['name' => 'OVO']);
        $payShopee = PaymentMethod::create(['name' => 'ShopeePay']);
        $payLain = PaymentMethod::create(['name' => 'Lainnya']);

        // 6. Create Products
        // FASHION
        $p1 = Product::create([
            'category_id' => $catFashion->id,
            'name' => 'Kaos Oversize AstridMart',
            'description' => 'Kaos oversize bahan katun combed 24s premium yang adem dan nyaman digunakan sehari-hari. Desain minimalis elegan dengan logo AstridMart.',
            'price' => 85000,
            'stock' => 50,
            'rating' => 4.50,
            'review_count' => 5,
            'image' => 'fashion1.png',
        ]);

        $p2 = Product::create([
            'category_id' => $catFashion->id,
            'name' => 'Hoodie Polos Green Accent',
            'description' => 'Hoodie hangat fleece tebal dengan warna hijau botol premium khas AstridMart. Dilengkapi tali pengatur dan saku depan yang luas.',
            'price' => 150000,
            'stock' => 30,
            'rating' => 4.80,
            'review_count' => 4,
            'image' => 'fashion2.png',
        ]);

        $p3 = Product::create([
            'category_id' => $catFashion->id,
            'name' => 'Celana Cargo Modern',
            'description' => 'Celana panjang cargo kasual bahan ripstop premium. Banyak saku fungsional untuk membawa barang kecil Anda saat bepergian.',
            'price' => 125000,
            'stock' => 20,
            'rating' => 4.30,
            'review_count' => 3,
            'image' => 'fashion3.png',
        ]);

        $p4 = Product::create([
            'category_id' => $catFashion->id,
            'name' => 'Dress Casual Floral',
            'description' => 'Dress kasual motif bunga-bunga cantik berbahan rayon premium yang jatuh dan adem saat dipakai. Cocok untuk acara santai.',
            'price' => 175000,
            'stock' => 15,
            'rating' => 4.70,
            'review_count' => 3,
            'image' => 'fashion4.png',
        ]);

        $p5 = Product::create([
            'category_id' => $catFashion->id,
            'name' => 'Kemeja Flannel Slim',
            'description' => 'Kemeja flannel lengan panjang bermotif kotak-kotak klasik dengan potongan slim fit. Bahan semi-wool yang bertekstur nyaman.',
            'price' => 140000,
            'stock' => 25,
            'rating' => 4.60,
            'review_count' => 3,
            'image' => 'fashion5.png',
        ]);

        // AKSESORIS
        $p6 = Product::create([
            'category_id' => $catAksesoris->id,
            'name' => 'Jam Tangan Sporty Astrid',
            'description' => 'Jam tangan sporty digital dengan ketahanan air (water resistant) 5 ATM. Dilengkapi fitur alarm, stopwatch, dan lampu LED malam.',
            'price' => 250000,
            'stock' => 15,
            'rating' => 4.60,
            'review_count' => 4,
            'image' => 'aksesoris1.png',
        ]);

        $p7 = Product::create([
            'category_id' => $catAksesoris->id,
            'name' => 'Tas Selempang Canvas',
            'description' => 'Tas selempang (sling bag) berbahan canvas tebal berkualitas tinggi. Ringan dan kuat, muat tablet hingga 10 inci.',
            'price' => 95000,
            'stock' => 40,
            'rating' => 4.40,
            'review_count' => 3,
            'image' => 'aksesoris2.png',
        ]);

        $p8 = Product::create([
            'category_id' => $catAksesoris->id,
            'name' => 'Kalung Titanium Silver',
            'description' => 'Kalung rantai titanium anti karat dan tidak gatal di kulit. Desain silinder simpel yang trendi cocok untuk pria maupun wanita.',
            'price' => 45000,
            'stock' => 60,
            'rating' => 4.80,
            'review_count' => 3,
            'image' => 'aksesoris3.png',
        ]);

        $p9 = Product::create([
            'category_id' => $catAksesoris->id,
            'name' => 'Gelang Kulit Vintage',
            'description' => 'Gelang lilit berbahan kulit asli (genuine leather) dengan ornamen perunggu kuno. Memberi kesan vintage maskulin.',
            'price' => 30000,
            'stock' => 80,
            'rating' => 4.20,
            'review_count' => 3,
            'image' => 'aksesoris4.png',
        ]);

        $p10 = Product::create([
            'category_id' => $catAksesoris->id,
            'name' => 'Topi Bucket Unisex',
            'description' => 'Topi bucket hat bolak-balik (reversible) warna hitam dan hijau daun. Melindungi kepala dari terik matahari dengan gaya kasual.',
            'price' => 35000,
            'stock' => 50,
            'rating' => 4.50,
            'review_count' => 3,
            'image' => 'aksesoris5.png',
        ]);

        // MAKANAN
        $p11 = Product::create([
            'category_id' => $catMakanan->id,
            'name' => 'Keripik Pedas Daun Jeruk',
            'description' => 'Keripik singkong renyah dengan bumbu cabai rawit pedas meledak dan taburan daun jeruk yang segar wangi. Dijamin bikin nagih!',
            'price' => 15000,
            'stock' => 100,
            'rating' => 4.90,
            'review_count' => 5,
            'image' => 'makanan1.png',
        ]);

        $p12 = Product::create([
            'category_id' => $catMakanan->id,
            'name' => 'Kacang Almond Oven Madu',
            'description' => 'Kacang almond kupas panggang utuh dilapisi madu murni alami. Renyah, gurih, manis alami, dan kaya akan nutrisi sehat.',
            'price' => 45000,
            'stock' => 40,
            'rating' => 4.70,
            'review_count' => 3,
            'image' => 'makanan2.png',
        ]);

        $p13 = Product::create([
            'category_id' => $catMakanan->id,
            'name' => 'Snack Mix Manis Renyah',
            'description' => 'Campuran aneka camilan krispi manis seperti biskuit cokelat mini, sereal gandum, dan wafer karamel yang lezat untuk teman ngopi.',
            'price' => 12000,
            'stock' => 150,
            'rating' => 4.30,
            'review_count' => 3,
            'image' => 'makanan3.png',
        ]);

        $p14 = Product::create([
            'category_id' => $catMakanan->id,
            'name' => 'Minuman Botol Herba Segar',
            'description' => 'Minuman sari temulawak dan madu tradisional segar dalam botol kemasan praktis. Menjaga daya tahan tubuh dan menyegarkan dahaga.',
            'price' => 8000,
            'stock' => 200,
            'rating' => 4.60,
            'review_count' => 3,
            'image' => 'makanan4.png',
        ]);

        $p15 = Product::create([
            'category_id' => $catMakanan->id,
            'name' => 'Camilan Tradisional Emping',
            'description' => 'Emping melinjo rasa manis pedas gurih. Digoreng bersih higienis dan dikemas rapi tanpa bahan pengawet buatan.',
            'price' => 20000,
            'stock' => 80,
            'rating' => 4.50,
            'review_count' => 3,
            'image' => 'makanan5.png',
        ]);

        // VOUCHER
        $p16 = Product::create([
            'category_id' => $catVoucher->id,
            'name' => 'Voucher Diskon Kopi Kenangan',
            'description' => 'Voucher digital senilai Rp 20.000 untuk pembelian semua jenis minuman di seluruh outlet Kopi Kenangan Indonesia. Berlaku 30 hari.',
            'price' => 15000,
            'stock' => 100,
            'rating' => 4.90,
            'review_count' => 5,
            'image' => 'voucher1.png',
        ]);

        VoucherItem::create([
            'product_id' => $p16->id,
            'voucher_type' => 'discount_fixed',
            'voucher_label' => 'Voucher Rp 20.000 OFF',
        ]);

        $p17 = Product::create([
            'category_id' => $catVoucher->id,
            'name' => 'Voucher Diskon Bioskop XXI',
            'description' => 'Voucher diskon senilai Rp 50.000 untuk pembelian tiket nonton bioskop Cinema XXI/21 di aplikasi resmi M-Tix. Hemat dan seru!',
            'price' => 40000,
            'stock' => 100,
            'rating' => 4.80,
            'review_count' => 4,
            'image' => 'voucher2.png',
        ]);

        VoucherItem::create([
            'product_id' => $p17->id,
            'voucher_type' => 'discount_fixed',
            'voucher_label' => 'Voucher Rp 50.000 OFF',
        ]);


    }
}
