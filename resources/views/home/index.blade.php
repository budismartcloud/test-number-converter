@extends('layout.main')
@section('title', $title)
@section('content')
    <h3>Number Converter</h3>
    <hr>

    <form method="POST" onsubmit="return false;" id="form-konten">
        <textarea name="keyword" id="keyword" placeholder="Masukkan Keyword Disini" class="form-control"></textarea>
        <br>
        <input type="submit" value="Convert Data" class="btn btn-primary btn-block">
    </form>

    <br>
    <div id="results"></div>

@endsection
@section('scripts')
    <script>
        $("#form-konten").submit(function(){
            let data = new FormData();
            let keyword = $("#keyword").val();
            if(keyword == ""){
                alert("Please fill keyword first");
            }else{
                data.append('keyword', keyword);
                $.ajax({
                    type: "POST",
                    url: "{{url('/convert')}}",
                    data: data,
                    contentType:false,
                    cache: false,
                    processData:false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        $("#results").html(data);
                    },
                    error: function() {
                        $("#results").html(data);
                    }
                });
            }
        });
    </script>
@endsection
