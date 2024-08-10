@csrf

<label for="">Name</label>
<input class="form-control" type="text" name="name" value="{{ old('name', $tag->name) }}">

<button type="submit" class="btn btn-success mt-2">Send</button>