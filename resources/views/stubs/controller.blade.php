@php
    echo "<?php".PHP_EOL;
@endphp

namespace App\Http\Controllers\Api\{{ $moduleName }};

use App\Http\Requests\{{ $moduleName }}\Create{{ $modelName }}Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\BaseResource;
use App\Models\{{ $modelName }};
use Illuminate\Http\Request;

class {{ $modelName }}Controller extends Controller
{

    public function index(Request $request)
    {
        $list = {{ $modelName }}::filter($request->all())
        ->orderBy('id', 'desc')
        ->paginateOrGet();
        return $this->success(BaseResource::collection($list));
    }

    public function show({{ $modelName }} ${{ camel($modelName) }})
    {
        // $this->authorize('own', ${{ camel($modelName) }});

        return $this->success(new BaseResource(${{ camel($modelName) }}));
    }


    public function store(Create{{ $modelName }}Request $request, {{ $modelName }} ${{ camel($modelName) }})
    {
        $params = $request->all();

        ${{ camel($modelName) }}->fill($params);
        ${{ camel($modelName) }}->save();

        return $this->message('操作成功');
    }

    public function update(Request $request, {{ $modelName }} ${{ camel($modelName) }})
    {
        $params = $request->all();

        ${{ camel($modelName) }}->fill($params);
        ${{ camel($modelName) }}->save();

        return $this->message('操作成功');
    }

    public function destroy({{ $modelName }} ${{ camel($modelName) }})
    {
        ${{ camel($modelName) }}->delete();

        return $this->message('操作成功');
    }
}
