<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\v1\NoteRequest;
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

    /**
     * Create a new note
     *
     * @param NoteRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(NoteRequest $request)
    {
        $note = $this->note->create($request->all());

        return $this->response->item($note, new NoteTransformer);
    }

    /**
     * Update a note.
     *
     * @param NoteRequest $request
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function update(NoteRequest $request, $id)
    {
        $note = $this->note->findOrFail($id);

        $note->update($request->all());

        return $this->response->item($note, new NoteTransformer);
    }

    /**
     * Delete a note.
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function destroy($id)
    {
        $note = $this->note->findOrFail($id);

        $note->delete($id);

        return $this->response->noContent();
    }
}
