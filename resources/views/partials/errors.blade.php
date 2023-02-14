
@if (isset($errors) && count($errors) > 0)
    <div id="" class="alert alert-dismissable alert-danger">
        <button type="button" class="close" data-dismss="alert" aria-label="close">
            <span aria-hidden="true">&times;</span>
        </button>
        <strong>
            <?php 
                if(is_object ($errors))
                {
            ?>
                @foreach($errors->all() as $error)
                    <li> {!! $error !!}</li>
                @endforeach
            <?php }else{?>
                <li> {!! $errors !!}</li>
            <?php } ?>
        </strong>
    </div><!--#  .alert alert-dismissable alert-sucess -->
@endif
