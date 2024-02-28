<div class="card mb-4">
    <div class="card-body">
        <p>{{ $comment->body }}</p>

        <div class="d-flex justify-content-between">
            <div class="d-flex flex-row align-items-center">
                <img
                    src="https://eu.ui-avatars.com/api/?name={{ Str::camel($comment->user->name) }}"
                    width="25"
                    height="25" alt=""/>
                <p class="small mb-0 ms-2">{{ $comment->user->name }}</p>
                <p class="small text-muted mb-0 end-0 position-absolute p-3">{{$comment->created_at->diffForHumans()}}</p>
            </div>
            <div class="d-flex flex-row align-items-center">
            </div>
        </div>
    </div>
</div>
