@if (session()->has('success'))
    <div id="" class="alert alert-dismissable alert-success">
        <button type="button" class="close" data-dismss="alert" aria-label="close">
            <span aria-hidden="true">&times;</span>
        </button>
        <strong>
            {!! session()->get('success') !!}
        </strong>
    </div><!--#  .alert alert-dismissable alert-sucess -->

@endif
