<?php

namespace Database\Seeders;

use App\Models\Question;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    // --- POPULATE QUESTION BANK ---
    public function run(): void
    {
        // 1. We're loading up 20 questions total (5 for each category)
        // to make sure our test logic has enough data to work with.
        $dummyQuestions = [

            // --- VERBAL: Testing vocab and logic patterns ---
            ['category' => 'Verbal', 'content' => 'ABREVIASI = ...', 'option_a' => 'Pengurangan', 'option_b' => 'Pemendekan', 'option_c' => 'Rangkuman', 'option_d' => 'Penggalan', 'correct_answer' => 'B'],
            ['category' => 'Verbal', 'content' => 'SINONIM dari KONTRAKSI adalah...', 'option_a' => 'Pemuaian', 'option_b' => 'Penciutan', 'option_c' => 'Penyempitan', 'option_d' => 'Penyusutan', 'correct_answer' => 'C'],
            ['category' => 'Verbal', 'content' => 'ANTONIM dari EKSPANSI adalah...', 'option_a' => 'Perluasan', 'option_b' => 'Penyempitan', 'option_c' => 'Penyebaran', 'option_d' => 'Peninggian', 'correct_answer' => 'B'],
            ['category' => 'Verbal', 'content' => 'PREMISE memiliki arti yang sama dengan...', 'option_a' => 'Dasar pemikiran', 'option_b' => 'Kesimpulan', 'option_c' => 'Hasil', 'option_d' => 'Tujuan', 'correct_answer' => 'A'],
            ['category' => 'Verbal', 'content' => 'Hubungan kata: MOBIL - BENSIN = MANUSIA - ...', 'option_a' => 'Pakaian', 'option_b' => 'Makanan', 'option_c' => 'Udara', 'option_d' => 'Minum', 'correct_answer' => 'B'],

            // --- NUMERIK: Basic math and sequence puzzles ---
            ['category' => 'Numerik', 'content' => 'Jika 3x + 5 = 20, berapakah nilai dari 6x - 2?', 'option_a' => '28', 'option_b' => '30', 'option_c' => '32', 'option_d' => '34', 'correct_answer' => 'A'],
            ['category' => 'Numerik', 'content' => 'Kelanjutan deret: 2, 4, 8, 16, ... adalah...', 'option_a' => '24', 'option_b' => '32', 'option_c' => '48', 'option_d' => '64', 'correct_answer' => 'B'],
            ['category' => 'Numerik', 'content' => 'Nilai dari 15% dari 200 adalah...', 'option_a' => '20', 'option_b' => '25', 'option_c' => '30', 'option_d' => '35', 'correct_answer' => 'C'],
            ['category' => 'Numerik', 'content' => 'Deret: 1, 3, 6, 10, 15, ...', 'option_a' => '18', 'option_b' => '20', 'option_c' => '21', 'option_d' => '22', 'correct_answer' => 'C'],
            ['category' => 'Numerik', 'content' => 'Jika x=2 dan y=3, maka (x^2 + y) = ...', 'option_a' => '5', 'option_b' => '6', 'option_c' => '7', 'option_d' => '8', 'correct_answer' => 'C'],

            // --- LOGIKA: Testing deductive reasoning ---
            ['category' => 'Logika', 'content' => 'Semua dosen adalah ilmuwan. Sebagian ilmuwan adalah penulis. Kesimpulan?', 'option_a' => 'Semua dosen penulis', 'option_b' => 'Sebagian dosen penulis', 'option_c' => 'Sebagian ilmuwan bukan dosen', 'option_d' => 'Tidak dapat disimpulkan', 'correct_answer' => 'D'],
            ['category' => 'Logika', 'content' => 'Semua mamalia menyusui. Paus adalah mamalia. Kesimpulan?', 'option_a' => 'Paus menyusui', 'option_b' => 'Paus bukan mamalia', 'option_c' => 'Semua yang menyusui paus', 'option_d' => 'Paus bertelur', 'correct_answer' => 'A'],
            ['category' => 'Logika', 'content' => 'Jika hujan, maka jalanan basah. Jalanan tidak basah. Kesimpulan?', 'option_a' => 'Hujan turun', 'option_b' => 'Hujan tidak turun', 'option_c' => 'Jalanan banjir', 'option_d' => 'Tidak ada kesimpulan', 'correct_answer' => 'B'],
            ['category' => 'Logika', 'content' => 'Semua buku adalah jendela ilmu. Novel adalah buku. Kesimpulan?', 'option_a' => 'Novel bukan jendela ilmu', 'option_b' => 'Novel adalah jendela ilmu', 'option_c' => 'Jendela ilmu adalah novel', 'option_d' => 'Buku bukan novel', 'correct_answer' => 'B'],
            ['category' => 'Logika', 'content' => 'Sebagian karyawan rajin. Semua yang rajin sukses. Kesimpulan?', 'option_a' => 'Semua karyawan sukses', 'option_b' => 'Sebagian karyawan sukses', 'option_c' => 'Semua yang sukses karyawan', 'option_d' => 'Sebagian karyawan tidak sukses', 'correct_answer' => 'B'],

            // --- SPASIAL: Visualization and shape logic ---
            ['category' => 'Spasial', 'content' => 'Hasil rotasi 180 derajat segitiga siku-siku hadap kanan atas?', 'option_a' => 'Menghadap kiri bawah', 'option_b' => 'Menghadap kanan bawah', 'option_c' => 'Menghadap kiri atas', 'option_d' => 'Tetap kanan atas', 'correct_answer' => 'A'],
            ['category' => 'Spasial', 'content' => 'Manakah yang merupakan jaring-jaring kubus?', 'option_a' => 'Bentuk L', 'option_b' => 'Bentuk T', 'option_c' => 'Bentuk O', 'option_d' => 'Bentuk I', 'correct_answer' => 'B'],
            ['category' => 'Spasial', 'content' => 'Jika huruf "b" dicerminkan horizontal, menjadi...', 'option_a' => 'd', 'option_b' => 'q', 'option_c' => 'p', 'option_d' => 'b', 'correct_answer' => 'A'],
            ['category' => 'Spasial', 'content' => 'Berapa jumlah sisi pada kubus?', 'option_a' => '4', 'option_b' => '6', 'option_c' => '8', 'option_d' => '12', 'correct_answer' => 'B'],
            ['category' => 'Spasial', 'content' => 'Bentuk 2D dari tabung jika dibuka adalah...', 'option_a' => 'Segitiga', 'option_b' => 'Persegi', 'option_c' => 'Persegi panjang & lingkaran', 'option_d' => 'Trapesium', 'correct_answer' => 'C'],
        ];

        // 2. Loop through our list and save them one by one.
        foreach ($dummyQuestions as $q) {
            Question::create($q);
        }
    }
}
