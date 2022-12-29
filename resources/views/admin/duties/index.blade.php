<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
   <meta name="description" content="Bootstrap Admin App">
   <meta name="keywords" content="app, responsive, jquery, bootstrap, dashboard, admin">
   <link rel="icon" type="image/x-icon" href="favicon.ico">
   <title>{{$title}}</title><!-- =============== VENDOR STYLES ===============-->
   <!-- FONT AWESOME-->
   @include('admin.includes.global_styles')
<!-- Datatables-->
    <link rel="stylesheet" href="{{URL::to('/')}}/vendor/datatables.net-bs4/css/dataTables.bootstrap4.css">
    <link rel="stylesheet" href="{{URL::to('/')}}/vendor/datatables.net-keytable-bs/css/keyTable.bootstrap.css">
    <link rel="stylesheet" href="{{URL::to('/')}}/vendor/datatables.net-responsive-bs/css/responsive.bootstrap.css">

    <!-- DATETIMEPICKER-->
    <link rel="stylesheet" href="{{URL::to('/')}}/vendor/bootstrap-datepicker/dist/css/bootstrap-datepicker.css">
    @include('admin.includes.global_app_styles')
</head>

<body>
{{csrf_field()}}
   <div class="wrapper">
      <!-- top navbar-->
      @include('admin.includes.header')
       <!-- sidebar-->
       @include('admin.includes.aside')
      <!-- offsidebar-->
      <!-- Main section-->
      <section class="section-container">
         <!-- Page content-->
         <div class="content-wrapper">
            <div class="content-heading">
               <div>{{$title}}</div>
                <a href="javascript:;" onclick="init_modal(0);" class="btn btn-outline-primary ml-auto">Добавить</a>
            </div>
             <div class="row">
                 <div class="col-12">
                     <div class="card card-default">
                         <div class="card-body">
                                     @if(count($duties))
                                         @foreach ($duties as $duty)
                                             <div class="w-100">
                                                 <div class="d-flex ml-1 p-2 border-bottom align-items-center">
                                                     <div class="">{{$duty['name']}}</div>
                                                     <div class="ml-auto">
                                                         <button class="btn btn-xs btn-outline-success mr-1" onclick="init_modal({{$duty['id']}})"><i class="fa fa-plus"></i> Добавить подгруппу</button>
                                                         <button class="btn btn-xs btn-outline-info mr-1" onclick="init_edit_modal({{$duty['id']}})"><i class="fa fa-edit"></i></button>
                                                         <button class="btn btn-xs btn-danger delete_btn" data-id="{{$duty['id']}}" title="Удалить"><i class="fa fa-trash"></i></button>
                                                     </div>
                                                 </div>
                                                 @include('admin.duties.template_tree_group', $duty)
                                             </div>
                                         @endforeach
                                     @endif
                         </div>
                     </div>
                 </div>
             </div>
         </div>
      </section><!-- Page footer-->
@include('admin.includes.footer')
   </div>
@include('admin.includes.global_scripts')
<!-- Datatables-->
<script src="{{URL::to('/')}}/vendor/datatables.net/js/jquery.dataTables.js"></script>
<script src="{{URL::to('/')}}/vendor/datatables.net-bs4/js/dataTables.bootstrap4.js"></script>
<script src="{{URL::to('/')}}/vendor/datatables.net-buttons/js/dataTables.buttons.js"></script>
<script src="{{URL::to('/')}}/vendor/datatables.net-buttons-bs/js/buttons.bootstrap.js"></script>
<script src="{{URL::to('/')}}/vendor/datatables.net-buttons/js/buttons.colVis.js"></script>
<script src="{{URL::to('/')}}/vendor/datatables.net-buttons/js/buttons.flash.js"></script>
<script src="{{URL::to('/')}}/vendor/datatables.net-buttons/js/buttons.html5.js"></script>
<script src="{{URL::to('/')}}/vendor/datatables.net-buttons/js/buttons.print.js"></script>
<script src="{{URL::to('/')}}/vendor/datatables.net-keytable/js/dataTables.keyTable.js"></script>
<script src="{{URL::to('/')}}/vendor/datatables.net-responsive/js/dataTables.responsive.js"></script>
<script src="{{URL::to('/')}}/vendor/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>

<!-- DATETIMEPICKER-->
<script src="{{URL::to('/')}}/vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.js"></script>
@include('admin.includes.global_app_scripts')
<!-- Modal -->
<div class="modal " id="alert"
     aria-hidden="true">
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelTitleId">Уверены?</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                <button type="button" class="btn btn-primary " id="confirm_btn">Подтвердить</button>
            </div>
        </div>
    </div>
</div>


<link rel="stylesheet" href="{{URL::to('/')}}/vendor/datatables.net/js/jquery.dataTables.js">
<link rel="stylesheet" href="{{URL::to('/')}}/vendor/datatables.net-bs4/js/dataTables.bootstrap4.js">
<script>
    var oTable;
    $(function (){
        $(document).on('click','.delete_btn',function (e){
            e.preventDefault();
            $('#confirm_btn').attr('data-id', $(this).attr('data-id'));
            $('#alert').modal();
        });

        $('#confirm_btn').on('click',function (e){
            e.preventDefault();

            var data = {
                _token: $('input[name=_token]').val(),
            };

            $.ajax({
                url: '{{URL::to('/')}}' + '/admin/duties/'+$(this).attr('data-id')+'/delete',
                method: 'post',
                data: data,
                success: function (response, status, xhr, $form) {
                    if (response.error) {
                        toastr.error(response.error);
                    } else {
                        $('#alert').modal('hide');
                        location.reload();
                    }
                }
            });
        });
    });

    function init_modal(parent_id){
        $.post('{{route('admin.duties.modal.add')}}', { _token: $('input[name=_token]').val(),parent_id:parent_id}, function (result){
            $('#duties__modal').remove();
            $('body').append(result);
            $('#duties__modal').modal('show');
        });
    }
    function init_edit_modal(id){
        $.post('{{route('admin.duties.modal.edit')}}', {id:id, _token: $('input[name=_token]').val()}, function (result){
            $('#duties__modal').remove();
            $('body').append(result);
            $('#duties__modal').modal('show');
        });
    }
</script>
</body>

</html>
