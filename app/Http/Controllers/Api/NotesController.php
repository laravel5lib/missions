<?php

namespace App\Http\Controllers\Api;

use App\Http\Transformers\v1\NoteTransformer;
use App\Models\v1\Note;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NotesController extends Controller
{

    /**
     * @var Note
     */
    private $note;

    /**
     * NotesController constructor.
     * @param Note $note
     */
    public function __construct(Note $note)
    {
        $this->note = $note;
    }

    /**
     * Get all notes.
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function index(Request $request)
    {
        $notes = $this->note
            ->filter($request->all())
            ->paginate($request->get('per_page', 10));

        return $this->response->paginator($notes, new NoteTransformer);
    }

    /**
     * Get a note by it's id.
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function show($id)
    {
        $note = $this->note->findOrFail($id);

        return $this->response->item($note, new NoteTransformer);
    }
}
