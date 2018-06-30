(function (factory) {
  /* global define */
  if (typeof define === 'function' && define.amd) {
    // AMD. Register as an anonymous module.
    define(['jquery'], factory);
  } else if (typeof module === 'object' && module.exports) {
    // Node/CommonJS
    module.exports = factory(require('jquery'));
  } else {
    // Browser globals
    factory(window.jQuery);
  }
} (function ($) {

  // Extends plugins for adding accordion.
  //  - plugin is external module for customizing.
  var AccordionPlugin = function (context) {
    var self = this;
    // ui has renders to build ui elements.
    //  - you can create a button with `ui.button`
    var ui = $.summernote.ui;
    var options = context.options;
    var lang = options.langInfo;

    // add accordion button
    context.memo('button.accordion', function () {
      // create button
      var button = ui.button({
        contents: '<i class="fa fa-bars"/>',
        container: false,
        tooltip: lang.accordion.name,
        click: function () {
          self.show();
        }
      });

      // create jQuery object from button instance.
      var $accordion = button.render();
      return $accordion;
    });

    // This events will be attached when editor is initialized.
    this.events = {
      // This will be called after modules are initialized.
      'summernote.init': function (we, e) {
        //   console.log('summernote initialized', we, e);
      },
      // This will be called when user releases a key on editable.
      'summernote.keyup': function (we, e) {
        //   console.log('summernote keyup', we, e);
      }
    };

    // This method will be called when editor is initialized by $('..').summernote();
    // You can create elements for plugin
    this.initialize = function () {
      var $container = options.dialogsInBody ? $(document.body) : context.layoutInfo.editor;
      var body = '<button href="#" class="btn btn-primary" id="add-accordion">' + lang.accordion.add + '</button>';
      var footer = '<button href="#" class="btn btn-primary" id="insert-accordion">' + lang.accordion.insert + '</button>';
      //Create dialog
      this.$dialog = ui.dialog({
        title: lang.accordion.insert,
        fade: options.dialogsFade,
        body: body,
        footer: footer
      }).render().appendTo($container);
      //Create logique for Add button and Delete button
      var $addBtn = self.$dialog.find('#add-accordion');
      $addBtn.click(function (event) {
        event.preventDefault();
        var accordionInput =
          '<div class="panel panel-default">' +
          '<div class="panel-body">' +
          '<i class="fa fa-close pull-right" aria-hidden="true"></i>' +
          '<div class="form-group">' +
          '<label>' + lang.accordion.title + '</label>' +
          '<input class="form-control" type="text" />' +
          '</div>' +
          '<div class="form-group">' +
          '<label>' + lang.accordion.content + '</label>' +
          '<textarea class="form-control" rows="4" />' +
          '</div>' +
          '</div>' +
          '</div>';
        $addBtn.before(accordionInput);
        var $deleteBtn = self.$dialog.find('.fa-close');
        $deleteBtn.click(function (event) {
          event.preventDefault();
          event.currentTarget.parentNode.parentNode.remove();
        });
      });
    };

    // This methods will be called when editor is destroyed by $('..').summernote('destroy');
    // You should remove elements on `initialize`.
    this.destroy = function () {
      this.$dialog.remove();
      this.$dialog = null;
    };

    this.show = function () {
      context.invoke('editor.saveRange');
      this.showAccordionDialog().then(function (data) {
        // [workaround] hide dialog before restore range for IE range focus
        ui.hideDialog(self.$dialog);
        context.invoke('editor.restoreRange');
        //Create accordion
        if (data.length > 0) {
          var accordionId = "accordion" + new Date().getTime();
          var toInsert = '<div class="panel-group" id="' + accordionId + '">';
          for (var i = 0; i < data.length; i++) {
            var myId = accordionId + (i + 1);
            toInsert = toInsert + '<div class="panel panel-default">';
            toInsert = toInsert + '<div class="panel-heading">';
            toInsert = toInsert + '<h4 class="panel-title">';
            toInsert = toInsert + '<a data-toggle="collapse" data-parent="#' + accordionId + '" href="#' + myId + '">' + data[i].title + '</a>';
            toInsert = toInsert + '</h4>';
            toInsert = toInsert + '</div>';
            toInsert = toInsert + '<div id="' + myId + '" class="panel-collapse collapse in">';
            toInsert = toInsert + '<div class="panel-body">';
            toInsert = toInsert + '<p>' + data[i].content + '</p>';
            toInsert = toInsert + '</div>';
            toInsert = toInsert + '</div>';
            toInsert = toInsert + '</div>';
          }
          toInsert = toInsert + '</div>';
          var div = document.createElement('div');
          div.innerHTML = toInsert;
          context.invoke('editor.insertNode', div);
        }
      }).fail(function () {
        context.invoke('editor.restoreRange');
      });
    };

    this.showAccordionDialog = function () {
      return $.Deferred(function (deferred) {
        ui.onDialogShown(self.$dialog, function () {
          //Add resolve to insert button
          var $insertBtn = self.$dialog.find('#insert-accordion');
          context.triggerEvent('dialog.shown');
          $insertBtn.click(function (event) {
            event.preventDefault();
            // Get user data
            var data = [];
            var $formInput = self.$dialog.find('.form-control');
            for (var i = 0; i < $formInput.length; i = i + 2) {
              data.push({
                title: $formInput[i].value,
                content: $formInput[i + 1].value
              });
            }
            deferred.resolve(data);
          });
        });

        ui.onDialogHidden(self.$dialog, function () {
          // Remove panel
          var $accordionPanel = self.$dialog.find('.panel');
          for (var i = 0; i < $accordionPanel.length; i++) {
            $accordionPanel[i].remove();
          }
        });

        ui.showDialog(self.$dialog);
      });
    };
  };

  $.extend(true, $.summernote, {
    plugins: {
      accordion: AccordionPlugin
    },
    lang: {
      'en-US': {
        accordion: {
          name: 'Accordion',
          insert: 'Insert Accordion',
          add: 'Add',
          title: 'Title',
          content: 'Content'
        }
      },
      'fr-FR': {
        accordion: {
          name: 'Accordéon',
          insert: 'Insérer un accordéon',
          add: 'Ajouter',
          title: 'Titre',
          content: 'Contenu'
        }
      }
    }
  });
}));