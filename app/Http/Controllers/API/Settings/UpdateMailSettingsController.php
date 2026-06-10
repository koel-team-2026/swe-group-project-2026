<?php

namespace App\Http\Controllers\API\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Jackiedo\DotenvEditor\DotenvEditor;

class UpdateMailSettingsController extends Controller
{
    public function __construct(
        private readonly DotenvEditor $dotenvEditor,
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $request->validate([
            'host' => 'required|string',
            'port' => 'required|numeric',
            'username' => 'nullable|string',
            'password' => 'nullable|string',
            'encryption' => 'nullable|string',
            'from_address' => 'required|email',
        ]);

        $this->dotenvEditor->setKeys([
            'MAIL_HOST' => $request->host,
            'MAIL_PORT' => $request->port,
            'MAIL_USERNAME' => $request->username,
            'MAIL_PASSWORD' => $request->password,
            'MAIL_ENCRYPTION' => $request->encryption,
            'MAIL_FROM_ADDRESS' => $request->from_address,
        ]);

        $this->dotenvEditor->save();

        return response()->json(['message' => 'Mail settings updated successfully.']);
    }
}
