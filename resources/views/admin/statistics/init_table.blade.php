<style>
    /* Ensure that the demo table scrolls */
    th, td {
        white-space: nowrap;
    }

    div.dataTables_wrapper {
        margin: 0 auto;
    }

    /* Lots of padding for the cells as SSP has limited data in the demo */
    th,
    td {
        padding-left: 8px !important;
        padding-right: 8px !important;
    }

    .DTFC_LeftBodyLiner {
        overflow-x: hidden;
    }
</style>
<!-- START table-responsive-->
                             <div class="table-responsive" >
<table class="table table-striped" id="statistics" style="width: 100%;">
    <thead>
    <tr>
        @foreach($columns as $column)
            <th width="120" style="max-width: 120px; white-space: inherit;">{{$column['title']}}</th>
        @endforeach
    </tr>
    </thead>
    @foreach($data as $d)
        <tr>
            @foreach($d as $item)
                <td>{{$item}}</td>
            @endforeach
        </tr>
    @endforeach
    <tfoot>
    <tr>
        @foreach($data_totals as $data_total)
            <th>{{$data_total}}</th>
        @endforeach
    </tr>
    </tfoot>
</table>
</div>

