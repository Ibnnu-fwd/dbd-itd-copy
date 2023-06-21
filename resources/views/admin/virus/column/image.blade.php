<div class="avatar">
    <div class="w-24 rounded">
      <img src="{{
        $data->image ? asset('storage/virus/' . $data->image) : asset('assets/images/noimage.jpg')
      }}" />
    </div>
  </div>
