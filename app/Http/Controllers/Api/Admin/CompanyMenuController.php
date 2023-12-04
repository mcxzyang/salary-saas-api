<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanyMenu;
use Illuminate\Http\Request;

class CompanyMenuController extends Controller
{
    public function index(Request $request)
    {
        $query = CompanyMenu::query()
            ->with(['children'])->where('type', 1);

        $title = $request->input('title');
        $status = $request->input('status');
        if ($title) {
            $query->where('title', 'like', sprintf('%%%s%%', $title));
        }
        if ($status !== null) {
            $query->where('status', $status);
        }
        if (!$title && $status === null || $status == 1) {
            $query->where('parent_id', 0);
        }
        $list = $query
            ->orderBy('sort')
            ->get();
        return $this->success($list);
    }

    public function tree()
    {
        $list = CompanyMenu::query()
            ->orderBy('sort')
            ->get();
        return $this->success($this->getTree($list));
    }


    private function getTree($data, $pId = 0)
    {
        $tree = [];
        foreach ($data as $k => $v) {
            if ($v['parent_id'] == $pId) {
                $v['children'] = $this->getTree($data, $v['id']);
                $tree[] = ['key' => $v['id'], 'title' => $v['title'], 'children' => $v['children']];
            }
        }
        return $tree;
    }

    public function store(Request $request, CompanyMenu $companyMenu)
    {
        $params = $request->all();

        $companyMenu->fill($params);
        $companyMenu->save();

        return $this->message('操作成功');
    }

    public function update(Request $request, CompanyMenu $companyMenu)
    {
        $params = $request->all();

        $companyMenu->fill($params);
        $companyMenu->save();

        return $this->message('操作成功');
    }

    public function destroy(CompanyMenu $companyMenu)
    {
        $companyMenu->children()->delete();
        $companyMenu->delete();

        return $this->message('删除成功');
    }
}
