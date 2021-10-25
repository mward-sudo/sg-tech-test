@if ($uploads->count())
<div class=text-center>
    <h2 class="text-xl font-bold">View Uploads</h2>
    <ul>
    @foreach ($uploads as $upload)
        <li class="mt-4"><a href="{{$upload->path}}" class="underline">{{$upload->uploaded_at}}</a></li>
    @endforeach
    </ul>
</div>
@endif
