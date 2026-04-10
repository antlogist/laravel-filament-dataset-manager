<?php

use Livewire\Component;
use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads;

    public $file;

    public function save()
    {
        $this->validate([
            'file' => 'required|file|mimes:zip|max:204800',
        ]);
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

    <button
        class="fi-color fi-color-primary fi-bg-color-600 hover:fi-bg-color-500 dark:fi-bg-color-600 dark:hover:fi-bg-color-500 fi-text-color-0 hover:fi-text-color-0 dark:fi-text-color-0 dark:hover:fi-text-color-0 fi-btn fi-size-md"
        type="submit">Upload File</button>
</form>
