<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <!-- Start Paper path check -->
    <?php $actual_link = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>
    @if($actual_link === 'https://staffsites.sohag-univ.edu.eg/uploads/2/1609869845%20-%2001_1-7-2020_Adaptive%20Multimodal%20Feature%20Fusion%20for%20Content-BasedImage%20Classification%20and%20Retrieval.pdf')
    <meta http-equiv="Refresh" content="0; url=https://dc.naturalspublishing.com/amis/vol14/iss4/18/">
    <?php return; ?>
    @endif
    <!-- /End Paper path check -->
     
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @if(Auth::check() && Auth::user()->role_id !=2)
      <?php 
      $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
      
      
      if( Auth::user()->photo_id && !file_exists('./uploads/'.Auth::user()->id.'/'.Auth::user()->photo->name)){
          if ('http://staffsites.sohag-univ.edu.eg/stuff/user/'.Auth::user()->id."/edit" != $actual_link ) {
              
              header("Location: https://staffsites.sohag-univ.edu.eg/".'stuff/user/'.Auth::user()->id.'/edit');

              exit();
          }
      }elseif( !Auth::user()->photo_id){
        if ('http://staffsites.sohag-univ.edu.eg/stuff/user/'.Auth::user()->id."/edit" != $actual_link ) {
            
            header("Location: https://staffsites.sohag-univ.edu.eg/".'stuff/user/'.Auth::user()->id.'/edit');

            exit();
        }
      }
      ?>
      @if( Auth::user()->photo_id && !file_exists('./uploads/'.Auth::user()->id.'/'.Auth::user()->photo->name)){
      <script>
          alert("عذرا تم حذف الصورة الشخصية لك: نعتذر عند حدوث هذا الأمر،!!! من فضلك قم برفع الصورة الشخصية لك مرة أخري من خلال هذه الصفحة ثم اضغط حفظ التعديلات في الأسفل حتي تتمكن من استخدام باقي خصائص الموقع. شكرا لتفهمك!");
      </script>
      @elseif( !Auth::user()->photo_id)
      <script>
          alert("عذرا! يجب رفع صورة شخصية حتي تتمكن من استخدام باقي خصائص الموقع! شكرا لتفهمكم")
      </script>
      @endif
    @endif
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @yield('headMeta')
	<?php $version = 73; ?>
    <!-- Styles Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Bootstrap RTL-->
    @if(app()->getLocale() == "ar") 
      <link rel="stylesheet" href="//cdn.rawgit.com/morteza/bootstrap-rtl/v3.3.4/dist/css/bootstrap-rtl.min.css">
    @endif

     <!-- Fontawesome Fonts-->
     <script src="https://kit.fontawesome.com/c7125b87e6.js" crossorigin="anonymous"></script>
         
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.25.0/codemirror.min.css">
 
    @if(Auth()->check())
    <!-- datatables css-->
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css">
 
    {{-- //dropzon upload files --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.4.0/min/dropzone.min.css">
    @endif 

    @yield('styles')
    
    <!-- web Emadeleen CSS -->
    <link rel="stylesheet" href="{{ asset('css/webcss.css?v='.$version)  }}">
    
    @if(app()->getLocale() == "en")
	    <link rel="stylesheet" href="{{ asset('css/webcssltr.css?v='.$version)  }}">
    @endif
    
    <style type="text/css" media="print">
    @page {
        size: auto;   /* auto is the initial value */
        margin: 0;  /* this affects the margin in the printer settings */
    }
    </style>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js does not work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body id="body"> 

    @yield('allContent')
    <!-- jQuery (necessary for Bootstrap s JavaScript plugins) -->
    <!-- upload progrss bar -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>  

    @if(Auth()->check())
    <!-- Include datatables js -->
    <script src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>  
    <script src="//cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js"></script>  
    

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.25.0/codemirror.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.25.0/mode/xml/xml.min.js"></script>
 
    
    {{-- //dropzon upload files --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.4.0/min/dropzone.min.js"></script>
    

    <!-- Initialize the editor. -->
    <script src="https://cdn.tiny.cloud/1/t25js77ya6y844vjfbq2afel4dxhwpcmwmrlnfr3lwzibz47/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>    
    <script>
      tinymce.init({
           selector: "textarea.editor",
           plugins: 'print preview   importcss tinydrive searchreplace autolink autosave save directionality  visualblocks visualchars fullscreen image link media  template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help   charmap quickbars emoticons',
            tinydrive_token_provider: 'URL_TO_YOUR_TOKEN_PROVIDER',
            tinydrive_dropbox_app_key: 'YOUR_DROPBOX_APP_KEY',
            tinydrive_google_drive_key: 'YOUR_GOOGLE_DRIVE_KEY',
            tinydrive_google_drive_client_id: 'YOUR_GOOGLE_DRIVE_CLIENT_ID',
            mobile: {
            plugins: 'print preview   importcss tinydrive searchreplace autolink autosave save directionality  visualblocks visualchars fullscreen image link media  template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount textpattern noneditable help  charmap quickbars emoticons'
            },
            menubar: 'file edit view insert format tools table tc help',
            toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist checklist | forecolor backcolor casechange  formatpainter removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media template link anchor codesample | a11ycheck ltr rtl | showcomments addcomment',
            autosave_ask_before_unload: true,
            autosave_interval: '30s',
            autosave_prefix: '{path}{query}-{id}-',
            autosave_restore_when_empty: false,
            autosave_retention: '2m',
            image_advtab: true,
            link_list: [
            { title: 'My page 1', value: 'https://www.tiny.cloud' },
            { title: 'My page 2', value: 'http://www.moxiecode.com' }
            ],
            image_list: [
            { title: 'My page 1', value: 'https://www.tiny.cloud' },
            { title: 'My page 2', value: 'http://www.moxiecode.com' }
            ],
            image_class_list: [
            { title: 'None', value: '' },
            { title: 'Some class', value: 'class-name' }
            ],
            importcss_append: true,
            templates: [
              { title: 'New Table', description: 'creates a new table', content: '<div class="mceTmpl"><table width="98%%"  border="0" cellspacing="0" cellpadding="0"><tr><th scope="col"> </th><th scope="col"> </th></tr><tr><td> </td><td> </td></tr></table></div>' },
            { title: 'Starting my story', description: 'A cure for writers block', content: 'Once upon a time...' },
            { title: 'New list with dates', description: 'New List with dates', content: '<div class="mceTmpl"><span class="cdate">cdate</span><br /><span class="mdate">mdate</span><h2>My List</h2><ul><li></li><li></li></ul></div>' }
            ],
            template_cdate_format: '[Date Created (CDATE): %m/%d/%Y : %H:%M:%S]',
            template_mdate_format: '[Date Modified (MDATE): %m/%d/%Y : %H:%M:%S]',
            height: 300,
            image_caption: true,
            quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
            noneditable_noneditable_class: 'mceNonEditable',
            toolbar_mode: 'sliding',
            spellchecker_whitelist: ['Ephox', 'Moxiecode'],
            content_style: '.mymention{ color: gray; }',
            contextmenu: 'link image imagetools table configurepermanentpen',
            a11y_advanced_options: true,
            //   skin: useDarkMode ? 'oxide-dark' : 'oxide',
            //   content_css: useDarkMode ? 'dark' : 'default',
            /*
            The following settings require more configuration than shown here.
            For information on configuring the plugin, see:
            https://www.tiny.cloud/docs/plugins/premium/mentions/.
            */
            //   mentions_fetch: mentions_fetch,
            //   mentions_menu_hover: mentions_menu_hover,
            //   mentions_menu_complete: mentions_menu_complete,
            //   mentions_select: mentions_select
        });
    </script>

    <script>
      $(document).ready(function(){
        if($('textarea').length){
          $( "#submitFormCreate" ).click(function() {
            $( "#CreateUser" ).submit();
          });
        }
      })
      
      
    </script>


    <script>
      $(document).ready(function() {
        
        $( ".close" ).click(function() {
          $('.alert-dismissable').hide();
        });
        //dropzone file types
      Dropzone.options.dropzone = {
        acceptedFiles: 'image/*,application/pdf,.psd',
        maxFilesize: 8192,
        init: function() {
          this.on("uploadprogress", function(file, progress) {
            console.log("File progress", progress);
          });
        }
      }

      $('.dz-hidden-input').attr('accept', 'image/x-png, image/gif, image/jpeg');
        
      @if(app()->getLocale() == "ar")
      /*======================================
        Main nav bar
        =====================================*/
        $('.dataTable').dataTable( {
          "language": {
            "search": "بحث :",
            "sLengthMenu ": "اعرض ",
          },
          "lengthMenu": [[50, 100, 200, 500 , 1000, -1], [50, 100, 200, 500 , 1000, "All"]],
          "pageLength": 50,
          "oLanguage": {
            "sProcessing":   "جارٍ التحميل...",
            "sLengthMenu":   "أظهر _MENU_ مدخلات",
            "sZeroRecords":  "لم يعثر على أية سجلات",
            "sInfo":         "إظهار _START_ إلى _END_ من أصل _TOTAL_ مدخل",
            "sInfoEmpty":    "يعرض 0 إلى 0 من أصل 0 سجل",
            "sInfoFiltered": "(منتقاة من مجموع _MAX_ مُدخل)",
            "sInfoPostFix":  "",
            "sSearch":       "ابحث:",
            "sUrl":          "",
            "oPaginate": {
                "sFirst":    "الأول",
                "sPrevious": "السابق",
                "sNext":     "التالي",
                "sLast":     "الأخير"
            }
        }
        } );
      @else
        $('.dataTable').dataTable();
      @endif

      
      });
    </script>
    @endif

    <!-- webjs Emadeldeen js -->
    {{-- <script src="{{ asset('circle/scripts/plugin.js') }}"></script>
    <script src="{{ asset('js/getBrowser.js') }}" ></script>
    <script src="{{ asset('js/webjs.js?'.$version) }}" ></script>   --}}
    
    @yield('scripts')
    @yield('scriptsData')
    <script>
      var $ipDesc = ""
      function getIP(json) {
        $ipDesc = json.ip
      }
      </script>
      <script src="https://api.ipify.org?format=jsonp&callback=getIP"></script>
      
    <script>
        $(document).ready(function() {

          
        // NUm File Downloaded
         $('.downloadedA').click(function(){
            // alert($(this).attr('id'));
            $did = $(this).attr('id')
            
            $.ajax({
              type:'get',
              url:'{{ URL::to("stuff/files/updateDownloaded") }}',
              data: { 'downloaded': $did},
              success: function(data){
                @if(Auth::check() && Auth::user()->isCpanel())
                  $("#yourip").html(data);
                  console.log(data);
                @else
                  console.log(data);
                @endif
              }
            });
            
            // alert("Emad");
            //return false;
          });
          
            
          @yield('jqScript')
      })
    </script>
    
    <script>
        <?php use App\User;
        $allUsers = User::where('isActive', '=', '1')->where('role_id', '=', '1')->orWhere('role_id', '=', '3')->whereNotNull('fullName')->orderBy('name', 'ASC')->get();
        $count = (isset($staffCount) && $staffCount>0)? $staffCount  : $allUsers->count();
        ?>
         
      
      
    </script>
    @if(Auth::check())
    {{ Auth::user()->mostActiveSet() }}
    @endif
  </body>

    <script>
      $(window).on('load', function() {
        $('.dz-hidden-input').attr('accept', '.doc,.zip,.rar,.docx, .xls, .xlsx, .ppt,.pptx,.png,.JPG,image/jpeg,image/gif,image/png,application/pdf,application/zip,application/rar');
      });
	  
	    function printMyPage() {
        /* sel =  document.getElementById("faculty_id")
        sel.options[sel.selectedIndex].text
        document.title =  sel.options[sel.selectedIndex].text */
        //document.title = $("#faculty_id").text();

        window.print();
      }
	  
    </script>
    
</html>