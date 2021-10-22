<div class="panel-heading">
    <a data-toggle="tooltip"
       data-placement="bottom"
       title="Go to first page of keys"
       href="?node={!! request('node') !!}&page=0"
       @if (request('page') == 0)
       disabled="disabled"
       @endif
       class="btn btn-default btn-sm">
        <span id="btnRefreshIcon" class="glyphicon glyphicon-fast-backward"></span>
    </a>
    <a data-toggle="tooltip"
       data-placement="bottom"
       title="Go to previous page of keys"
       @if (request('page') == 0)
       disabled="disabled"
       @endif
       href="?node={!! request('node') !!}&page={!! request('page') - 1 !!}"
       class="btn btn-default btn-sm">
        <span id="btnRefreshIcon" class="glyphicon glyphicon-backward"></span>
    </a>
    <select class="input-sm" style="width: 70px;" id="pagechange">
        @foreach(range(0, floor($size / $take)) as $page)
            <option value="{!! $page !!}" {!! request('page') == $page ? 'selected="selected"' : '' !!}>
                {!! $page !!}
            </option>
        @endforeach
    </select>
    <a data-toggle="tooltip"
       data-placement="bottom"
       title="Go to next page of keys"
       @if (request('page') == floor($size / $take))
       disabled="disabled"
       @endif
       href="?node={!! request('node') !!}&page={!! request('page') + 1 !!}"
       class="btn btn-default btn-sm">
        <span id="btnRefreshIcon" class="glyphicon glyphicon-forward"></span>
    </a>
    <a data-toggle="tooltip"
       data-placement="bottom"
       title="Go to last page of keys"
       @if (request('page') == floor($size / $take))
       disabled="disabled"
       @endif
       href="?node={!! request('node') !!}&page={!! floor($size / $take) !!}"
       class="btn btn-default btn-sm">
        <span id="btnRefreshIcon" class="glyphicon glyphicon-fast-forward"></span>
    </a>
    <select class="input-sm" style="width: 120px; float: right;" id="takechange">
        @foreach([25, 50, 100, 250, 500, 1000] as $i)
            <option value="{!! $i !!}" {!! $take == $i ? 'selected="selected"' : '' !!}>
                {!! $i !!} per page
            </option>
        @endforeach
    </select>
</div>

<script>
    $("#takechange").change(function () {
        ajaxOptionSet('pagination', $(this).val());
        location.reload(true);
    });
    $("#pagechange").change(function () {
        window.location.href = '?node={!! request('node') !!}&page=' + $(this).val();
    });
</script>