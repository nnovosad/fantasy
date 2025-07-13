<?php

namespace App\Http\Controllers;

use App\Services\PlayerSelectionService;

class AssistantController extends Controller
{
    public function __construct(
        private readonly PlayerSelectionService $playerSelectionService
    ) {
    }

    public function __invoke()
    {
        ini_set('max_execution_time', 300);

//        $this->playerSelectionService->handle();

        return view('assistant', [
            'data' => $this->playerSelectionService->handle()
        ]);
    }
}
