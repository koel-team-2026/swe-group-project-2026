<?php

namespace App\Http\Controllers\API\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Jackiedo\DotenvEditor\DotenvEditor;

class UpdateSearchSettingsController extends Controller
{
    public function __construct(
        private readonly DotenvEditor $dotenvEditor,
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $request->validate([
            'driver' => 'required|string|in:database,algolia,elasticsearch,meilisearch,null',
        ]);

        $keys = ['SCOUT_DRIVER' => $request->driver];

        if ($request->driver === 'algolia') {
            $keys['ALGOLIA_APP_ID'] = $request->algolia_app_id;
            $keys['ALGOLIA_SECRET'] = $request->algolia_secret;
        } elseif ($request->driver === 'elasticsearch') {
            $keys['ELASTICSEARCH_HOST'] = $request->elasticsearch_host;
        } elseif ($request->driver === 'meilisearch') {
            $keys['MEILISEARCH_HOST'] = $request->meilisearch_host;
            $keys['MEILISEARCH_KEY'] = $request->meilisearch_key;
        }

        $this->dotenvEditor->setKeys($keys);
        $this->dotenvEditor->save();

        return response()->json(['message' => 'Search settings updated successfully.']);
    }
}
