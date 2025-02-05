<form method="POST" id="{{$id}}" action="{{ $action }}" class="generic-form">
    @csrf
    @method($method)

    @foreach ($fields as $field)
        <div class="form-group">
            <label for="{{ $field['name'] }}">{{ $field['label'] }}</label>
            @if ($field['type'] === 'text' || $field['type'] === 'number' || $field['type'] === 'date' || $field['type'] === 'email' || $field['type'] === 'password')
                <input type="{{ $field['type'] }}" name="{{ $field['name'] }}" id="{{ $field['name'] }}" value="{{ $field['value'] }}" @foreach ($field['attributes'] as $attr => $value) {{ $attr }}="{{ $value }}" @endforeach>
            @elseif ($field['type'] === 'textarea')
                <textarea name="{{ $field['name'] }}" id="{{ $field['name'] }}" @foreach ($field['attributes'] as $attr => $value) {{ $attr }}="{{ $value }}" @endforeach>{{ $field['value'] }}</textarea>
            @elseif ($field['type'] === 'select')
                <select name="{{ $field['name'] }}" id="{{ $field['name'] }}" @foreach ($field['attributes'] as $attr => $value) {{ $attr }}="{{ $value }}" @endforeach>
                    @foreach ($field['options'] as $option)
                        <option value="{{ $option['value'] }}" @if ($option['value'] == $field['value']) selected @endif>{{ $option['label'] }}</option>
                    @endforeach
                </select>
            @elseif ($field['type'] === 'checkbox')
                <input type="checkbox" name="{{ $field['name'] }}" id="{{ $field['name'] }}" @if ($field['value']) checked @endif @foreach ($field['attributes'] as $attr => $value) {{ $attr }}="{{ $value }}" @endforeach>
                <label for="{{ $field['name'] }}">{{ $field['label'] }}</label>
            @elseif ($field['type'] === 'file')
                <input type="file" name="{{ $field['name'] }}" id="{{ $field['name'] }}" @foreach ($field['attributes'] as $attr => $value) {{ $attr }}="{{ $value }}" @endforeach>
            @endif
        </div>
    @endforeach

    <button type="submit">{{ $submitText }}</button>
</form>
