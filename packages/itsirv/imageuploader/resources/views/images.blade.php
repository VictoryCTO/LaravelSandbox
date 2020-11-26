<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>ImageUploader</title>
    </head>
    <body>
        <div>
            @foreach ($images as $image)
                    <img src="https://storage.googleapis.com/{{ $image->bucket }}/{{ $image->name }}" alt="{{ $image->name }}" />
            @endforeach
        </div>
    </body>
</html>
