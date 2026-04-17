<div>
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ __('Create Category') }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">{{ __('Form to create a Category') }}</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    @if (session()->has('message'))
        <div class="mb-4 text-green-600">
            {{ session('message') }}
        </div>
    @endif

    <flux:button variant="primary" href="{{ route('category.index') }}" color="red">Back</flux:button>

    <div class="col-span-2 mt-6">
        <form wire:submit.prevent="submit" class="space-y-6">
            <flux:input wire:model.defer="name" label="Name" class="col-span-2" />
            @error('name') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror

            <flux:input type="file" wire:model="image" label="Image" />
            @error('image') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror

            <div class="mb-4">
                <label for="status" class="block mb-2 font-medium">Status</label>
                <select wire:model.defer="status" id="status" class="w-full border-gray-300 rounded">
                    <option value="">-- Select Status --</option>
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                </select>
                @error('status') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
            </div>
            <flux:button type="submit" variant="primary">Submit</flux:button>
        </form>
    </div>
</div>
