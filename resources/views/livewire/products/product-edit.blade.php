<div class="admin-page">
    <div class="admin-page-head">
        <div>
            <h2 class="admin-page-title">{{ __('Edit Product') }}</h2>
            <p class="admin-page-subtitle">{{ __('Update product details, images, inventory, and status.') }}</p>
        </div>
        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center justify-content-center gap-2">
            <i class="bi bi-arrow-left"></i>
            {{ __('Back') }}
        </a>
    </div>

    @if (session()->has('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit.prevent="submit" enctype="multipart/form-data" autocomplete="off">
        <div class="row g-3">
            <div class="col-12 col-xl-7">
                <div class="admin-card h-100">
                    <div class="admin-card-header">
                        <h3 class="admin-card-title">{{ __('Product Details') }}</h3>
                    </div>
                    <div class="admin-card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="name" class="form-label">Name</label>
                                <input wire:model.defer="name" type="text" id="name" class="form-control @error('name') is-invalid @enderror" autocomplete="off" />
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-12">
                                <label for="description" class="form-label">Description</label>
                                <textarea wire:model.defer="description" id="description" rows="4" class="form-control @error('description') is-invalid @enderror"></textarea>
                                @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="shortdescription" class="form-label">Short Description</label>
                                <textarea wire:model.defer="shortdescription" id="shortdescription" rows="2" class="form-control @error('shortdescription') is-invalid @enderror"></textarea>
                                @error('shortdescription') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="price" class="form-label">Price</label>
                                <input wire:model.defer="price" type="number" step="0.01" min="0" id="price" class="form-control @error('price') is-invalid @enderror" />
                                @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="category_id" class="form-label">Category</label>
                                <select wire:model.defer="category_id" id="category_id" class="form-select @error('category_id') is-invalid @enderror">
                                    <option value="">-- Select Category --</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="quantity" class="form-label">Quantity</label>
                                <input wire:model.defer="quantity" type="number" min="0" id="quantity" class="form-control @error('quantity') is-invalid @enderror" autocomplete="off" />
                                @error('quantity') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-5">
                <div class="admin-card mb-3">
                    <div class="admin-card-header">
                        <h3 class="admin-card-title">{{ __('Publishing') }}</h3>
                    </div>
                    <div class="admin-card-body">
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select wire:model.defer="status" id="status" class="form-select @error('status') is-invalid @enderror">
                                <option value="">-- Select Status --</option>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                            @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div>
                            <label for="image" class="form-label">Product Image</label>
                            <input type="file" wire:model="image" id="image" accept="image/*" class="form-control @error('image') is-invalid @enderror" />
                            @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror

                            @if ($image)
                                <div class="mt-3">
                                    <img src="{{ $image->temporaryUrl() }}" alt="Main Image Preview" class="admin-preview">
                                </div>
                            @elseif ($oldImageUrl)
                                <div class="mt-3">
                                    <img src="{{ $oldImageUrl }}" alt="Current Image" class="admin-preview">
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="admin-card">
                    <div class="admin-card-header">
                        <h3 class="admin-card-title">{{ __('Gallery Images') }}</h3>
                    </div>
                    <div class="admin-card-body">
                        <input type="file" wire:model="gallery_temp" id="gallery" accept="image/*" multiple class="form-control @error('gallery_temp.*') is-invalid @enderror" />
                        <div class="text-muted small mt-2">Upload multiple product gallery images.</div>
                        @error('gallery_temp.*') <div class="invalid-feedback">{{ $message }}</div> @enderror

                        <div class="mt-3 d-flex flex-wrap gap-2">
                            @if (is_iterable($productGallery) && count($productGallery))
                                @foreach ($productGallery as $gallery)
                                    <img src="{{ asset('storage/' . $gallery->image) }}" alt="Gallery Image" class="admin-gallery-thumb">
                                @endforeach
                            @endif

                            @if (is_array($gallery_temp) && count($gallery_temp))
                                @foreach ($gallery_temp as $index => $galleryImage)
                                    <div class="position-relative d-inline-block">
                                        <img src="{{ $galleryImage->temporaryUrl() }}" alt="Gallery Preview" class="admin-gallery-thumb">
                                        <button type="button"
                                            class="btn btn-danger btn-sm position-absolute top-0 end-0 d-inline-flex align-items-center justify-content-center"
                                            style="width: 24px; height: 24px; padding: 0;"
                                            wire:click="removeGalleryImage({{ $index }})"
                                            title="Remove Image">
                                            <i class="bi bi-x"></i>
                                        </button>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="admin-form-actions">
            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">{{ __('Cancel') }}</a>
            <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
        </div>
    </form>
</div>
