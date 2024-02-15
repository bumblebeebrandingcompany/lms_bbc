<div class="row">
    <div class="col-md-12" id="document_list">
        <div class="form-group row">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control" placeholder="Search doc..." id="doc_search_input">
            </div>
            <div class="col-md-6">
                <select name="project_filter" id="project_filter"
                    class="form-select select2" style="width: 100%;">
                    <option value="">@lang('messages.all')</option>
                    @foreach($projects_list as $id => $name)
                        <option value="{{$id}}">{{$name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div id="document_accordion" class="list">
        </div>
    </div>
</div>
