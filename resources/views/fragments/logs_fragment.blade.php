<style>
    .ps {
        font-size: 12px;
    }
</style>
<div class="row p-5">
    <h1 class="fw-bold">Logs</h1>
    <p class="ps">A log record will be deleted automatically after 14days.</p>
    @foreach($data as $entry)
    <div class="col-md-12 col-lg-12 col-sm-12 mb-1">
        <div class="list-group w-100">
            <div class="list-group-item border-5  align-items-start w-100">
                <div class="d-flex justify-content-between">
                    <h5 class="mb-1">Action</h5>
                    <small>{{ $entry->created_at->format('l jS, \of F, Y  g:i  A')}}</small>
                </div>
                <div class="d-flex">
                    @if($entry->action == 'c')
                    <i class="bx bx-check text-success"></i>
                    <p class="mb-1 ps text-success">{{$entry->description}}</p>
                    @endif
                    @if($entry->action == 'u')
                    <i class="bx bx-edit text-primary"></i>
                    <p class="mb-1 ps text-primary">{{$entry->description}}</p>
                    @endif
                    @if($entry->action == 'd')
                    <i class="bx bx-trash text-danger"></i>
                    <p class="mb-1 ps text-danger">{{$entry->description}}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>