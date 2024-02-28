<li class='d-flex justify-content-between mb-4'>
    <div class='card w-100'>
        <div class='card-header d-flex justify-content-between p-3'>
            <p class='fw-bold mb-0'>
                {{ \App\Models\User::where('id',$message->sender_id)->value('name') }}
            </p>
            <p class='text-muted small mb-0'>{{ $message->diff_for_humans }}</p></div>
        <div class='card-body'><p class='mb-0'>{{ $message->message }}</p></div>
    </div>
    <img src='https://eu.ui-avatars.com/api/?name={{ \App\Models\User::where('id',$message->sender_id)->value('name') }}'
         class='rounded-circle d-flex align-self-start ms-3 shadow-1-strong' width='60' alt="">
</li>
