<?php

namespace FluentConnect\App\Http\Controllers;

use FluentConnect\App\Models\FeedRunner;
use FluentConnect\Framework\Http\Request\Request;

class LogsController extends Controller
{
    public function get(Request $request)
    {
        $runners = FeedRunner::with(['trigger', 'actionLogs'])
            ->orderBy('id', 'DESC')
            ->paginate($request->get('per_page', 15));

        return [
            'logs' => $runners
        ];

    }
}


