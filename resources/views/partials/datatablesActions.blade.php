@if(isset($viewGate) && $viewGate)
    <a class="btn btn-xs btn-primary" href="{{ route('admin.' . $crudRoutePart . '.show', $row->id) }}">
        {{ trans('global.view') }}
    </a>
@endif
@if(isset($editGate) && $editGate)
    <a class="btn btn-xs btn-info" href="{{ route('admin.' . $crudRoutePart . '.edit', $row->id) }}">
        {{ trans('global.edit') }}
    </a>
@endif
@if(isset($deleteGate) && $deleteGate)
    <form action="{{ route('admin.' . $crudRoutePart . '.destroy', $row->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
        <input type="hidden" name="_method" value="DELETE">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
    </form>
@endif
@if(isset($webhookSecretGate) && $webhookSecretGate)
    <a class="btn btn-xs btn-dark" href="{{ route('admin.' . $crudRoutePart . '.webhook', $row->id) }}">
        {{ trans('messages.webhook') }}
    </a>
@endif