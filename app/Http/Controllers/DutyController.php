<?php

namespace App\Http\Controllers;

use App\Models\Duty;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class DutyController extends Controller
{
    private function buildTree(array &$elements, $parentId = 0)
    {
        $branch = [];

        foreach ($elements as $element) {
            if ($element['parent_id'] == $parentId) {
                $children = $this->buildTree($elements, $element['id']);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[$element['id']] = $element;
//                unset($elements[$element['id']]);
            }
        }

        return $branch;
    }

    public function index(): Factory|JsonResponse|View|Application
    {
        if (auth()->user()->isRole()) {
            return response()->json(
                [
                    'success' => 'false',
                    'error' => 'access only admin group'
                ]
            );
        }
        $duties = Duty::all()->toArray();
        $duties = $this->buildTree($duties);

        return view('admin.duties.index',
            [
                'title' => 'Рабочие обязанности',
                'duties' => $duties,
            ]);
    }

    public function add(): Factory|View|Application
    {
        return view(
            'admin.duties.modal',
            [
                'title' => 'Добавить обязанность',
                'parent_id' => request('parent_id'),
            ]
        );
    }

    public function edit(): Factory|JsonResponse|View|Application
    {
        $duty = Duty::find(request('id'));
        if ($duty == null) {
            return response()->json([
                'success' => 'false',
                'error' => 'not found',
            ]);
        }

        return view('admin.duties.modal', [
            'title' => 'Редактирование обязанность',
            'duty' => $duty,
            'parent_id' => $duty->parent_id,
        ]);
    }

    public function store()
    {
        if (request()->has('id') && request('id') != '') {
            $duty = Duty::find(request('id'));
            if ($duty == null) {
                return response()->json([
                    'success' => 'false',
                    'error' => 'not found',
                ]);
            }
        } else {
            $duty = new Duty();
            $duty->parent_id = request('parent_id');
        }

        $validate_arr = [
            'name',
        ];
        foreach ($validate_arr as $item) {
            if (! request()->has($item) || request($item) == '') {
                return response()->json([
                    'success' => 'false',
                    'error' => 'Заполните необходимые поля',
                ]);
            }
        }

        $duty->name = request('name');
        $duty->save();

        return response()->json([
            'success' => 'true',
        ]);
    }

    public function delete($id)
    {
        $duty = Duty::find($id);
        if ($duty == null) {
            return response()->json([
                'success' => 'false',
                'error' => 'not found',
            ]);
        }

        function DeleteRecursive($parent_id)
        {
            $duties = Duty::where('parent_id', $parent_id)->get();
            if (count($duties) == 0) {
                return;
            }
            foreach ($duties as $d) {
                $new_parent = $d->id;
                $d->delete();
                DeleteRecursive($new_parent);
            }
        }
        DeleteRecursive($duty->id);
        $duty->delete();

        return response()->json(['success' => 'true']);
    }
}
