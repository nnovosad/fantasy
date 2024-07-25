<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Contracts\ConverterInterface;
use Illuminate\Http\Request;

class ConverterController extends Controller
{
    public function __construct(public ConverterInterface $converter) {}

    public function __invoke(Request $request): void
    {
        $requestData = $request->all();
        $league = $requestData['data']['attributes']['league'];

        $this->converter->handler($league);
    }
}
