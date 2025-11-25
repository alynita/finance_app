@foreach($children as $item)
<li data-id="{{ $item->id }}">
    <div class="kro-item">
        <span class="caret kode-span" data-original="{{ $item->kode }}">{{ $item->kode }}</span>
        <span class="akun-span" data-original="{{ $item->kode_akun }}">{{ $item->kode_akun ?? '' }}</span>
        <div class="kro-buttons">
            <button class="btn-add-child btn btn-sm btn-primary">+ Child</button>
            <button class="btn-edit-kode btn btn-sm btn-warning">Edit Kode</button>
            <button class="btn-edit-akun btn btn-sm btn-info">Edit Kode Akun</button>
            <button class="btn-delete btn btn-sm btn-danger">Hapus</button>
        </div>
    </div>
    @if($item->children->count())
        <ul class="nested">
            @include('admin.kro.partials.kro-children', ['children'=>$item->children])
        </ul>
    @endif
</li>

@endforeach
