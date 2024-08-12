

    tinymce.init({
      selector: 'textarea',
      height: 350,
      menubar: false,
      plugins: [
        'advlist autolink lists link image charmap print preview anchor',
        'searchreplace visualblocks code fullscreen',
        'insertdatetime media table paste code help wordcount directionality',
        'code'
      ],
      toolbar: 'undo redo | formatselect | ' +
      'bold italic backcolor | alignleft aligncenter ' +
      'alignright alignjustify | bullist numlist outdent indent | rtl | ltr' +
      'removeformat | help | code | link  codesample | table ',
      content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }', 
      image_title: true,
      automatic_uploads: true,
      file_picker_types: 'file image media',

   });