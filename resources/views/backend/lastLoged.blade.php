@extends('layouts.backend')

@section('content')
    <div class="row adminIndex">
        <div class="col-xs-12">
            <a href="" onclick="printMyPage()" class="btn btn-primary newRecored pull-left"><i class="fas fa-print"></i> {{ trans('main.print') }}</a>
            <style>
                .circle {
                    border-radius: 50%;
                    width:50px;
                    height:50px;
                }
            </style>
            
            <table class="table table-hover table-striped table-bordered order-column dataTable">
                <thead>
                    <tr>
                        <td width="5%">id</td>
                        <td width="8%">Photo</td>
                        <td width="30%">Name</td>
                        <td>Faculty</td>
                        <td>Date</td>
                        <td><i class="fas fa-cloud-upload-alt"></i></td>
                        <td><i class="fas fa-cloud-download-alt"></i></td>
                    </tr>
                </thead>;
                
            <?php     
            $i=1;
            foreach ($users as $user) {
               
                if(isset($user->photo) && file_exists('./uploads/'.$user->id.'/'.$user->photo->name)){
                  $photoLink = '<a href="' . url("/" . $user->name) .'"><img class="circle" src="'.url("/").'/'. $user->uploads() . $user->photo->name . '" alt="' . $user->name . '"></a>';
                }else{
                    $photoLink =  '<a href="' . url("/" . $user->name) .'"><img class="circle" src="'.url("/").'/images/fac_photo.png" alt="' . $user->name . '"></a>';
                }
                
                
                
                if($i<501){
            ?>
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{!! $photoLink !!}
                    <td><a href='{{ url('/' . $user->name) }}'>{{  $user->fullName  }}</a></td>
                    <td>{{ $user->faculty->name }}</td>
                    <td>{{ date('d-m-Y H:i:s', $user->lastLoged) }}</td>
                    <td>{{ $user->allfiles()->whereIn('fileable_type' , array('App\Subject', 'App\Supplement'))->count() }}</td>
                    <td>{{ $user->allfiles()->whereIn('fileable_type' , array('App\Subject', 'App\Supplement'))->sum('downloaded') }}</td>
                </tr>
            <?php 
                }
            }
            ?>
            </table>
        
        </div>
    </div>
@endsection