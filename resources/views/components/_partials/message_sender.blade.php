<li class='d-flex justify-content-between mb-4'>
    <img src='https://eu.ui-avatars.com/api/?name={{Auth::user()->name}}'
         alt='avatar'
         class='rounded-circle d-flex align-self-start me-3 shadow-1-strong'
         width='60'>
    <div class='card w-100'>
        <div class='card-header d-flex justify-content-between p-3'><p class='fw-bold mb-0'>{{Auth::user()->name}} </p>
            <p class='text-muted small mb-0'>
                {{ $message->created_at->diffForHumans() }}
            </p>
        </div>
        <div class='card-body'><p class='mb-0'>{{ $message->message }}</p></div>
    </div>
</li>
