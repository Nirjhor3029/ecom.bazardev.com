<?php

namespace App\Modules\Core\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Core\Models\FeatureFlag;
use App\Modules\Core\Models\Setting;
use App\Modules\Core\Services\FeatureFlagService;
use App\Modules\Core\Services\SettingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index(): JsonResponse
    {
        $this->authorize('settings.setting.view');

        return response()->json([
            'data' => [
                'settings' => Setting::query()->orderBy('group')->get(['key', 'value', 'group', 'is_public']),
                'features' => FeatureFlag::query()->orderBy('key')->get(['key', 'enabled', 'source']),
            ],
        ]);
    }

    public function updateSetting(Request $request, string $key, SettingService $settings): JsonResponse
    {
        $this->authorize('settings.setting.update');

        $request->validate(['value' => ['required'], 'group' => ['nullable', 'string'], 'is_public' => ['nullable', 'boolean']]);

        $existing = Setting::query()->find($key);

        $setting = $settings->set(
            $key,
            $request->input('value'),
            $request->input('group', $existing?->group ?? 'general'),
            $request->boolean('is_public', $existing?->is_public ?? false),
        );

        return response()->json(['data' => $setting]);
    }

    public function updateFeature(Request $request, string $key, FeatureFlagService $features): JsonResponse
    {
        $this->authorize('settings.setting.update');

        $request->validate(['enabled' => ['required', 'boolean']]);

        $flag = $features->set($key, $request->boolean('enabled'), 'admin');

        return response()->json(['data' => $flag]);
    }
}
