<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Services\SettingsService;
use App\Services\StudentService;
use CodeIgniter\HTTP\ResponseInterface;

class Dashboard extends BaseController
{
    protected $helpers = ['url', 'html', 'form'];

    private ?SettingsService $settingsService = null;

    public function index(): ResponseInterface
    {
        $this->requireGroup([ROLE_ADMIN]);
        $shieldUser = auth()->user();

        $studentService = new StudentService();
        $settingsService = new SettingsService();

        $stats = $studentService->getStats();
        $recentStudents = $studentService->getRecentStudents(4);
        $monthlyTrends = $studentService->getMonthlyTrends();

        $trendValues = array_values($monthlyTrends);
        $trendPct = $this->computeTrendPercent($trendValues);

        return $this->response->setBody(view('admin/dashboard', [
            ...$this->getSiteSettings(),
            'admin_username'  => $shieldUser->username,
            'user_email'      => $shieldUser->email,
            'roleLabel'       => $shieldUser->inGroup(ROLE_ADMIN) ? 'Administrator' : 'Panitia SPMB',
            'isAdmin'         => $shieldUser->inGroup(ROLE_ADMIN),
            'recent_students' => $recentStudents,
            'stats'           => $stats,
            'monthly_trends'  => $monthlyTrends,
            'trend_pct'       => $trendPct,
            'is_open'         => $settingsService->get(SETTING_IS_OPEN, '1'),
            'tahun_ajaran'    => $settingsService->get(SETTING_TAHUN_AJARAN, '2026/2027'),
        ]));
    }

    private function computeTrendPercent(array $trendValues): array
    {
        $pct = ['total' => 0, 'valid' => 0, 'pending' => 0, 'invalid' => 0];

        $counts = array_column($trendValues, 'count');
        $last = (int) ($counts[count($counts) - 1] ?? 0);
        $prev = (int) ($counts[count($counts) - 2] ?? 0);

        if ($prev > 0) {
            $pct['total'] = round((($last - $prev) / $prev) * 100, 1);
        }

        return $pct;
    }
}
