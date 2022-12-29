{{--@include('protocols.includes.group', $templateprotocolgroup)--}}
@if (array_key_exists('children', $duty))
    <div style="margin-left: 20px;" class="my_parent_{{$duty['id']}}">
        @foreach($duty['children'] as $duty)
            <div class="d-flex p-2 w-100 border-bottom align-items-center">
                <div>{{$duty['name']}}</div>
                <div class="ml-auto">
                    <button class="btn btn-xs btn-outline-success mr-1" onclick="init_modal({{$duty['id']}})"><i class="fa fa-plus"></i> Добавить подгруппу</button>
                    <button class="btn btn-xs btn-outline-info mr-1" onclick="init_edit_modal({{$duty['id']}})"><i class="fa fa-edit"></i></button>
                    <button class="btn btn-xs btn-danger delete_btn" data-id="{{$duty['id']}}" title="Удалить"><i class="fa fa-trash"></i></button>
                </div>
            </div>
            @include('admin.duties.template_tree_group', $duty)
        @endforeach
    </div>
@endif
