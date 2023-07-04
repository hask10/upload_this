<?php

namespace App\Http\Controllers\Backend\Projects;

use App\Http\Controllers\Controller;
use App\Models\Folder;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectsController extends Controller
{
    # project list
    public function index(Request $request)
    {
        if (!auth()->user()->can('projects') && auth()->user()->user_type != "customer") {
            abort(403);
        }

        $searchKey = null;
        $content_type = null;
        $projects = Project::latest();

        $projects = $projects->where('user_id', auth()->user()->id);

        if ($request->search != null) {
            $projects = $projects->where('title', 'like', '%' . $request->search . '%');
            $searchKey = $request->search;
        }


        if ($request->content_type != null) {
            $projects = $projects->where('content_type', $request->content_type);
            $content_type    = $request->content_type;
        }


        $projects = $projects->paginate(paginationNumber());
        return view('backend.pages.projects.index', compact('projects', 'content_type', 'searchKey'));
    }

    # edit project
    public function edit($slug)
    {
        if (!auth()->user()->can('projects') && auth()->user()->user_type != "customer") {
            abort(403);
        }

        $project = Project::where('slug', $slug)->first();
        return view('backend.pages.projects.edit', compact('project'));
    }


    # update project
    public function update(Request $request)
    {
        $project = Project::findOrFail((int)$request->project_id);
        $project->title = $request->title;
        $project->content = $request->contents;
        $project->save();
        return [
            'status'    => 200,
            'success'   => true
        ];
    }

    # delete project
    public function delete($id)
    {
        $project = Project::findOrFail($id);
        if (empty($project)) {
            abort(404);
        }
        $project->delete();
        flash(localize('Project has been deleted successfully'))->success();
        return back();
    }

    # move to folder modal open
    public function moveToFolderModalOpen(Request $request)
    {
        $project = Project::findOrFail((int)$request->project_id);
        $folders = Folder::latest();

        if (auth()->user()->user_type == "customer") {
            $folders = $folders->where('user_id', auth()->user()->id)->get();
        } else {
            $folders = $folders->get();
        }

        return [
            'status'    => 200,
            'success'   => true,
            'contents'  => view('backend.pages.templates.inc.saveToFolderModalContent', compact('project', 'folders'))->render()
        ];
    }

    # move to folder  
    public function moveToFolder(Request $request)
    {
        $project = Project::findOrFail((int)$request->project_id);
        $project->folder_id = $request->folder_id;
        $project->save();

        return [
            'status'    => 200,
            'success'   => true,
        ];
    }
}
