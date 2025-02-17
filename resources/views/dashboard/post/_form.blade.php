@csrf

<label for="">Title</label>
<input class='form-control' type="text" name="title" value="{{ old('title', $post->title) }}">

<label for="">Slug</label>
<input class='form-control' type="text" name="slug" value="{{ old('slug', $post->slug) }}">

<label for="">Content</label>
<textarea class='form-control !hidden content' name="content">{{ old('content', $post->content) }}</textarea>

<div id="editor">
    {!! old('content', $post->content) !!}
</div>

<label for="">Category</label>
<select class='form-control' name="category_id">
    @foreach ($categories as $title => $id)
        <option {{ old('category_id', $post->category_id) == $id ? 'selected' : '' }} value="{{ $id }}">
            {{ $title }}
        </option>
    @endforeach
</select>

<label for="">Description</label>
<textarea class='form-control' name="description">{{ old('description', $post->description) }}</textarea>

<label for="">Posted</label>
<select class='form-control' name="posted">
    <option {{ old('posted', $post->posted) == 'not' ? 'selected' : '' }} value="not">Not</option>
    <option {{ old('posted', $post->posted) == 'yes' ? 'selected' : '' }} value="yes">Yes</option>
</select>

<label for="">Tags</label>
<select class='form-control' multiple name="tags_id[]">
    @foreach ($tags as $name => $id)
        {{-- <option {{ in_array($id, old('tags_id') ?: $post->tags->pluck('id')->toArray()) ? 'selected' : '' }} value="{{ $id }}">{{ $name }} --}}
        <option {{ in_array($id, old('tags_id', $post->tags->pluck('id')->toArray())) ? 'selected' : '' }}
            value="{{ $id }}">{{ $name }}
        </option>
    @endforeach
</select>

@if (isset($task) && $task == 'edit')
    <label for="">Image</label>
    <input class='form-control' type="file" name="image">
@endif

<button type="submit" class="btn btn-success mt-2">Send</button>

<script>
    document.querySelector('#myForm').addEventListener('submit', function(e) {
        document.querySelector('.content').value = editor.getData()
        // e.preventDefault();
    })
</script>

@vite(['resources/css/ckeditor.css', 'resources/js/ckeditor.js'])
