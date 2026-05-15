<?php

namespace App\Http\Controllers;

use App\Jobs\ChainJobs;
use Illuminate\Http\Request;
use Spatie\RouteAttributes\Attributes\Get;
class TestController extends Controller
{
    #[Get('www')]
    public function cookie(Request $request)
    {
        for($i = 0; $i < 200; $i++) {
            ChainJobs::dispatch();
        }

        return '123';
    }
}
