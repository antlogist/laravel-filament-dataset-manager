<?php

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Services\ZipUploader;
use Illuminate\Support\Facades\Log;

new class extends Component {
    use WithFileUploads;

    public $file;
    public $host;
    public $name;

    public function save()
    {
        $this->validate([
            'file' => 'required|file|mimes:zip|max:204800',
            'host' => 'required|exists:hosts,id',
            'name' => 'required|unique:uploaded_files,name',
        ]);

        try {
            $uploader = new ZipUploader();
            $uploader->save($this->file, $this->host, $this->name);
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            $this->dispatch('notify', ['message' => $e->getMessage()]);
        }
    }
};
?>

<form wire:submit="save">

    <div style="margin-bottom: 1rem">
        <input class="form-control" id="file-input" type="file" wire:model="file"
            accept="application/zip, application/x-zip-compressed, multipart/x-zip">
    </div>

    @error('file')
        <div style="margin-bottom: 1rem">
            <span style="color: red;">{{ $message }}</span>
        </div>
    @enderror

    <div style="margin-bottom: 1rem">
        <x-filament::input.wrapper>
            <x-filament::input.select wire:model="host">
                <option value="">Select host</option>
                @foreach (\App\Models\Host::all()->where('status', 'active') as $host)
                    <option value="{{ $host->id }}">{{ $host->type }} - {{ $host->name }}</option>
                @endforeach
            </x-filament::input.select>
        </x-filament::input.wrapper>
    </div>

    @error('host')
        <div style="margin-bottom: 1rem">
            <span style="color: red;">{{ $message }}</span>
        </div>
    @enderror

    <div style="margin-bottom: 1rem">
        <x-filament::input.wrapper>
            <x-filament::input type="text" wire:model="name" placeholder="Enter dataset name" />
        </x-filament::input.wrapper>
    </div>

    @error('name')
        <div style="margin-bottom: 1rem">
            <span style="color: red;">{{ $message }}</span>
        </div>
    @enderror

    <button
        class="fi-color fi-color-primary fi-bg-color-600 hover:fi-bg-color-500 dark:fi-bg-color-600 dark:hover:fi-bg-color-500 fi-text-color-0 hover:fi-text-color-0 dark:fi-text-color-0 dark:hover:fi-text-color-0 fi-btn fi-size-md"
        type="submit">Upload File</button>
</form>

<script>
    window.addEventListener('notify', event => {
        alert('The message: ' + event.detail[0]['message']);
    })
</script>
