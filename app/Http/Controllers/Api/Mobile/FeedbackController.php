<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function store(Request $request, Feedback $feedback)
    {
        $this->validate($request, [
            'content' => 'required'
        ]);
        $user = auth('client')->user();

        $params = $request->all();

        $feedback->fill(array_merge($params, ['company_user_id' => $user->id]));
        $feedback->save();

        return $this->message('操作成功');
    }
}
