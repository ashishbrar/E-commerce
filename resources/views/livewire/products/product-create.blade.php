<div class="container mt-5">
    <div class="mb-4">
        <h1 class="display-4">{{ __('Create Product') }}</h1>
        <p class="lead mb-4">{{ __('Form to create a product') }}</p>
        <hr>
    </div>

    @if (session()->has('message'))
        <div class="alert alert-success mb-4">
            {{ session('message') }}
        </div>
    @endif

    <a href="{{ route('products.index') }}" class="btn btn-danger mb-3">Back</a>

    <form wire:submit.prevent="submit" enctype="multipart/form-data" autocomplete="off" class="mt-2">
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-body bg-light p-3 rounded">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input wire:model.defer="name" type="text" id="name" class="form-control @error('name') is-invalid @enderror" autocomplete="off" />
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea wire:model.defer="description" id="description" rows="3" class="form-control @error('description') is-invalid @enderror"></textarea>
                            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="shortdescription" class="form-label">Short Description</label>
                                <textarea wire:model.defer="shortdescription" id="shortdescription" rows="1" class="form-control @error('shortdescription') is-invalid @enderror"></textarea>
                                @error('shortdescription') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="price" class="form-label">Price</label>
                                <input wire:model.defer="price" type="number" step="0.01" min="0" id="price" class="form-control @error('price') is-invalid @enderror" />
                                @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="category_id" class="form-label">Category</label>
                                <select wire:model.defer="category_id" id="category_id" class="form-select @error('category_id') is-invalid @enderror">
                                    <option value="">-- Select Category --</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="quantity" class="form-label">Quantity</label>
                                <input wire:model.defer="quantity" type="number" id="quantity" class="form-control @error('quantity') is-invalid @enderror" autocomplete="off" />
                                @error('quantity') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-body bg-light p-3 rounded">
                        <div class="mb-3">
                            <label for="image" class="form-label">Product Image</label>
                            <input type="file" wire:model="image" id="image" accept="image/*" class="form-control @error('image') is-invalid @enderror" />
                            @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            @if ($image)
                                <div class="mt-2 position-relative d-inline-block">
                                    <img src="{{ $image->temporaryUrl() }}" alt="Main Image Preview" class="img-thumbnail" style="max-width: 150px;">
                                </div>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select wire:model.defer="status" id="status" class="form-select @error('status') is-invalid @enderror">
                                <option value="">-- Select Status --</option>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                            @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
                <div class="card mb-4">
                    <div class="card-body bg-light p-3 rounded">
                        <label for="gallery" class="form-label">Product Gallery Images</label>
                        <input type="file" wire:model="gallery_temp" id="gallery" accept="image/*" multiple class="form-control @error('gallery_temp.*') is-invalid @enderror" />
                        <div class="text-muted small mt-2 mb-2">
                            Drag &amp; drop images here or click to select.
                        </div>
                        @error('gallery_temp.*') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <div class="mt-2 d-flex flex-wrap gap-2">
                            @if (isset($oldGalleryUrls) && is_array($oldGalleryUrls) && count($oldGalleryUrls))
                                @foreach ($oldGalleryUrls as $oldIndex => $oldGalleryUrl)
                                    <div class="position-relative d-inline-block" style="width: 90px; height: 90px;">
                                        <img src="{{ $oldGalleryUrl }}" alt="Gallery Image" class="img-thumbnail" style="max-width: 80px; max-height: 80px;">
                                    </div>
                                @endforeach
                            @endif

                            @if (is_array($gallery_temp) && count($gallery_temp))
                                @foreach ($gallery_temp as $index => $galleryImage)
                                    <div class="position-relative d-inline-block" style="width: 90px; height: 90px;">
                                        <img src="{{ $galleryImage->temporaryUrl() }}" alt="Gallery Preview" class="img-thumbnail" style="max-width: 80px; max-height: 80px;">
                                        <button type="button"
                                            class="btn btn-danger btn-sm position-absolute"
                                            style="top: 2px; right: 2px; z-index: 2; border-radius: 50%; width: 24px; height: 24px; padding: 0; display: flex; align-items: center; justify-content: center;"
                                            wire:click="removeGalleryImage({{ $index }})"
                                            title="Remove Image">
                                            <span aria-hidden="true" style="font-size: 16px; line-height: 1;">&times;</span>
                                        </button>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-12 d-flex justify-content-end">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
</div>