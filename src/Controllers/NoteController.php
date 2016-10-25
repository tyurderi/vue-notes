<?php

namespace Notes\Controllers;

use Favez\Mvc\Controller;
use Notes\Models\Note;

class NoteController extends Controller
{

    public function listAction()
    {
        return $this->json()->success([
            'data' => Note::repository()->getQuery()->orderBy('changed DESC')->fetchAll()
        ]);
    }

    public function saveAction()
    {
        $note   = $this->request()->getParam('note');
        $noteID = $note['id'];

        if (!($model = Note::repository()->findByID($noteID)))
        {
            $model = new Note();
            $model->created  = date('Y-m-d H:i:s');
            $model->archived = 0;
        }

        $model->changed = date('Y-m-d H:i:s');
        $model->text    = $note['text'];
        $model->title   = $note['title'];
        $model->save();

        return $this->json()->success();
    }

    public function deleteAction()
    {
        $noteID = $this->request()->getParam('noteID');

        if ($note = Note::repository()->findByID($noteID))
        {
            $note->delete();
        }

        return $this->json()->success();
    }

}