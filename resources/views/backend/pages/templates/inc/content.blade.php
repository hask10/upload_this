<form action="" method="POST" class="content-form">
    @csrf
    <input class="project_id" type="hidden" name="project_id"
        @if (isset($project)) value="{{ $project->id }}" @else value="" @endif>

    <div class="row justify-content-between align-items-center g-2 p-3">
        <div class="col-auto flex-grow-1">
            <input class="form-control border-0 px-2 project-title" type="text" id="title"name="title"
                placeholder="{{ localize('Your project title') }}..."
                @if (isset($project)) value="{{ $project->title }}" @else value="" @endif>
        </div>
        <div class="col-auto">
            <button type="button" class="tt-icon-btn tt-icon-info border-0 shadow-sm rounded-circle move_to_folder_btn"
                data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Move to folder"
                onclick="showSaveToFolderModal()"><i data-feather="folder"></i></button>
        </div>

        <div class="col-auto">
            <button type="button" class="tt-icon-btn tt-icon-warning border-0 shadow-sm rounded-circle copyBtn"
                data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Copy Contents"><i
                    data-feather="copy"></i></button>
        </div>

        <div class="col-auto">
            <button type="submit"
                class="tt-icon-btn tt-icon-success border-0 shadow-sm rounded-circle content-form-submit"
                data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Save Changes"><i
                    data-feather="save"></i></button>
        </div>
    </div>
    <div class="card-body d-flex flex-column h-100 tt-create-content-wrap p-0 border-top">
        <textarea class="editor content-editor" data-content-min-height="true"
            data-buttons='[["font", ["bold", "underline" , "italic" ]], ["fontname",["fontname"]], ["para", ["ul", "ol" , "paragraph" ]], ["style", ["style"]], ["fontsize", ["fontsize"]], ["insert", ["link"]], ["view", ["undo", "redo" ]]]'
            class="p-0 border-0" id="aiContents" name="contents">
@if (isset($project))
{{ $project->content }}
@endif
</textarea>
    </div>
</form>
