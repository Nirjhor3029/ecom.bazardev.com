<?php

namespace App\Modules\Core\Http\Middleware;

use App\Modules\Core\Services\FeatureFlagService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * FR-CFG-4: disabled feature -> 404 with error.code=FEATURE_DISABLED, not 403/empty-list.
 * Usage: Route::middleware('feature:delivery_slots')
 */
class EnsureFeatureEnabled
{
    public function __construct(protected FeatureFlagService $features) {}

    public function handle(Request $request, Closure $next, string $key): Response
    {
        if (! $this->features->enabled($key)) {
            return response()->json([
                'error' => [
                    'code' => 'FEATURE_DISABLED',
                    'message' => "The feature [{$key}] is not enabled for this store.",
                ],
            ], 404);
        }

        return $next($request);
    }
}
