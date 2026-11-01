<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\Report;
use App\Models\ReportReview;
use App\Models\ReportFollowUp;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReportSeeder extends Seeder
{
    public function run(): void
    {
        $reporter = User::where('email', 'andi@karhutla.test')->firstOrFail();
        $reviewer = User::where('email', 'sari@karhutla.test')->firstOrFail();
        $coordinator = User::where('email', 'budi@karhutla.test')->firstOrFail();

        $katingan = Area::where('name', 'Kabupaten Katingan')->firstOrFail();
        $kapuas = Area::where('name', 'Kabupaten Kapuas')->firstOrFail();
        $palangkaRaya = Area::where('name', 'Kota Palangka Raya')->firstOrFail();
        $kotim = Area::where('name', 'Kabupaten Kotawaringin Timur')->firstOrFail();
        $pulangPisau = Area::where('name', 'Kabupaten Pulang Pisau')->firstOrFail();

        $reports = [
            [
                'title' => 'Asap Terlihat di Area Perkebunan',
                'area_id' => $katingan->id,
                'description' => 'Terlihat asap tipis dari area perkebunan. Belum diketahui sumber asap tersebut.',
                'days_ago' => 1,
                'hour' => 8,
                'minute' => 15,
                'address' => 'Area perkebunan Kabupaten Katingan',
                'latitude' => -1.9000000,
                'longitude' => 113.3000000,
                'condition_level' => 'medium',
                'status' => 'submitted',
            ],
            [
                'title' => 'Dugaan Pembakaran Lahan Terbuka',
                'area_id' => $kapuas->id,
                'description' => 'Terlihat asap cukup tebal dari lahan terbuka. Kondisi sekitar perlu diperiksa lebih lanjut.',
                'days_ago' => 2,
                'hour' => 14,
                'minute' => 30,
                'address' => 'Area lahan terbuka Kabupaten Kapuas',
                'latitude' => -2.0000000,
                'longitude' => 114.4000000,
                'condition_level' => 'high',
                'status' => 'submitted',
            ],
            [
                'title' => 'Asap di Sekitar Lahan Pertanian',
                'area_id' => $palangkaRaya->id,
                'description' => 'Terlihat asap dalam jumlah kecil di sekitar lahan pertanian.',
                'days_ago' => 3,
                'hour' => 10,
                'minute' => 0,
                'address' => 'Area pertanian Kota Palangka Raya',
                'latitude' => -2.2100000,
                'longitude' => 113.9200000,
                'condition_level' => 'low',
                'status' => 'reviewed',
                'review_result' => 'valid',
                'review_notes' => 'Informasi laporan sudah cukup jelas dan lokasi dapat dikenali.',
            ],
            [
                'title' => 'Asap Tebal Dekat Kawasan Hutan',
                'area_id' => $kotim->id,
                'description' => 'Pelapor melihat asap tebal di sekitar kawasan hutan. Diperlukan perhatian lebih lanjut.',
                'days_ago' => 4,
                'hour' => 13,
                'minute' => 45,
                'address' => 'Area sekitar kawasan hutan Kabupaten Kotawaringin Timur',
                'latitude' => -2.5300000,
                'longitude' => 112.9500000,
                'condition_level' => 'high',
                'status' => 'needs_attention',
                'review_result' => 'needs_attention',
                'review_notes' => 'Kondisi yang dilaporkan memerlukan perhatian lebih lanjut.',
            ],
            [
                'title' => 'Indikasi Asap di Lahan Kosong',
                'area_id' => $pulangPisau->id,
                'description' => 'Terlihat asap dari lahan kosong. Informasi telah diteruskan kepada pihak terkait.',
                'days_ago' => 5,
                'hour' => 9,
                'minute' => 20,
                'address' => 'Area lahan kosong Kabupaten Pulang Pisau',
                'latitude' => -2.7500000,
                'longitude' => 114.0000000,
                'condition_level' => 'high',
                'status' => 'forwarded',
                'review_result' => 'needs_attention',
                'review_notes' => 'Laporan membutuhkan tindak lanjut dari pihak terkait.',
                'destination' => 'BPBD setempat',
                'follow_up_notes' => 'Informasi indikasi kejadian telah diteruskan untuk ditindaklanjuti.',
            ],
            [
                'title' => 'Asap Tipis di Area Perkebunan Lain',
                'area_id' => $katingan->id,
                'description' => 'Terlihat asap tipis di area perkebunan. Kondisi masih perlu dipantau.',
                'days_ago' => 6,
                'hour' => 7,
                'minute' => 40,
                'address' => 'Area perkebunan lainnya di Kabupaten Katingan',
                'latitude' => -1.7000000,
                'longitude' => 113.4000000,
                'condition_level' => 'low',
                'status' => 'submitted',
            ],
        ];

        foreach ($reports as $data) {
            $report = Report::updateOrCreate(
                [
                    'user_id' => $reporter->id,
                    'title' => $data['title'],
                ],
                [
                    'area_id' => $data['area_id'],
                    'description' => $data['description'],
                    'incident_at' => now()->subDays($data['days_ago'])
                        ->setTime($data['hour'], $data['minute']),
                    'address' => $data['address'],
                    'latitude' => $data['latitude'],
                    'longitude' => $data['longitude'],
                    'condition_level' => $data['condition_level'],
                    'status' => $data['status'],
                    'evidence_path' => null,
                ]
            );

            if (isset($data['review_result'])) {
                ReportReview::updateOrCreate(
                    ['report_id' => $report->id],
                    [
                        'reviewer_id' => $reviewer->id,
                        'notes' => $data['review_notes'],
                        'review_result' => $data['review_result'],
                    ]
                );
            }

            if (isset($data['destination'])) {
                ReportFollowUp::updateOrCreate(
                    ['report_id' => $report->id],
                    [
                        'coordinator_id' => $coordinator->id,
                        'destination' => $data['destination'],
                        'notes' => $data['follow_up_notes'],
                        'forwarded_at' => now()->subDays(4),
                    ]
                );
            }
        }
    }
}