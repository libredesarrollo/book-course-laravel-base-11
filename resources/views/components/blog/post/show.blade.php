 <div class="card card-white">

     {!! SEO::generate() !!}

     <h1>{{ $post->title }}</h1>
     <span>{{ $post->category->title }}</span>
     <hr>

     {{ $post->content }}
 </div>
