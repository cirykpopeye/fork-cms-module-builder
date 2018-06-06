$(document).ready(function() {
  let nodeSectionField = $('#node_section');

  let nodeId = null;

  if (jsBackend.data.exists('Builder') && jsBackend.data.exists('Builder.nodeId')) {
    nodeId = jsBackend.data.get('Builder.nodeId');
  }

  //-- Load fields for first time
  loadFields();

  //-- Load new fields, when changing the section
  nodeSectionField.on('change', loadFields);

  function loadFields() {
    let sectionId = nodeSectionField.val();

    $('#node_content').html('<p class="alert alert-warning">Loading fields <i class="fa fa-spinner fa-spin"></i></p>');


    $.ajax({
        data: {
            fork: { module: 'Builder', action: 'FieldsForSection' },
            section_id: sectionId,
            node_id: nodeId
        },
        success: function(data, textStatus) {
          $('#node_content').replaceWith(
            $(data.data.template).find('#node_content')
          );

          jsBackend.ckeditor.init();
        }
      }
    );
  }
});