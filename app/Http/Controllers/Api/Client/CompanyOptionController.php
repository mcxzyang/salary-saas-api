<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Models\CompanyOption;

class CompanyOptionController extends Controller
{
    public function index()
    {
        $list = CompanyOption::query()
            ->with(['customModule'])
            ->get();
        return $this->success($list);
    }
}
