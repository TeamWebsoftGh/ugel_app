<div class="row">
    <div class="f_error form-group col-sm-12 col-md-12">
        <label for="__comments__">@if(isset($comment_text)) {{ $comment_text }} @else Approver Comments @endif<span class="red_class show-red" style="display:none"></span></label>
        <textarea id="__comments__"
                  name="@if(isset($approval_comment_field_name)){{$approval_comment_field_name}}@else{{'approver_comment'}}@endif"
                  class="form-control" rows="@if(isset($rows)){{ $rows }}@else 3 @endif"></textarea>
    </div>
</div>
