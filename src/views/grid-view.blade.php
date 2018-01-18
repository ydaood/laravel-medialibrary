@if((sizeof($files) > 0) || (sizeof($directories) > 0))
<?php

$url = request()->headers->get('referer');

function getatriputes($url) {


    $paremtar = explode('?', $url)[1];
    $paremtars = explode('&', $paremtar);
    $allpara = [];
    foreach ($paremtars as $p) {
        $arr = explode('=', $p);
        $allpara[$arr[0]] = $arr[1];
    }
    return $allpara;
}

$mode = null;
if ($url != null) {
    foreach (getatriputes($url) as $key => $value) {
        if ($key == 'mode') {
            $mode = $value;
            break;
        }
    }
}
?>
<!--

//$_SERVER['HTTP_REFERER']
-->
<div class="row">

    @foreach($items as $item)
    <div class="col-xs-6 col-sm-4 col-md-3 col-lg-2 img-row">
        <?php $item_name = $item->name; ?>
        <?php $thumb_src = $item->thumb; ?>
<?php $item_path = $item->is_file ? $item->url : $item->path; ?>

        <div class="square clickable {{ $item->is_file ? '' : 'folder-item' }}" data-id="{{ $item_path }}"
             @if($item->is_file && $thumb_src) onclick="<?php
             if (!$mode) {
                 echo "fileView('" . $item_path . "', '" . $item->updated . "')";
             } else {
                 echo "selectFile('" . $item_path . "')";
             }
             ?>"
             @elseif($item->is_file) onclick="download('{{ $item_name }}')" @endif >
             @if($thumb_src)
             <img src="{{ $thumb_src }}">
            @else
            <i class="fa {{ $item->icon }} fa-5x"></i>
            @endif

        </div>
        @if($item->is_file&&($mode==null)) <input type="checkbox" name="selectedItem[]" value="{{$item_path}}"> @endif
        <div class="caption text-center">
            <div class="btn-group">
                <button type="button" data-id="{{ $item_path }}"
                        class="item_name btn btn-default btn-xs {{ $item->is_file ? '' : 'folder-item'}}"
                        @if($item->is_file && $thumb_src) onclick="fileView('{{ $item_path }}', '{{ $item->updated }}')"
                        @elseif($item->is_file) onclick="download('{{ $item_name }}')" @endif >
                        {{ $item_name }}
            </button>
            <button type="button" class="btn btn-default dropdown-toggle btn-xs" data-toggle="dropdown" aria-expanded="false">
                <span class="caret"></span>
                <span class="sr-only">Toggle Dropdown</span>
            </button>
            <ul class="dropdown-menu" role="menu">
                <li><a href="javascript:rename('{{ $item_name }}')"><i class="fa fa-edit fa-fw"></i> {{ Lang::get('laravel-filemanager::lfm.menu-rename') }}</a></li>
                @if($item->is_file)
                <li><a href="javascript:download('{{ $item_name }}')"><i class="fa fa-download fa-fw"></i> {{ Lang::get('laravel-filemanager::lfm.menu-download') }}</a></li>
                <li class="divider"></li>
                @if($thumb_src)
                <li><a href="javascript:fileView('{{ $item_path }}', '{{ $item->updated }}')"><i class="fa fa-image fa-fw"></i> {{ Lang::get('laravel-filemanager::lfm.menu-view') }}</a></li>
                <li><a href="javascript:resizeImage('{{ $item_name }}')"><i class="fa fa-arrows fa-fw"></i> {{ Lang::get('laravel-filemanager::lfm.menu-resize') }}</a></li>
                <li><a href="javascript:cropImage('{{ $item_name }}')"><i class="fa fa-crop fa-fw"></i> {{ Lang::get('laravel-filemanager::lfm.menu-crop') }}</a></li>
                <li class="divider"></li>
                @endif
                @endif
                <li><a href="javascript:trash('{{ $item_name }}')"><i class="fa fa-trash fa-fw"></i> {{ Lang::get('laravel-filemanager::lfm.menu-delete') }}</a></li>
            </ul>
        </div>
    </div>

</div>
@endforeach

</div>

@else
<p>{{ Lang::get('laravel-filemanager::lfm.message-empty') }}</p>
@endif
<script>
    function inArray(array, value) {
        for (var i = 0; i < array.length; i++) {
            if (value == array[i]) {
                return true;
            }
        }
        return false;
    }

    if (localStorage.items) {
        var items = JSON.parse(localStorage.items);
        $('input[name="selectedItem[]"]').each(function (i) {
            if (inArray(items, $(this).val())) {
                $(this).prop('checked', true);
            }
        });
    }

</script>
