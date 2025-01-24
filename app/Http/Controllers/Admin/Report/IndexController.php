<?php

namespace App\Http\Controllers\Admin\Report;

class IndexController extends BaseController
{
    public function index()
    {
        return view('admin.report.index');
    }
}
