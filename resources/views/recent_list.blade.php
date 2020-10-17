Showing {{ count($recent_images) }} of {{ $total_qty }}<br/>
To delete an image, click on it.<br/>

@foreach ($recent_images as $recent_image)
  <img src="/storage/{{ $recent_image->getFname() }}" style="margin: 10px; max-width: 200px; max-height: 100px;" onClick="thisUploadPageManager.imageClicked({{ $recent_image->resource_id }});" />
@endforeach
