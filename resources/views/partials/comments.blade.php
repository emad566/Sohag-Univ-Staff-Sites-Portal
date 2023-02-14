<div class="row comments">
    <form id='AddComment' class='' method='POST' action='{{ route('comments.store') }}'>
        {{ csrf_field() }}
        <input type='hidden' name='_method' value='post'>
        <input type='hidden' name='commentable_id' value='{{ $commentId }}'>
        <input type='hidden' name='commentable_type' value='App\{{ $commentType }}'>

        <div id="" class="form-group">
            <label for="body">Comment <span class="required">*</span></label>
            <textarea style="resize:vertical" rows="3" class="form-control autosize-target text-left" placeholder="Write A Comment"   name="body" id="body"></textarea>
        </div><!--#  .form-group -->

        <div id="" class="form-group">
            <label for="url">Proof a work done (Url/Photo) <span class="required">*</span></label>
            <textarea style="resize:vertical" rows="2" class="form-control autosize-target text-left" placeholder="Enter url or ScreenShots"   name="url" id="url"></textarea>
        </div><!--#  .form-group -->
        
        <div id="" class="form-group">
            <input type="submit" value="submit" class="btn bt-primary">
        </div><!--#  .form-group -->
    </form><!--#editcomment . -->

    <div class="col-sm-12">
    
        <!-- Fluid width widget -->        
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <span class="glyphicon glyphicon-comment"></span> 
                    Recent Comments
                </h3>
            </div>
            <div class="panel-body">
                <ul class="media-list">
                    @if($comments != null)
                        @foreach($comments as $Comment)
                        <li class="media">
                            <div class="media-left">
                                <img src="http://placehold.it/60x60" class="img-circle">
                            </div>
                            <div class="media-body">
                                <h4 class="media-heading">
                                    {{ $Comment->user->first_name }} {{ $Comment->user->middle_name }}
                                    <br>
                                    <small>
                                        commented on {{ $Comment->created_at }}
                                    </small>
                                </h4>
                                <p>
                                    {{ $Comment->body }}
                                </p>

                                proof:
                                <p>
                                    {{ $Comment->url }}
                                </p>
                            </div>
                        </li>
                        @endforeach
                    @endif
                </ul>
            </div>
        </div>
        <!-- End fluid width widget --> 
        
    </div>
</div>