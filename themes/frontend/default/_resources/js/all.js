;(function($, window, undefined) {
    "use strict";

    $(function() {
        const BASE_URL = $('body').data('url');
        const url = function(path) {
            return BASE_URL + (path || '');
        };

        new Vue({
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
            mounted: function()
            {
                $('.app').fadeIn();
            },
            methods:
            {
                openEditor: function(note)
                {
                    var me = this;

                    if (note == null)
                    {
                        note = {
                            id: null,
                            title: 'New note',
                            text: ''
                        };
                    }

                    me.editing     = true;
                    me.editingNote = note;
                },
                closeEditor: function()
                {
                    var me = this;

                    me.editing     = false;
                    me.editingNote = {};
                },
                saveNote: function()
                {
                    var me = this;

                    $.post(url('note/save'), { note: me.editingNote }, function(response) {
                        me.closeEditor();
                        me.loadNotes();
                    })
                },
                loadNotes: function()
                {
                    var me = this;

                    $.post(url('note/list'), function(response) {
                        me.notes = response.data;
                    });
                },
                deleteNote: function(note)
                {
                    var me = this;

                    if (confirm('Are you sure?'))
                    {
                        if (me.editingNote.id == note.id)
                        {
                            me.closeEditor();
                        }

                        $.post(url('note/delete'), { noteID: note.id }, function(response) {
                            me.notes.splice(me.notes.indexOf(note), 1);
                        });
                    }
                }
            }
        });
    })

})(jQuery, window);