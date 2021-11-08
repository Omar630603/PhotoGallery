@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="container">
        @if (count($errors) > 0)
        <div class="alert alert-danger">
            <strong>Sorry !</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @if(session('success'))
        <div class="alert alert-success">
            {!! session('success') !!}
        </div>
        @endif
    </div>
    <div class="container upload-btn">
        <a onclick="$('#gallery-photo-add').click();" class="btn btn-sm btn-warning"
            style="background-color: #f57c00; border: 0">Upload Photos</a>
        <form method="post" action="{{ route('photos.store') }}" enctype="multipart/form-data">
            @csrf
            <input hidden type="file" name="images[]" multiple class="form-control" multiple id="gallery-photo-add"
                accept="image/*">
            <div class="gallery">
                <button style="display: none" id="submit-upload-btn" type="submit"
                    class="btn btn-sm btn-light">Upload</button>
            </div>

        </form>
    </div>
    @if (count($photos)>0)
    <div class="container mt-2 mb-2">
        <div class="d-flex center">
            @if (is_array($photos) || is_object($photos))
            {{$photos->links("pagination::bootstrap-4")}}
            @endif
        </div>
        <h1>
            Your Photos ({{$allPhotos}} Photos)
            <a class="btn btn-sm btn-secondary float-right mt-3" onclick="$('#deleteAll').submit()">Delete All</a>
        </h1>
        <div class="mb-3">
            <form hidden action="{{ route('photos.deleteAll') }}" id="deleteAll">
                @csrf
            </form>
        </div>
    </div>
    <div class="container">
        <div class="container photos">
            <div class="grid">
                @foreach ($photos as $photo)
                <div class="item">
                    <img data-bs-toggle="tooltip" title="Title: {{$photo->title}}
                Description: {{$photo->description}}" id="{{$photo->id_photo}}" src="storage/{{$photo->img}}"
                        alt="{{$photo->title}}" />
                    <div class="middle" data-bs-toggle="tooltip" title="Title: {{$photo->title}}
                    Description: {{$photo->description}}">
                        <div class="text">
                            <a href="{{ route('photos.download', $photo) }}">
                                <i class="fas fa-download"></i></a>
                            <a data-toggle="modal" data-target="#editPhoto{{$photo->id_photo}}">
                                <i class="far fa-edit"></i></a>
                            <a data-toggle="modal" data-target="#deletePhoto{{$photo->id_photo}}">
                                <i class="far fa-trash-alt"></i></a>
                        </div>
                    </div>
                    <div class="modal fade" id="deletePhoto{{$photo->id_photo}}" aria-hidden="true"
                        aria-labelledby="deletePhoto{{$photo->id_photo}}Label" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deletePhoto{{$photo->id_photo}}Label">
                                        Delete {{$photo->title}}
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form id="deletephotoForm{{$photo->id_photo}}"
                                        action="{{ route('photos.destroy', $photo) }}" enctype="multipart/form-data"
                                        method="POST">
                                        @method('DELETE')
                                        @csrf
                                        <div class="form-row">
                                            <div class="form-group col-md-12">
                                                <div class="class alert alert-danger">
                                                    <label for="photo_title">Photo Title</label>
                                                    <p>This photo will be deleted click delete to continue.</p>
                                                    <input id="photo_title{{$photo->id_photo}}" type="text"
                                                        class="form-control" placeholder="Photo Title"
                                                        value="{{$photo->title}}" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label for="Preview">Preview</label>
                                                <img class="previewImage" id="delete{{$photo->id_photo}}"
                                                    src="storage/{{$photo->img}}" alt="{{$photo->title}}" />
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-sm btn-secondary"
                                        data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="$('#deletephotoForm{{$photo->id_photo}}').submit()">Delete</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="editPhoto{{$photo->id_photo}}" aria-hidden="true"
                        aria-labelledby="editPhoto{{$photo->id_photo}}Label" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editPhoto{{$photo->id_photo}}Label">
                                        Edit {{$photo->title}}
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form id="editphotoForm{{$photo->id_photo}}"
                                        action="{{ route('photos.update', $photo) }}" enctype="multipart/form-data"
                                        method="POST">
                                        @method('PUT')
                                        @csrf
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="photo_title">Photo Title</label>
                                                <input id="photo_title{{$photo->id_photo}}" type="text"
                                                    class="form-control" placeholder="Photo Title" name="title"
                                                    value="{{$photo->title}}">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="photo_description">Photo Description</label>
                                                <input id="photo_description{{$photo->id_photo}}" type="text"
                                                    class="form-control" placeholder="Photo Description"
                                                    name="description" value=" {{$photo->description}}">
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label for="Preview">Preview</label>
                                                <img class="previewImage" id="edit{{$photo->id_photo}}"
                                                    src="storage/{{$photo->img}}" alt="{{$photo->title}}" />
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-sm btn-secondary"
                                        data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-sm btn-primary"
                                        onclick="$('#editphotoForm{{$photo->id_photo}}').submit()">Save changes</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
                <div class="placeholder"></div>
            </div>
        </div>
    </div>
    @else
    <div class="mt-5" style="text-align: center; color: #fff">
        <h5>You don't have photos yet, start uploading to see your photos here:)</h5>
        <img class="img-fluid" width="100px" src="{{ asset('images\noImages.png') }}" alt="">
    </div>
    @endif
</div>
<script>
    document.querySelectorAll('.item img').forEach((img) => {
  // Ideally, we would know the image size or aspect ratio beforehand...
  if (img.naturalHeight) {
    setItemRatio.call(img);
  } else {
    img.addEventListener('load', setItemRatio);
  }
});

function setItemRatio() {
    this.parentNode.style.setProperty('--ratio', this.naturalHeight / this.naturalWidth);
}
</script>
<script>
    $(function() {
    var imagesPreview = function(input, placeToInsertImagePreview) {
        if (input.files) {
            var filesAmount = input.files.length;
            for (i = 0; i < filesAmount; i++) {
                var reader = new FileReader();
                reader.onload = function(event) {
                    $($.parseHTML('<img>')).attr('src', event.target.result).appendTo(placeToInsertImagePreview);
                }
                reader.readAsDataURL(input.files[i]);
            }
        }
    };
    $('#gallery-photo-add').on('change', function() {
        imagesPreview(this, 'div.gallery');
        $('#submit-upload-btn').show('slow');
    });
});
</script>
@endsection