;(function($, window, undefined) {
    "use strict";

    $(function() {
        const BASE_URL = $('body').data('url');
        const url = function(path) {
            return BASE_URL + (path || '');
        };

        var app = new Vue({
            el: '.app',
            data:
            {
                notes: [],
                editing: false,
                editingNote: {}
            },
            created: function()
            {
                this.loadNotes();
            },
            methods:
            {
                openEditor: function(note)
                {
                    if (note == null)
                    {
                        note = {
                            id: null,
                            title: 'New note',
                            text: ''
                        };
                    }

                    this.editing     = true;
                    this.editingNote = note;
                },
                closeEditor: function()
                {
                    app.editing     = false;
                    app.editingNote = {};
                },
                saveNote: function()
                {
                    $.post(url('note/save'), { note: this.editingNote }, function(response) {
                        app.closeEditor();
                        app.loadNotes();
                    })
                },
                loadNotes: function()
                {
                    $.post(url('note/list'), function(response) {
                        app.notes = response.data;
                    });
                },
                deleteNote: function(note)
                {
                    if (confirm('Are you sure?'))
                    {
                        if (app.editingNote.id == note.id)
                        {
                            app.closeEditor();
                        }

                        $.post(url('note/delete'), { noteID: note.id }, function(response) {
                            app.notes.splice(app.notes.indexOf(note), 1);
                        });
                    }
                }
            }
        });
    })

})(jQuery, window);