Showing {{ count($recent_images) }} of {{ $total_qty }}<br/>
To delete an image, click on it.<br/>

@foreach ($recent_images as $recent_image)
  <img src="/cloud_image/{{ $recent_image->resource_key }}?size=thumb" style="margin: 10px; max-width: 200px; max-height: 100px;" onClick="thisUploadPageManager.imageClicked({{ $recent_image->resource_id }});" />
@endforeach

<br/>
<a href="/delete_all" onclick="return confirm('Are you sure you want to delete all the images?');">Delete All</a>
