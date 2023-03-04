@extends('admin.layouts.master')

@section('content')
    @if (session('success'))
        <div class="alert alert-dismissible alert-success">
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            <h4 class="alert-heading">Success!</h4>
            <p class="mb-0">New product was added successfully!</p>
        </div>
    @endif
    <div class="card">
        <h3 classs="card-header">Add a product</h3>
        <div class="card-body">
            <div class="col-md-12">
                <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="title" class="mt-4">Product Title:</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror " name="title"
                            placeholder="Enter product title">
                        <span class="text-danger">
                            @error('title')
                                {{ $message }}
                            @enderror
                        </span>
                    </div>

                    <div class="form-group">
                        <label for="summary" class="col-form-label">Summary <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="summary" name="summary">{{old('summary')}}</textarea>
                        @error('summary')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                      </div>
              
                      <div class="form-group">
                        <label for="description" class="col-form-label">Description</label>
                        <textarea class="form-control" id="description" name="description">{{old('description')}}</textarea>
                        @error('description')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                      </div>

                    <div class="form-group">
                        <label for="cat_id">Category <span class="text-danger">*</span></label>
                        <select name="cat_id" id="cat_id" class="form-control">
                            <option value="">--Select any category--</option>
                            @foreach ($categories as $key => $cat_data)
                                <option value='{{ $cat_data->id }}'>{{ $cat_data->title }}</option>
                            @endforeach
                        </select>
                    </div>


                    {{-- <div class="form-group">
                        <label for="price" class="mt-4">Product Price:</label>
                        <input type="number" step="any" min="1"
                            class="form-control @error('price') is-invalid @enderror " name="price"
                            placeholder="Enter product price">
                        <span class="text-danger">
                            @error('price')
                                {{ $message }}
                            @enderror
                        </span>

                    </div> --}}

                    <div class="form-group">
                        <label for="price" class="col-form-label">Price(KSh) <span class="text-danger">*</span></label>
                        <input id="price" type="number" name="price" placeholder="Enter price"  value="{{old('price')}}" class="form-control">
                        @error('price')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                      </div>
              
                      <div class="form-group">
                        <label for="discount" class="col-form-label">Discount(%)</label>
                        <input id="discount" type="number" name="discount" min="0" max="100" placeholder="Enter discount"  value="{{old('discount')}}" class="form-control">
                        @error('discount')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                      </div>
                    <div class="form-group">
                        <label for="files" class="form-label mt-4">Upload Product Images:</label>
                        <input type="file" name="images[]" class="form-control" accept="image/*" multiple>
                    </div>


                    <div class="form-group">
                        <label for="status" class="col-form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-control">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                        @error('status')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="files" class="form-label mt-4">Upload thumbnail:</label>
                        <input class="form-control" name="thumbnail" type="file" id="thumbnail">
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Save Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('admin/summernote/summernote.min.css') }}">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
@endpush
@push('scripts')
    <script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
    <script src="{{ asset('admin/summernote/summernote.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

    <script>
        $('#lfm').filemanager('image');

        $(document).ready(function() {
            $('#summary').summernote({
                placeholder: "Write short seats.....",
                tabsize: 2,
                height: 100
            });
        });

        $(document).ready(function() {
            $('#seats').summernote({
                placeholder: "Write detail seats.....",
                tabsize: 2,
                height: 150
            });
        });
        // $('select').selectpicker();
    </script>

    
@endpush