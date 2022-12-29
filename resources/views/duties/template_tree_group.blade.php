{{--@include('protocols.includes.group', $templateprotocolgroup)--}}
@if (array_key_exists('children', $duty))
    <div style="margin-left: 20px;" class="my_parent my_parent_{{$duty['id']}}">
        @foreach($duty['children'] as $duty)
            <div class="d-flex p-2 w-100 border-bottom align-items-center " style="min-height: 50px;">
                <div  class="duty__name">{{$duty['name']}}</div>
                <div class="ml-auto">
                    @if(!array_key_exists('children', $duty))
                    <a title="Начать выполнение" href="javascript:;" class="btn btn-success  btn__action" onclick="changeStatusDuty('{{$duty['id']}}',1);"><i class="fas fa-hand-point-up"></i></a>
                    @else
                        <a href="javascript:;" class="duties__open_close btn__action"><i class="fa fa-plus"></i><i class="fa fa-minus"></i></a>
                @endif
                </div>
            </div>
            @include('duties.template_tree_group', $duty)
        @endforeach
    </div>
@endif
